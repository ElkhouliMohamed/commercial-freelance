<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Devis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rdv_id',
        'contact_id',
        'freelancer_id',
        'montant',
        'commission_rate',
        'commission',
        'statut',
        'date_validite',
        'notes',
        'compte_pour_commission',
    ];

    protected $casts = [
        'montant' => 'float',
        'date_validite' => 'date',
        'statut' => 'string',
        'compte_pour_commission' => 'boolean',
    ];

    protected $dates = ['deleted_at', 'date_validite'];

    protected $attributes = [
        'statut' => 'Brouillon',
        'compte_pour_commission' => false,
    ];

    public function rdv()
    {
        return $this->belongsTo(Rdv::class, 'rdv_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'devis_plan', 'devis_id', 'plan_id')->withTimestamps();
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('statut', $status);
    }

    public function scopeValid($query)
    {
        return $query->where('date_validite', '>=', now());
    }

    public function scopeByFreelancer($query, $freelancerId)
    {
        return $query->where('freelancer_id', $freelancerId);
    }

    protected static function booted()
    {
        static::created(function ($devis) {
            Log::info('Devis created', [
                'devis_id' => $devis->id,
                'statut' => $devis->statut,
                'freelancer_id' => $devis->freelancer_id,
            ]);
            if ($devis->statut === 'validé') {
                $devis->updateCommission();
            }
        });

        static::updated(function ($devis) {
            Log::info('Devis updated', [
                'devis_id' => $devis->id,
                'original_statut' => $devis->getOriginal('statut'),
                'new_statut' => $devis->statut,
                'freelancer_id' => $devis->freelancer_id,
            ]);
            if ($devis->isDirty('statut') && $devis->statut === 'validé') {
                $devis->updateCommission();
            }
        });
    }

    public function updateCommission()
    {
        Log::info('Checking commission update', [
            'devis_id' => $this->id,
            'compte_pour_commission' => $this->compte_pour_commission,
            'freelancer_id' => $this->freelancer_id,
        ]);

        if (!$this->compte_pour_commission) {
            $this->update(['compte_pour_commission' => true]);

            $freelancer = $this->freelancer;
            if ($freelancer) {
                $commissionController = new \App\Http\Controllers\CommissionController();
                $contractCount = $commissionController->getValidContractCountForFreelancer($freelancer->id);
                $commissionLevel = $commissionController->getCommissionLevel($contractCount);

                Log::info('Commission calculation', [
                    'freelancer_id' => $freelancer->id,
                    'contract_count' => $contractCount,
                    'commission_level' => $commissionLevel,
                ]);

                if ($commissionLevel) {
                    $existingCommission = $freelancer->commissions()
                        ->where('statut', 'en attente')
                        ->first();

                    if ($existingCommission) {
                        $existingCommission->update([
                            'montant' => $commissionLevel['fixed_amount'],
                            'description' => "Commission {$commissionLevel['name']} pour {$contractCount} contrats",
                            'niveau' => $commissionLevel['name'],
                            'nombre_contrats' => $contractCount,
                        ]);
                        Log::info('Updated existing commission', ['commission_id' => $existingCommission->id]);
                    } else {
                        $newCommission = Commission::create([
                            'freelancer_id' => $freelancer->id,
                            'montant' => $commissionLevel['fixed_amount'],
                            'description' => "Commission {$commissionLevel['name']} pour {$contractCount} contrats",
                            'statut' => 'en attente',
                            'niveau' => $commissionLevel['name'],
                            'nombre_contrats' => $contractCount,
                        ]);
                        Log::info('Created new commission', ['commission_id' => $newCommission->id]);
                    }
                } else {
                    Log::warning('No commission level found', ['contract_count' => $contractCount]);
                }
            } else {
                Log::warning('No freelancer found for devis', ['devis_id' => $this->id]);
            }
        } else {
            Log::info('Devis already counted for commission', ['devis_id' => $this->id]);
        }
    }
}

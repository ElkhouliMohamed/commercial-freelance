<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'price', 'description'];

    public function devis()
    {
        return $this->belongsToMany(Devis::class, 'devis_plan', 'plan_id', 'devis_id')->withTimestamps();
    }
}

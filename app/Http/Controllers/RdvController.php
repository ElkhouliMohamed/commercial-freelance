<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Rdv;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\AssignedToRdv;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RdvUpdated;
use App\Notifications\RdvCancelled;

class RdvController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Freelancer|Account Manager|Admin|Super Admin');
    }

    /**
     * Display a listing of the RDVs for the authenticated user.
     */
    public function index()
    {
        $user = auth()->user();
        $rdvs = Rdv::query()
            ->with(['contact', 'freelancer', 'manager'])
            ->when($user->hasRole('Freelancer'), function ($query) use ($user) {
                return $query->where('freelancer_id', $user->id);
            })
            ->when($user->hasRole('Account Manager'), function ($query) use ($user) {
                return $query->where('manager_id', $user->id);
            })
            ->orderBy('date', 'asc')
            ->paginate(10);

        return view('rdvs.index', [
            'rdvs' => $rdvs,
            'upcomingCount' => Rdv::upcoming()->count(),
            'pastCount' => Rdv::past()->count(),
        ]);
    }

    /**
     * Show the form for creating a new RDV.
     */
    public function create()
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('create', Rdv::class);
        $plans = Plan::all();
        $contacts = Contact::where('freelancer_id', auth()->id())
            ->active()
            ->get();

        return view('rdvs.create', [
            'contacts' => $contacts,
            'rdvTypes' => Rdv::getTypeOptions(),
            'minDate' => now()->addDay()->format('Y-m-d'),
            'maxDate' => now()->addMonths(3)->format('Y-m-d'),
            'plans' => $plans,
        ]);
    }

    /**
     * Store a newly created RDV in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'date' => 'required|date|after:now',
            'type' => 'required|string',
            'notes' => 'nullable|string',
            'plans' => 'required|array', // Ensure plans is an array
            'plans.*' => 'exists:plans,id', // Ensure each plan ID exists
        ]);

        // Select a random account manager
        $manager = User::role('Account Manager')
            ->inRandomOrder()
            ->first();

        if (!$manager) {
            return redirect()->back()->with('error', 'Aucun Account Manager n\'est disponible.');
        }

        // Create the RDV
        $rdv = Rdv::create([
            'contact_id' => $validated['contact_id'],
            'freelancer_id' => auth()->id(),
            'manager_id' => $manager->id, // Assign the selected manager
            'date' => $validated['date'],
            'type' => $validated['type'],
            'notes' => $validated['notes'] ?? null,
            'statut' => Rdv::STATUS_PLANNED,
        ]);

        // Attach selected plans to the RDV
        $rdv->plans()->attach($validated['plans']);

        // Notify the assigned manager
        $manager->notify(new AssignedToRdv($rdv));

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous créé avec succès et assigné à un Account Manager.');
    }

    /**
     * Display the specified RDV.
     */
    public function show(Rdv $rdv)
    {
        return view('rdvs.show', [
            'rdv' => $rdv->load(['contact', 'freelancer', 'manager', 'devis']),
        ]);
    }

    /**
     * Show the form for editing the specified RDV.
     */
    public function edit(Rdv $rdv)
    {
        Gate::authorize('update', $rdv);

        // No need to re-query the rdv since it's already injected
        $contacts = Contact::where('freelancer_id', auth()->id())
            ->active()
            ->get();

        $plans = Plan::all();

        return view('rdvs.edit', [
            'rdv' => $rdv,
            'contacts' => $contacts,
            'rdvTypes' => Rdv::getTypeOptions(),
            'statusOptions' => Rdv::getStatusOptions(),
            'minDate' => now()->addDay()->format('Y-m-d'),
            'maxDate' => now()->addMonths(3)->format('Y-m-d'),
            'plans' => $plans,
        ]);
    }


    /**
     * Update the specified RDV in storage.
     */
    public function update(Request $request, Rdv $rdv)
    {
        Gate::authorize('update', $rdv);

        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id,freelancer_id,' . auth()->id(),
            'date' => 'required|date|after:now|before:' . now()->addMonths(3),
            'type' => 'required|in:' . implode(',', Rdv::getTypeOptions()),
            'statut' => 'required|in:' . implode(',', Rdv::getStatusOptions()),
            'notes' => 'nullable|string|max:500',
            'location' => 'required_if:type,' . Rdv::TYPE_PHYSICAL . '|string|max:255',
        ]);

        $originalStatus = $rdv->statut;
        $rdv->update($validated);

        // Notify stakeholders about important changes
        if ($originalStatus !== $rdv->statut) {
            $this->handleStatusChangeNotification($rdv, $originalStatus);
        } else {
            Notification::send([$rdv->manager, $rdv->freelancer], new RdvUpdated($rdv));
        }

        return redirect()->route('rdvs.index')
            ->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    /**
     * Cancel the specified RDV.
     */
    public function cancel(Rdv $rdv)
    {
        Gate::authorize('update', $rdv);

        if (!$rdv->canBeCancelled()) {
            return back()->with('error', 'Ce rendez-vous ne peut pas être annulé.');
        }

        $rdv->update(['statut' => Rdv::STATUS_CANCELLED]);

        Notification::send([$rdv->manager, $rdv->contact], new RdvCancelled($rdv));

        return redirect()->route('rdvs.index')
            ->with('success', 'Rendez-vous annulé avec succès.');
    }

    /**
     * Remove the specified RDV from storage.
     */
    public function destroy(Rdv $rdv)
    {
        $rdv->delete();

        return redirect()->route('rdvs.index')
            ->with('success', 'Rendez-vous supprimé avec succès.');
    }

    /**
     * Handle notifications for RDV status changes.
     */
    protected function handleStatusChangeNotification(Rdv $rdv, string $originalStatus): void
    {
        switch ($rdv->statut) {
            case Rdv::STATUS_CANCELLED:
                Notification::send([$rdv->manager, $rdv->contact], new RdvCancelled($rdv));
                break;

            case Rdv::STATUS_CONFIRMED:
                Notification::send([$rdv->freelancer, $rdv->manager], new RdvConfirmed($rdv));
                break;

            case Rdv::STATUS_COMPLETED:
                Notification::send([$rdv->manager], new RdvCompleted($rdv));
                break;

            default:
                Notification::send([$rdv->manager, $rdv->freelancer], new RdvUpdated($rdv));
        }
    }
}

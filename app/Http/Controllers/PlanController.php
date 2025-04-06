<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:Admin|Super Admin']);
    }

    // Display a list of plans
    public function index()
    {
        $plans = Plan::paginate(10); // Paginate plans
        return view('plans.index', compact('plans'));
    }

    // Show the form to create a new plan
    public function create()
    {
        return view('plans.create');
    }

    // Store a new plan in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Plan::create($request->all());

        return redirect()->route('plans.index')->with('success', 'Plan créé avec succès.');
    }

    // Show the form to edit an existing plan
    public function edit(Plan $plan)
    {
        return view('plans.edit', compact('plan'));
    }

    // Update an existing plan in the database
    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $plan->update($request->all());

        return redirect()->route('plans.index')->with('success', 'Plan mis à jour avec succès.');
    }

    // Delete a plan
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'Plan supprimé avec succès.');
    }
}

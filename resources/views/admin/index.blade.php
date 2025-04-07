@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Superadmin Dashboard</h1>
            <p class="text-gray-600">Welcome back! Here's what's happening today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- RDVs Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 font-medium">Total RDVs</p>
                        <h2 class="text-3xl font-bold text-indigo-600">{{ $rdvCount }}</h2>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Contacts Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 font-medium">Total Contacts</p>
                        <h2 class="text-3xl font-bold text-blue-600">{{ $contactCount }}</h2>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Devis Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 font-medium">Total Devis</p>
                        <h2 class="text-3xl font-bold text-green-600">{{ $devisCount }}</h2>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent RDVs -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Recent RDVs</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($latestRdvs as $rdv)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800">{{ $rdv->name }}</p>
                                <p class="text-sm text-gray-500">{{ $rdv->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full">{{ $rdv->status ?? 'Pending' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500">
                        No recent RDVs found
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <a href="{{ route('rdvs.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
                </div>
            </div>

            <!-- Recent Contacts -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Recent Contacts</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($latestContacts as $contact)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800">{{ $contact->name }}</p>
                                <p class="text-sm text-gray-500">{{ $contact->email }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">New</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-600 truncate">{{ $contact->message }}</p>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500">
                        No recent contacts found
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <a href="{{ route('contacts.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
                </div>
            </div>

            <!-- Recent Devis -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-green-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Recent Devis</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($latestDevis as $devi)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800">{{ $devi->client_name }}</p>
                                <p class="text-sm text-gray-500">{{ $devi->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ $devi->status ?? 'Pending' }}</span>
                        </div>
                        <p class="mt-1 text-sm font-medium text-gray-900">€{{ number_format($devi->total_amount, 2) }}</p>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500">
                        No recent devis found
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <a href="{{ route('devis.index') }}" class="text-sm font-medium text-green-600 hover:text-green-500">View all</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
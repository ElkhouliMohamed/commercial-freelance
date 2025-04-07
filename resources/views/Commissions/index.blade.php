@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">Commissions Overview</h1>
        <p class="text-gray-600 mt-1">Track and manage your commission earnings</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h5 class="text-sm font-medium text-gray-600">Total Earnings</h5>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ number_format($stats['total_amount'], 2) }} MAD</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h5 class="text-sm font-medium text-gray-600">Paid Commissions</h5>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $stats['paid'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h5 class="text-sm font-medium text-gray-600">Pending Approvals</h5>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h5 class="text-sm font-medium text-gray-600">Total Contracts</h5>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $stats['total_contracts'] }}</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All</option>
                    <option value="En Attente" {{ request('status') === 'En Attente' ? 'selected' : '' }}>Pending</option>
                    <option value="Payé" {{ request('status') === 'Payé' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div class="flex-1">
                <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                <select name="per_page" id="per_page" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Apply Filters</button>
            </div>
        </form>
    </div>

    <!-- Commissions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-4">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 mb-4">{{ session('warning') }}</div>
        @endif

        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h5 class="text-lg font-semibold text-gray-800">Commission Records</h5>
            @can('create', App\Models\Commission::class)
                <a href="{{ route('commissions.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Request Commission
                </a>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contracts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($commissions as $commission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commission->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commission->niveau }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($commission->montant, 2) }} MAD</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commission->nombre_contrats }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $commission->statut === 'Payé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $commission->statut === 'Payé' ? 'Paid' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $commission->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('commissions.show', $commission) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                                @can('approve', $commission)
                                    @if ($commission->statut === 'En Attente')
                                        <button class="text-green-600 hover:text-green-900 font-medium approve-commission" 
                                                data-id="{{ $commission->id }}"
                                                data-modal-target="approveModal">
                                            Approve
                                        </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No commissions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-200">
            {{ $commissions->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Approve Modal -->
@can('approve', App\Models\Commission::class)
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" tabindex="-1">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <form id="approveForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex justify-between items-center mb-6">
                <h5 class="text-lg font-semibold text-gray-800">Approve Commission</h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl close-modal">&times;</button>
            </div>
            <div class="space-y-6">
                <div>
                    <label for="proof" class="block text-sm font-medium text-gray-700">Payment Proof</label>
                    <input type="file" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           name="proof" id="proof" accept=".pdf,.jpg,.png" required>
                </div>
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           name="payment_date" id="payment_date" value="{{ now()->format('Y-m-d') }}" required>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 close-modal">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Confirm</button>
            </div>
        </form>
    </div>
</div>
@endcan

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('approveModal');
        const approveButtons = document.querySelectorAll('.approve-commission');
        const closeButtons = document.querySelectorAll('.close-modal');
        const form = document.getElementById('approveForm');

        approveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const commissionId = this.getAttribute('data-id');
                form.action = `/commissions/${commissionId}/approve`;
                modal.classList.remove('hidden');
            });
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endsection
@endsection
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Tableau de Bord</h1>
    <h2>
        {{ Auth::user()->name  . " " .$data['type_user'] }}
    </h2>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @if(Auth::user()->hasRole('Freelancer'))
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-address-book text-3xl text-blue-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Contacts</h2>
                        <p class="text-2xl font-bold text-blue-500">{{ $data['contacts'] }}</p>
                        <p class="text-sm text-gray-500">contacts actifs</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-calendar-check text-3xl text-green-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Rendez-vous</h2>
                        <p class="text-2xl font-bold text-green-500">{{ $data['rdvs'] }}</p>
                        <p class="text-sm text-gray-500">RDV planifiés</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-file-alt text-3xl text-yellow-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Devis</h2>
                        <p class="text-2xl font-bold text-yellow-500">{{ $data['devis'] }}</p>
                        <p class="text-sm text-gray-500">devis créés</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-money-bill-wave text-3xl text-red-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Commissions</h2>
                        <p class="text-2xl font-bold text-red-500">{{ $data['commissions'] }}</p>
                        <p class="text-sm text-gray-500">demandes</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-cogs text-3xl text-purple-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Abonnement</h2>
                        <p class="text-2xl font-bold text-purple-500">{{ $data['abonnement'] ? $data['abonnement']->plan : 'Aucun' }}</p>
                        <p class="text-sm text-gray-500">plan actuel</p>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->hasRole('Account Manager'))
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-calendar-check text-3xl text-green-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Rendez-vous</h2>
                        <p class="text-2xl font-bold text-green-500">{{ $data['rdvs'] }}</p>
                        <p class="text-sm text-gray-500">RDV attribués</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-file-alt text-3xl text-yellow-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Devis</h2>
                        <p class="text-2xl font-bold text-yellow-500">{{ $data['devis'] }}</p>
                        <p class="text-sm text-gray-500">devis gérés</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-users text-3xl text-teal-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Utilisateurs</h2>
                        <p class="text-2xl font-bold text-teal-500">{{ $data['users'] }}</p>
                        <p class="text-sm text-gray-500">utilisateurs</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-address-book text-3xl text-blue-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Contacts</h2>
                        <p class="text-2xl font-bold text-blue-500">{{ $data['contacts'] }}</p>
                        <p class="text-sm text-gray-500">contacts</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-calendar-check text-3xl text-green-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Rendez-vous</h2>
                        <p class="text-2xl font-bold text-green-500">{{ $data['rdvs'] }}</p>
                        <p class="text-sm text-gray-500">RDV</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-file-alt text-3xl text-yellow-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Devis</h2>
                        <p class="text-2xl font-bold text-yellow-500">{{ $data['devis'] }}</p>
                        <p class="text-sm text-gray-500">devis</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-money-bill-wave text-3xl text-red-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Commissions</h2>
                        <p class="text-2xl font-bold text-red-500">{{ $data['commissions'] }}</p>
                        <p class="text-sm text-gray-500">demandes</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-cogs text-3xl text-purple-500"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Abonnements</h2>
                        <p class="text-2xl font-bold text-purple-500">{{ $data['abonnements'] }}</p>
                        <p class="text-sm text-gray-500">abonnements</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Charts Section -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Statistiques</h2>
        <canvas id="dashboardChart" class="w-full h-64"></canvas>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    const dashboardChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Contacts', 'RDVs', 'Devis', 'Commissions', 'Abonnements'],
            datasets: [{
                label: 'Statistiques',
                data: [
                    {{ $data['contacts'] }},
                    {{ $data['rdvs'] }},
                    {{ $data['devis'] }},
                    {{ $data['commissions'] }},
                    {{ $data['abonnements'] ?? 0 }}
                ],
                backgroundColor: [
                    '#4F46E5',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444',
                    '#3B82F6'
                ],
                borderColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444',
                    '#4F46E5'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

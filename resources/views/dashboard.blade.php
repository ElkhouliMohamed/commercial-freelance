@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord</h1>
                <p class="text-gray-600 mt-2">Bienvenue, {{ Auth::user()->name }}! Voici votre aperçu.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                    {{ ucfirst(Auth::user()->roles->first()->name) }}
                </span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @if (Auth::user()->hasRole('Freelancer'))
                <!-- Freelancer Cards -->
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-address-book text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Contacts</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['contacts'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">contacts actifs</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Rendez-vous</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['rdvs'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">RDV planifiés</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-file-alt text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Devis</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['devis'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">devis créés</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-red-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-money-bill-wave text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Commissions</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['commissions'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $data['pending_commissions'] ?? 0 }} en attente / {{ $data['approved_commissions'] ?? 0 }} validées
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-cogs text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Abonnement</h2>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $data['abonnement'] ? $data['abonnement']->plan : 'Aucun' }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">plan actuel</p>
                        </div>
                    </div>
                </div>

            @elseif (Auth::user()->hasRole('Account Manager'))
                <!-- Account Manager Cards -->
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Rendez-vous</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['rdvs'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">RDV attribués</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-file-alt text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Devis</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['devis'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">devis gérés</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-red-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-money-bill-wave text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Commissions à approuver</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['pending_commissions'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">en attente d’approbation</p>
                        </div>
                    </div>
                </div>

            @else
                <!-- Admin/Super Admin Cards -->
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-teal-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-teal-100 text-teal-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Utilisateurs</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['users'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">utilisateurs</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-address-book text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Contacts</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['contacts'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">contacts</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Rendez-vous</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['rdvs'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">RDV</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-file-alt text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Devis</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['devis'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">devis</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-red-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-money-bill-wave text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Commissions</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['commissions'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $data['pending_commissions'] ?? 0 }} en attente / {{ $data['approved_commissions'] ?? 0 }} validées
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-cogs text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">Abonnements</h2>
                            <p class="text-2xl font-bold text-gray-800">{{ $data['abonnements'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">abonnements</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Charts Section -->
        <div class="bg-white p-6 rounded-xl shadow-md mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Statistiques</h2>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm rounded-md bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition-colors" data-period="monthly">
                        Mensuel
                    </button>
                    <button class="px-3 py-1 text-sm rounded-md bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors" data-period="yearly">
                        Annuel
                    </button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="dashboardChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Recent Activities Section (Optional) -->
        @if (Auth::user()->hasRole('Freelancer') || Auth::user()->hasRole('Account Manager'))
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Activités Récentes</h2>
                <ul class="space-y-4">
                    <!-- Add dynamic recent activities here if needed -->
                    <li class="text-gray-600">Exemple: Nouveau devis créé le {{ now()->format('d/m/Y') }}</li>
                </ul>
            </div>
        @endif
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('dashboardChart').getContext('2d');
            const dashboardChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Contacts', 'RDVs', 'Devis', 'Commissions', 'Abonnements'],
                    datasets: [{
                        label: 'Statistiques',
                        data: [
                            {{ $data['contacts'] ?? 0 }},
                            {{ $data['rdvs'] ?? 0 }},
                            {{ $data['devis'] ?? 0 }},
                            {{ $data['commissions'] ?? 0 }},
                            {{ $data['abonnements'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(79, 70, 229, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(59, 130, 246, 0.7)'
                        ],
                        borderColor: [
                            '#4F46E5',
                            '#10B981',
                            '#F59E0B',
                            '#EF4444',
                            '#3B82F6'
                        ],
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1F2937',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 6,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#6B7280' } },
                        y: { grid: { color: '#F3F4F6' }, ticks: { color: '#6B7280' }, beginAtZero: true }
                    }
                }
            });

            // Optional: Dynamic chart updates based on period (monthly/yearly)
            document.querySelectorAll('[data-period]').forEach(button => {
                button.addEventListener('click', function () {
                    const period = this.dataset.period;
                    // Add AJAX call or logic here to update chart data based on period
                    console.log(`Switching to ${period} view`);
                });
            });
        });
    </script>
@endsection
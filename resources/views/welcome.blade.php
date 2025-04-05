<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdlabFactory Freelance - Gérer vos projets facilement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Ensure footer stays at bottom and custom styles */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f7fafc, #e2e8f0);
            color: #2d3748;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        /* Hero Section Animation */
        .animate-fade-in {
            animation: fadeIn 1.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card Hover Effect */
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Custom Button Style */
        .custom-btn {
            @apply bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-md;
        }
    </style>
</head>

<body class="overflow-x-hidden">

    <!-- Sidebar -->
    <div class="flex">
        <aside class="w-64 text-white h-screen fixed" style="background: #18181B;">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-6">AdlabFactory Freelance</h1>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('login') }}" class="block px-4 py-2 rounded hover:bg-blue-600 flex items-center text-gray-200 hover:text-white">
                            <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="block px-4 py-2 rounded hover:bg-blue-600 flex items-center text-gray-200 hover:text-white">
                            <i class="fas fa-user-plus mr-2"></i>Inscription
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="ml-64 w-screen">
            <main>
                <!-- Hero Section -->
                <section class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-24 animate-fade-in">
                    <div class="container mx-auto px-4 text-center">
                        <h2 class="text-4xl font-extrabold mb-6 leading-tight">Bienvenue sur AdlabFactory Freelance</h2>
                        <p class="text-lg mb-8 max-w-2xl mx-auto">Gérez vos abonnements, devis, rendez-vous et commissions en toute simplicité. Boostez votre activité freelance avec nos outils puissants et intuitifs.</p>
                        <a href="{{ route('login') }}" class="custom-btn">Commencer Maintenant</a>
                    </div>
                </section>

                <!-- Features Section -->
                <section class="container mx-auto px-4 py-16">
                    <div class="text-center mb-12">
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">Nos Fonctionnalités Clés</h3>
                        <p class="text-gray-600 max-w-2xl mx-auto">Tout ce dont vous avez besoin pour gérer vos projets, clients et revenus efficacement.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="feature-card bg-white shadow-lg rounded-lg p-6 text-center border border-gray-100">
                            <div class="text-blue-500 mb-4">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Gestion des Clients</h4>
                            <p class="text-gray-600">Suivez vos clients, prospects et interactions grâce à notre CRM intégré.</p>
                        </div>
                        <div class="feature-card bg-white shadow-lg rounded-lg p-6 text-center border border-gray-100">
                            <div class="text-blue-500 mb-4">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Planification des Rendez-vous</h4>
                            <p class="text-gray-600">Organisez vos réunions et recevez des rappels automatiques.</p>
                        </div>
                        <div class="feature-card bg-white shadow-lg rounded-lg p-6 text-center border border-gray-100">
                            <div class="text-blue-500 mb-4">
                                <i class="fas fa-file-invoice fa-2x"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Devis & Factures</h4>
                            <p class="text-gray-600">Créez des devis professionnels et gérez vos factures facilement.</p>
                        </div>
                    </div>
                </section>

                <!-- Testimonials Section -->
                <section class="bg-gray-100 py-16">
                    <div class="container mx-auto px-4 text-center">
                        <h3 class="text-3xl font-bold text-gray-800 mb-8">Ce que disent nos utilisateurs</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <p class="text-gray-600 italic mb-4">"AdlabFactory m’a permis de gérer mes projets et mes commissions de manière fluide. Un outil indispensable !"</p>
                                <p class="font-semibold text-gray-800">- Sarah M., Freelance</p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <p class="text-gray-600 italic mb-4">"L’interface est intuitive et les paiements sont sécurisés. Je recommande vivement !"</p>
                                <p class="font-semibold text-gray-800">- Karim L., Client</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Call to Action -->
                <section class="bg-gradient-to-r from-green-500 to-teal-600 text-white py-16 text-center">
                    <div class="container mx-auto px-4">
                        <h3 class="text-3xl font-bold mb-4">Prêt à booster votre activité ?</h3>
                        <p class="mb-6 max-w-xl mx-auto">Inscrivez-vous aujourd’hui et découvrez toutes les fonctionnalités pour réussir en tant que freelance.</p>
                        <a href="{{ route('register') }}" class="custom-btn">Créer un Compte Gratuit</a>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-8">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h4 class="font-bold mb-4">AdlabFactory</h4>
                            <p class="text-gray-400">Votre partenaire pour une gestion freelance efficace.</p>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4">Liens Utiles</h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white">À propos</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Services</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4">Support</h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white flex items-center"><i class="fas fa-envelope mr-2"></i>Support Email</a></li>
                                <li><a href="https://wa.me/1234567890" target="_blank" class="text-gray-400 hover:text-white flex items-center"><i class="fab fa-whatsapp mr-2"></i>WhatsApp</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4">Restez Connecté</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 text-center text-gray-400">
                        <p>© 2025 AdlabFactory Freelance. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Simple animation for scroll
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-fade-in');
            elements.forEach(element => {
                element.classList.add('opacity-0');
                setTimeout(() => element.classList.remove('opacity-0'), 100);
            });
        });
    </script>

</body>

</html>
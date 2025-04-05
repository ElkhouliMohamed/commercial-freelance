<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdlabFactory Freelance - Gérer vos projets facilement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Use Font Awesome CDN stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTJhFfiMnSyHmmM8BYrtTFCKQgQJ7yKSmnb9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Enhanced custom styles */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f7fafc, #e2e8f0);
            color: #2d3748;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        main {
            flex: 1;
        }

        /* Hero Section Animation */
        .animate-fade-in {
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Slide-in animation for features */
        .animate-slide-up {
            animation: slideUp 1s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card Hover and Focus Effects */
        .feature-card {
            transition: all 0.3s ease;
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            background: #f9fafb;
        }

        .feature-card:focus-within {
            outline: 2px solid #4299e1;
            outline-offset: 2px;
        }

        /* Custom Button Style (Enhanced) */
        .custom-btn {
            @apply bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-md flex items-center justify-center;
        }

        .custom-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
        }

        /* Footer Links */
        .footer-link:hover {
            text-decoration: underline;
            color: #a0aec0;
        }

        /* Icon Styling */
        .icon {
            transition: color 0.3s ease;
        }

        .icon:hover {
            color: #4299e1;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .feature-card {
                padding: 1rem;
            }

            .grid-cols-1.md\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }

            .grid-cols-1.md\\:grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .hero-section {
                padding: 2rem 1rem;
            }

            .custom-btn {
                padding: 0.75rem 1.5rem;
                font-size: 0.875rem;
            }

            .icon {
                font-size: 1rem;
            }
        }

        /* Smooth Scroll Behavior */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="overflow-x-hidden">

    <!-- Sidebar -->
    <div class="flex">
        <aside class="sidebar w-64 text-white h-screen fixed" style="background: #18181B;">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-6 text-gray-100 flex items-center">
                    <i class="fa fa-briefcase mr-2"></i> AdlabFactory Freelance
                </h1>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('login') }}"
                            class="block px-4 py-2 rounded hover:bg-blue-600 flex items-center text-gray-200 hover:text-white transition duration-200">
                            <i class="fa fa-sign-in-alt mr-2 icon"></i> Connexion
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}"
                            class="block px-4 py-2 rounded hover:bg-blue-600 flex items-center text-gray-200 hover:text-white transition duration-200">
                            <i class="fa fa-user-plus mr-2 icon"></i> Inscription
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content ml-64 w-screen">
            <main>
                <!-- Hero Section -->
                <section class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-24 animate-fade-in">
                    <div class="container mx-auto px-4 text-center">
                        <h2 class="text-4xl font-extrabold mb-6 leading-tight">Bienvenue sur AdlabFactory Freelance</h2>
                        <p class="text-lg mb-8 max-w-2xl mx-auto">Gérez vos abonnements, devis, rendez-vous et
                            commissions en toute simplicité. Boostez votre activité freelance avec nos outils puissants
                            et intuitifs.</p>
                        <a href="{{ route('login') }}" class="custom-btn">
                            <i class="fa fa-rocket mr-2"></i> Commencer Maintenant
                        </a>
                    </div>
                </section>

                <!-- Features Section -->
                <section class="container mx-auto px-4 py-16">
                    <div class="text-center mb-12">
                        <h3 class="text-3xl font-bold text-gray-800 mb-4 flex items-center justify-center">
                            <i class="fa fa-tools mr-2"></i> Nos Fonctionnalités Clés
                        </h3>
                        <p class="text-gray-600 max-w-2xl mx-auto">Tout ce dont vous avez besoin pour gérer vos projets,
                            clients et revenus efficacement.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div
                            class="feature-card animate-slide-up bg-white shadow-lg rounded-lg p-6 text-center border border-gray-100">
                            <div class="text-blue-500 mb-4">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Gestion des Clients</h4>
                            <p class="text-gray-600">Suivez vos clients, prospects et interactions grâce à notre CRM
                                intégré.</p>
                        </div>
                        <div
                            class="feature-card animate-slide-up bg-white shadow-lg rounded-lg p-6 text-center border border-gray-100 delay-200">
                            <div class="text-blue-500 mb-4">
                                <i class="fa fa-calendar-alt fa-2x"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Planification des Rendez-vous</h4>
                            <p class="text-gray-600">Organisez vos réunions et recevez des rappels automatiques.</p>
                        </div>
                        <div
                            class="feature-card animate-slide-up bg-white shadow-lg rounded-lg p-6 text-center border border-gray-100 delay-400">
                            <div class="text-blue-500 mb-4">
                                <i class="fa fa-file-invoice fa-2x"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">Devis & Factures</h4>
                            <p class="text-gray-600">Créez des devis professionnels et gérez vos factures facilement.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Testimonials Section -->
                <section class="bg-gray-100 py-16">
                    <div class="container mx-auto px-4 text-center">
                        <h3 class="text-3xl font-bold text-gray-800 mb-8 flex items-center justify-center">
                            <i class="fa fa-quote-left mr-2"></i> Ce que disent nos utilisateurs
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-white p-6 rounded-lg shadow-md animate-fade-in">
                                <p class="text-gray-600 italic mb-4">"AdlabFactory m’a permis de gérer mes projets et
                                    mes commissions de manière fluide. Un outil indispensable !"</p>
                                <p class="font-semibold text-gray-800 flex items-center">
                                    <i class="fa fa-user mr-2"></i> - Sarah M., Freelance
                                </p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md animate-fade-in delay-200">
                                <p class="text-gray-600 italic mb-4">"L’interface est intuitive et les paiements sont
                                    sécurisés. Je recommande vivement !"</p>
                                <p class="font-semibold text-gray-800 flex items-center">
                                    <i class="fa fa-user mr-2"></i> - Karim L., Client
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Call to Action -->
                <section
                    class="bg-gradient-to-r from-green-500 to-teal-600 text-white py-16 text-center animate-fade-in">
                    <div class="container mx-auto px-4">
                        <h3 class="text-3xl font-bold mb-4 flex items-center justify-center">
                            <i class="fa fa-bolt mr-2"></i> Prêt à booster votre activité ?
                        </h3>
                        <p class="mb-6 max-w-xl mx-auto">Inscrivez-vous aujourd’hui et découvrez toutes les
                            fonctionnalités pour réussir en tant que freelance.</p>
                        <a href="{{ route('register') }}" class="custom-btn">
                            <i class="fa fa-user-plus mr-2"></i> Créer un Compte Gratuit
                        </a>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-8">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h4 class="font-bold mb-4 flex items-center">
                                <i class="fa fa-building mr-2"></i> AdlabFactory
                            </h4>
                            <p class="text-gray-400">Votre partenaire pour une gestion freelance efficace.</p>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4 flex items-center">
                                <i class="fa fa-link mr-2"></i> Liens Utiles
                            </h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center">
                                        <i class="fa fa-info-circle mr-2"></i> À propos
                                    </a></li>
                                <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center">
                                        <i class="fa fa-cogs mr-2"></i> Services
                                    </a></li>
                                <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center">
                                        <i class="fa fa-envelope mr-2"></i> Contact
                                    </a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4 flex items-center">
                                <i class="fa fa-headset mr-2"></i> Support
                            </h4>
                            <ul class="space-y-2">
                                <li><a href="#"
                                        class="footer-link text-gray-400 hover:text-white flex items-center"><i
                                            class="fa fa-envelope mr-2"></i>Support Email</a></li>
                                <li><a href="https://wa.me/1234567890" target="_blank"
                                        class="footer-link text-gray-400 hover:text-white flex items-center"><i
                                            class="fab fa-whatsapp mr-2"></i>WhatsApp</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4 flex items-center">
                                <i class="fa fa-share-alt mr-2"></i> Restez Connecté
                            </h4>
                            <div class="flex space-x-4 justify-center md:justify-start">
                                <a href="#" class="text-gray-400 hover:text-white transition duration-200 icon">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition duration-200 icon">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition duration-200 icon">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 text-center text-gray-400">
                        <p class="flex items-center justify-center">
                            <i class="fa fa-copyright mr-2"></i> © 2025 AdlabFactory Freelance. Tous droits réservés.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Enhanced animations and interactivity
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-fade-in, .animate-slide-up');
            elements.forEach((element, index) => {
                setTimeout(() => {
                    element.classList.add('opacity-0');
                    setTimeout(() => element.classList.remove('opacity-0'), 100);
                }, index * 200); // Staggered animation
            });

            // Smooth scroll for links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>

</body>

</html>
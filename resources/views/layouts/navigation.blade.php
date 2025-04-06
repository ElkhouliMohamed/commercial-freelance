<nav id="sidebar" class="w-64 h-screen fixed bg-[#18181B] text-white p-4 flex flex-col transition-all duration-300">
    <!-- Toggle Button -->
    <button id="toggleSidebar" class="text-white mb-6 focus:outline-none">
        <i class="fas fa-bars text-2xl"></i>
    </button>

    <!-- Logo/Brand -->
    <div class="mb-10 flex items-center space-x-2">
        <i class="fas fa-briefcase text-yellow-400 text-2xl"></i>
        <span id="brandText" class="text-3xl font-bold">Freelance Hub</span>
    </div>

    <!-- Navigation Menu -->
    <ul class="space-y-4">
        <!-- Existing Links -->
        <li>
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-yellow-400 transition">
                <i class="fas fa-tachometer-alt text-xl"></i>
                <span class="ml-3 menu-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('contacts.index') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-yellow-400 transition">
                <i class="fas fa-address-book text-xl"></i>
                <span class="ml-3 menu-text">Gestion des Contacts</span>
            </a>
        </li>
        <li>
            <a href="{{ route('rdvs.index') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-yellow-400 transition">
                <i class="fas fa-calendar-alt text-xl"></i>
                <span class="ml-3 menu-text">Gestion des RDV</span>
            </a>
        </li>

        <!-- New Links -->
        @role('Super Admin')
            <li>
                <a href="{{ route('plans.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-yellow-400 transition">
                    <i class="fas fa-layer-group text-xl"></i>
                    <span class="ml-3 menu-text">Gestion des Plans</span>
                </a>
            </li>
        @endrole
        {{-- ! hna --}}
        @role('Account Manager')
            <li>
                <a href="{{ route('devis.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-yellow-400 transition">
                    <i class="fas fa-layer-group text-xl"></i>
                    <span class="ml-3 menu-text">Devis </span>
                </a>
            </li>
        @endrole

        @role('Freelancer')
            <li>
                <a href="{{ route('commissions.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-yellow-400 transition">
                    <i class="fas fa-layer-group text-xl"></i>
                    <span class="ml-3 menu-text">Commissions </span>
                </a>
            </li>
        @endrole



        @role('Super Admin')
            <li>
                <a href="{{ route('users.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-yellow-400 transition">
                    <i class="fas fa-users-cog text-xl"></i>
                    <span class="ml-3 menu-text">Gestion des Utilisateurs</span>
                </a>
            </li>
        @endrole

        @can('manage subscriptions')
            <li>
                <a href="{{ route('abonnements.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-yellow-400 transition">
                    <i class="fas fa-file-contract text-xl"></i>
                    <span class="ml-3 menu-text">Gestion des Abonnements</span>
                </a>
            </li>
        @endcan
    </ul>

    <!-- Authentication Buttons -->
    <div class="mt-auto">
        @auth
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center justify-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-red-400 transition">
                <i class="fas fa-sign-out-alt text-xl"></i>
                <span class="ml-3 menu-text">Déconnexion</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}"
                class="flex items-center justify-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-blue-400 transition">
                <i class="fas fa-sign-in-alt text-xl"></i>
                <span class="ml-3 menu-text">Connexion</span>
            </a>
            <a href="{{ route('register') }}"
                class="flex items-center justify-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-green-400 transition mt-2">
                <i class="fas fa-user-plus text-xl"></i>
                <span class="ml-3 menu-text">Inscription</span>
            </a>
        @endauth
    </div>
</nav>

<!-- JavaScript for Toggle -->
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const menuTextElements = document.querySelectorAll('.menu-text');
    const brandText = document.getElementById('brandText');

    toggleSidebar.addEventListener('click', () => {
        if (sidebar.style.width === '16rem') {
            sidebar.style.width = '4rem';
            menuTextElements.forEach(el => el.style.display = 'none');
            brandText.style.display = 'none';
        } else {
            sidebar.style.width = '16rem';
            menuTextElements.forEach(el => el.style.display = 'inline');
            brandText.style.display = 'inline';
        }
    });
</script>

<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión Distribuidora')</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .sidebar {
            height: calc(100vh - 4rem);
        }
        .active-nav {
            background-color: #3b82f6;
            color: white !important;
        }
        .page-transition {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 h-full w-full">
    
    <!-- Navbar Superior -->
    <nav class="bg-blue-900 border-b border-gray-200 px-4 py-3 shadow-sm w-full">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h1 class="text-xl font-bold text-white">Distribuidora</h1>
                <span class="text-sm text-white">Sistema de Gestión</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button id="dropdownUserButton" data-dropdown-toggle="dropdownUser" 
                            class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-blue-600">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold">U</span>
                        </div>
                        <!-- COMENTADO TEMPORALMENTE -->
                        {{-- <span>{{auth()->user()->nombre ?? 'Usuario'}}</span> --}}
                        <span class="text-white">Usuario</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown User -->
                    <div id="dropdownUser" class="hidden z-10 w-44 bg-white rounded-lg shadow border">
                        <div class="px-4 py-3 border-b">
                            <span class="block text-sm font-semibold">Administrador</span>
                            <!-- COMENTADO TEMPORALMENTE -->
                            {{-- <span class="block text-sm text-gray-500 truncate">{{auth()->user()->empleado->correo ?? 'correo@ejemplo.com'}}</span> --}}
                            <span class="block text-sm text-gray-500 truncate">usuario@ejemplo.com</span>
                        </div>
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mi perfil</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Configuración</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="sidebar w-60 bg-bluegray border-r border-gray-200 hidden lg:block">
            <div class="px-4 py-6">
                <nav class="space-y-1">
                    <a href="/inicio" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100 @if(request()->routeIs('inicio')) active-nav @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Inicio
                    </a>
                    
                    <!-- NUEVO: Ventas -->
                    <a href="/ventas" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100 @if(request()->routeIs('ventas')) active-nav @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h0" />
                        </svg>
                        Ventas
                    </a>
                    
                    <a href="/productos" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100 @if(request()->routeIs('articulos')) active-nav @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Artículos
                    </a>
                    <a href="/vendedores" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100 @if(request()->routeIs('vendedores')) active-nav @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>
                        </svg>
                        Vendedores
                    </a>
                    <a href="/comisiones" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100 @if(request()->routeIs('comisiones')) active-nav @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Comisiones
                    </a>
                    <a href="/calculo" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100 @if(request()->routeIs('calculo')) active-nav @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Cálculo
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 page-transition">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">@yield('page-title')</h2>
                <p class="text-gray-600">@yield('page-description')</p>
            </div>
            
            @yield('contenido')
        </main>
    </div>

    <!-- Mobile Menu Button -->
    <button id="mobileMenuButton" class="lg:hidden fixed bottom-4 right-4 w-12 h-12 bg-blue-900 text-white rounded-full shadow-lg flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeMobileMenu()"></div>
        <div class="absolute left-0 top-0 h-full w-64 bg-white">
            <div class="px-4 py-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">Menú</h3>
                    <button onclick="closeMobileMenu()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <nav class="space-y-1">
                    <a href="/inicio" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100">Inicio</a>
                    <!-- NUEVO: Ventas en menú móvil -->
                    <a href="/ventas" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100">Ventas</a>
                    <a href="/articulos" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100">Artículos</a>
                    <a href="/vendedores" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100">Vendedores</a>
                    <a href="/comisiones" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100">Comisiones</a>
                    <a href="/calculo" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-100">Cálculo</a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Mobile Menu Functionality
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileSidebar = document.getElementById('mobileSidebar');

        mobileMenuButton.addEventListener('click', () => {
            mobileSidebar.classList.remove('hidden');
        });

        function closeMobileMenu() {
            mobileSidebar.classList.add('hidden');
        }

        // Initialize Flowbite dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all Flowbite components
            const dropdowns = document.querySelectorAll('[data-dropdown-toggle]');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.getElementById(this.dataset.dropdownToggle);
                    target.classList.toggle('hidden');
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('[data-dropdown-toggle]')) {
                    document.querySelectorAll('[data-dropdown-toggle]').forEach(button => {
                        const target = document.getElementById(button.dataset.dropdownToggle);
                        if (!target.classList.contains('hidden')) {
                            target.classList.add('hidden');
                        }
                    });
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
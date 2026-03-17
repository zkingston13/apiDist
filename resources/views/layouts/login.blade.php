<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Distribuidora XYZ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .btn-login {
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full flex">
        <!-- Left Panel - Branding -->
       
        
        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Logo Mobile -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="flex items-center">
                        <div class="w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center">
                            <span class="text-3xl font-bold text-white">D</span>
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-800">Distribuidora XYZ</h1>
                            <p class="text-gray-500 text-sm">Sistema de Gestión</p>
                        </div>
                    </div>
                </div>
                
                <div class="login-card p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">Iniciar Sesión</h2>
                        <p class="text-gray-600 mt-2">Ingresa tus credenciales para acceder al sistema</p>
                    </div>
                    
                    <!-- Formulario de Login -->
                    <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-4">
                            <div>
                                <label for="usuario" class="block text-sm font-medium text-gray-700 mb-2">
                                    Usuario
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <input id="usuario" name="correo" type="text" required
                                           class="input-field pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Ingresa tu correo"
                                           value="{{ old('correo') }}">
                                </div>
                                @error('usuario')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contraseña
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input id="password" name="password" type="password" required
                                           class="input-field pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Ingresa tu contraseña">
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember" type="checkbox" 
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                    Recordar sesión
                                </label>
                            </div>
                            
                            <div class="text-sm">
                                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        </div>
                        
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm text-red-600">
                                    Credenciales incorrectas. Por favor, verifica tu usuario y contraseña.
                                </p>
                            </div>
                        @endif
                        
                        <div>
                            <button type="submit" 
                                    class="btn-login w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                   
                                </span>
                                Iniciar Sesión
                            </button>
                        </div>
                    </form>
                    <div class="mt-4">
                        <a href="{{ route('google.login') }}"
                        class="w-full flex items-center justify-center gap-3 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-300">
                            <i class="fa-brands fa-google"></i>
                            Iniciar sesión con Google
                        </a>
                    </div>
                    
                    <!-- Footer Links -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            ¿Problemas para iniciar sesión?
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                Contacta al administrador
                            </a>
                        </p>
                    </div>
                </div>
                
                <!-- System Status -->
                <div class="mt-6 flex items-center justify-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                        <span>Sistema en línea</span>
                    </div>
                    <span>•</span>
                    <span>Último acceso: {{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            // Agregar efecto de carga al botón
            form.addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-2">Verificando...</span>
                `;
            });

            // Efecto de focus en inputs
            const inputs = document.querySelectorAll('.input-field');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-200');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-200');
                });
            });
        });
    </script>
</body>
</html>
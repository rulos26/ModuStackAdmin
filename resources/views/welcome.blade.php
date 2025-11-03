<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Sistema Modular - Autenticación</title>
    
    <!-- TailwindCSS desde CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/auth-styles.css') }}">
    
    <!-- Vue 3 desde CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    
    <!-- Vue Router desde CDN -->
    <script src="https://unpkg.com/vue-router@4/dist/vue-router.global.js"></script>
    
    <!-- Axios desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50">
    <div id="app"></div>
    
    <!-- Aplicación de Autenticación con Debug -->
    <script src="{{ asset('js/auth-app.js') }}"></script>
</body>
</html>

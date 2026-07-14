<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Sugar Connection</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white font-sans antialiased h-screen flex flex-col justify-center items-center p-6">
    <div class="text-center max-w-md w-full bg-gray-800 p-8 rounded-2xl shadow-xl">
        <svg class="w-20 h-20 mx-auto text-pink-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
        </svg>
        <h1 class="text-3xl font-bold mb-4">You're Offline</h1>
        <p class="text-gray-400 mb-8">It seems you've lost your internet connection. Please check your network settings and try again.</p>
        <button onclick="window.location.reload()" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            Retry Connection
        </button>
    </div>
</body>
</html>

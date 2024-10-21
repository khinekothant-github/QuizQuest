<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900 flex items-center justify-center h-screen">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 max-w-md text-center">
        <h1 class="text-4xl font-bold text-red-600 dark:text-red-400">403 - Forbidden</h1>
        <p class="mt-4 text-lg text-gray-700 dark:text-gray-300">You do not have permission to access this page.</p>
        <button onclick="goBack()"
                class="mt-6 inline-block text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-700">
            Go Back
        </button>
    </div>

    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back(); // Navigate to the previous page if available
            } else {
                window.location.href = '/'; // Fallback to the home page if no history is available
            }
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=mai-2">
    <title inertia>Admin — Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <script>
        try { if (localStorage.getItem('admin-theme') !== 'light') document.documentElement.classList.add('dark'); } catch (e) { document.documentElement.classList.add('dark'); }
    </script>
    @viteReactRefresh
    @vite(['resources/css/admin.css', 'resources/js/admin/app.tsx'])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>

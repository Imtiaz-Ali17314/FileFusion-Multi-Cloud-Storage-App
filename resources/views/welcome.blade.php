<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO -->
    <title>FileFusion</title>
    <meta name="description" content="Manage Google Drive, OneDrive and all your cloud storage in one unified dashboard. Fast, secure, and seamless.">
    <meta name="theme-color" content="#0f172a">

    <!-- 👇 BROWSER TAB LOGO / FAVICON 👇 -->
    <link rel="icon" href="{{ asset('images/logo-icon.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/logo-icon.png') }}" type="image/png">
    
    <!-- For mobile devices (Apple, Android) -->
    <link rel="apple-touch-icon" href="{{ asset('images/logo-icon.png') }}">
    <link rel="icon" sizes="192x192" href="{{ asset('images/logo-icon.png') }}">

    <!-- Fonts: Inter (premium SaaS typeface) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    />
</head>
<body>
    <div id="app"></div>

    <!-- JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
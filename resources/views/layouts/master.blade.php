<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.meta')
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-[Poppins]">
    @include('includes.navbar')

    <!-- Main Content -->
    <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    @include('includes.footer')
</body>

</html>
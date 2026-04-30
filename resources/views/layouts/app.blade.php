<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rural Health Unit - Quality Healthcare Made Accessible</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background-light text-slate-900 font-display antialiased flex flex-col min-h-screen">

    @unless(View::getSection('hideHeader'))
        @include('layouts.header')
    @endunless

    <main class="flex-grow">
        @yield('content')
    </main>

    @unless(View::getSection('hideFooter'))
        @include('layouts.footer')
    @endunless

</body>
</html>
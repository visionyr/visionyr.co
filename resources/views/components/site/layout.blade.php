@props([
    'title' => 'Visionyr — Build the Brand Behind the Vision',
    'description' => 'Visionyr is an AI Brand Builder helping entrepreneurs create and grow brands with positioning, identity, launch strategy, and content plans in minutes.',
    'nav' => 'full',
    'footer' => true,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}" />
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    {{-- Marks the document as scripted before first paint, so JS-only layouts do not flash. --}}
    <script>document.documentElement.classList.add('js')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/site.js'])
</head>
<body class="min-h-screen bg-background text-foreground">

<x-site.nav :variant="$nav" />

<main>
    {{ $slot }}
</main>

@if ($footer)
    <x-site.footer />
@endif

</body>
</html>

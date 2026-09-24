@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ? $title.' — Visionyr CMS' : 'Visionyr CMS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/cms.js'])
</head>
<body class="h-full bg-background text-foreground">

<div class="relative flex min-h-full flex-col overflow-hidden">
    <div class="absolute inset-0 grid-bg opacity-60" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-[520px] bg-gradient-to-b from-steel/40 to-transparent" aria-hidden="true"></div>

    <div class="relative flex flex-1 items-center justify-center px-6 py-16">
        <div class="w-full max-w-md animate-rise">
            <div class="flex flex-col items-center">
                <a href="{{ route('webcms.login') }}" class="flex items-center gap-2">
                    <span class="grid h-7 w-7 place-items-center rounded-md bg-navy">
                        <span class="block h-2 w-2 rounded-full bg-mint"></span>
                    </span>
                    <span class="text-[17px] font-semibold tracking-tight text-navy">
                        Visionyr<span class="text-navy/40">&trade;</span>
                    </span>
                </a>
            </div>

            {{ $slot }}

            <p class="mt-6 text-center text-xs text-ink/50">
                Restricted area. Authorised staff only.
            </p>
        </div>
    </div>
</div>

</body>
</html>

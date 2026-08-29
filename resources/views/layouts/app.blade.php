<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'LinkBox' }}</title>

    {{-- Prevents flash-of-unstyled-theme before Alpine boots --}}
    <script>
        document.documentElement.setAttribute(
            'data-theme',
            localStorage.getItem('theme') ??
            (matchMedia('(prefers-color-scheme: dark)').matches ? 'comfy-dark' : 'comfy-light')
        );
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-base-200 min-h-screen">

    <livewire:header />
    <div class="mx-auto max-w-6xl px-6 py-10">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-[320px_1fr]">
            <aside class="lg:sticky lg:top-10 lg:self-start">
                <livewire:add-link-form />
            </aside>

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>

</html>

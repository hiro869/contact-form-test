<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'App' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body class="bg-beige">

    @php
        $currentRoute = Route::currentRouteName();
        // ヘッダーを隠したいページ
        $hideHeader = in_array($currentRoute, ['contact.thanks']);
    @endphp

    @unless ($hideHeader)
        <header class="app-header">
            <div class="brand">FashionablyLate</div>

            <nav class="nav">
                {{-- register ページ → login ボタン --}}
                @if ($currentRoute === 'register')
                    <a href="{{ route('login') }}">login</a>

                {{-- login ページ → register ボタン --}}
                @elseif ($currentRoute === 'login')
                    <a href="{{ route('register') }}">register</a>

                {{-- admin ページ → logout ボタン --}}
                @elseif (Str::startsWith($currentRoute, 'admin'))
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="link-button">logout</button>
                    </form>

                {{-- contact.create / contact.confirm ページ → ボタンなし --}}
                @elseif (in_array($currentRoute, ['contact.create', 'contact.confirm']))
                    {{-- 何も表示しない --}}
                @endif
            </nav>
        </header>
    @endunless

    <main class="container">
        @yield('content')
    </main>
@stack('scripts')
</body>
</html>

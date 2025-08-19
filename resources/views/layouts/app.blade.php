<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FashionablyLate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

  {{-- ヘッダー --}}
  <header class="site-header">
    <h1 class="brand"><a href="{{ url('/') }}">FashionablyLate</a></h1>

    @guest
      <a href="{{ route('login') }}" class="header-btn">login</a>
    @endguest

    @auth
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="header-btn">logout</button>
      </form>
    @endauth
  </header>

  {{-- 本文 --}}
  <main class="container-narrow">
    @yield('content')
  </main>

</body>
</html>

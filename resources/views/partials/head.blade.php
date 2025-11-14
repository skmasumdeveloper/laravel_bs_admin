<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 
<!-- Load jQuery early so inline scripts can use `$` before Vite bundles execute -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-3gJwYp6vQK6nQ6Yf8a3qB1R9k1yXg9g9Y+8a4G6kq0o=" crossorigin="anonymous"></script>

@vite(['resources/css/app.scss', 'resources/js/app.js'])
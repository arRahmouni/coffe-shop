@php
    $title = isset($title) ? 'Coffee Shops' . ' - ' .  $title : 'Coffee Shops'
@endphp

<title>
    {{$title}}
</title>

<meta charset="UTF-8">
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="shortcut icon" href="{{ asset('images/coffee-shop.png') }}">
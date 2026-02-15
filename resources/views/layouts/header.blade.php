<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title')</title>

{{-- STYLE GLOBAL --}}
<link rel="stylesheet" href="{{ asset('template/vendors/css/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/css/style.css') }}">

{{-- STYLE PAGE --}}
@yield('style-page')

</head>

<!DOCTYPE html>
<html lang="{{ config('app.locale') }}"> 
<head>
	<title>{{ env('APP_NAME') }}</title>
	<meta charset=utf-8>
	<meta name=viewport content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name=csrf-token content="{{ csrf_token() }}">
	<link rel=stylesheet media="screen, projection" type=text/css href="{{ asset('css/app.css') }}">
	<meta name=description content="{{ __('') }}">
	<meta name=keywords content="{{ __('') }}">
	<meta name=ROBOTS content="INDEX, FOLLOW">
	<meta property=og:locale content=fa_IR>
	<meta property=og:type content=website>
	<meta property=og:url content="{{ url('/') }}">
	<meta property=og:description content="{{ __('') }}">
	<meta property=og:image content="{{ asset('images/logo.png') }}">
	<link rel=apple-touch-icon sizes=144x144 href=/apple-touch-icon.png>
	<link rel=icon type=image/png sizes=32x32 href=/favicon-32x32.png>
	<link rel=icon type=image/png sizes=16x16 href=/favicon-16x16.png>
	<link rel=manifest href=/site.webmanifest>
	<link rel=mask-icon href=/safari-pinned-tab.svg color=#5bbad5>
	<meta name=msapplication-TileColor content=#da532c>
	<meta name=theme-color content=#ffffff>
</head> 
<body> 
	<div id=app> </div> 
	<script src="{{ asset('js/app.js') }}"></script> 
</body> 
</html>
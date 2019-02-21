<!DOCTYPE html>
<html lang="{{ config('app.locale') }}"> 
<head>
	<title>{{ env('APP_NAME') }}</title>
	<meta charset=utf-8>
	<meta name=viewport content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name=csrf-token content="{{ csrf_token() }}">
	<link rel=stylesheet media="screen, projection" type=text/css href="/css/app.css">
	<meta name=description content="{{ __('') }}">
	<meta name=keywords content="{{ __('') }}">
	<meta name=ROBOTS content="INDEX, FOLLOW">
	<meta property=og:locale content=fa_IR>
	<meta property=og:type content=website>
	<meta property=og:url content="/">
	<meta property=og:image content="/images/logo.png">
	<meta name=msapplication-TileColor content=#da532c>
	<meta name=theme-color content=#ffffff>
</head> 
<body> 
	<div id=app> </div> 
	<script src="/js/app.js"></script>
</body> 
</html>
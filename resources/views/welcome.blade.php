
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>PDT Project 1</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">

        <link rel="stylesheet" href="{{asset('css/vendor/normalize.min.css')}}">
        <link rel="stylesheet" href="{{asset('https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css')}}" rel='stylesheet'>
        <link rel="stylesheet" href="{{asset('css/main.css')}}">

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="/js/vendor/html5shiv.js"><\/script>')</script>
        <![endif]-->
    </head>
    <body>
        <div id="map"></div>
        <script src='{{asset('js/vendor/jquery-1.11.2.min.js')}}'></script>
        <script src='{{asset('https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js')}}'></script>
        <script src="{{asset('js/main.js')}}"></script>
    </body>
</html>

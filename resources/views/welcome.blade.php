
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>PDT Project 1</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">

        <link rel="stylesheet" href="{!! asset('https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css') !!}" rel='stylesheet'>
        <link rel="stylesheet" href="{!! asset('css/semantic.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/main.css') !!}">

        <!--[if lt IE 9]>
            <script src="{!! asset('js/vendor/html5-3.6-respond-1.4.2.min.js') !!}"></script>
        <![endif]-->
    </head>
    <body>
        
        <div class="ui grid maxheight">
            <div class="three wide column" id="sidebar">
                
                <div class="ui vertical fluid tabular menu">
                    <a class="active item">
                        Bio
                    </a>
                    <a class="item">
                        Pics
                    </a>
                    <a class="item">
                        Companies
                    </a>
                    <a class="item">
                        Links
                    </a>
                </div>
                
            </div>
            <div class="thirteen wide column maxheight">
                <div class="maxheight" id="map"></div>
            </div>
        </div>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{!! asset('js/vendor/jquery-1.11.2.min.js') !!}"><\/script>')</script>
        <script src='{!! asset('https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js') !!}'></script>
        <script src="{!! asset('js/vendor/semantic.min.js') !!}"></script>
        <script src="{!! asset('js/main.js') !!}"></script>
    </body>
</html>

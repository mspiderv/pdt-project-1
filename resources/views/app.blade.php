
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Vybavovačky | PDT Projekt | Matej Víťaz</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">

        <link rel="stylesheet" href="{!! asset('https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css') !!}" rel='stylesheet'>
        <script src="https://use.fontawesome.com/9ebae640d5.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="stylesheet" href="{!! asset('jquery-ui-1.12.1.custom/jquery-ui.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/semantic.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('css/main.css') !!}">

        <!--[if lt IE 9]>
            <script src="{!! asset('js/vendor/html5-3.6-respond-1.4.2.min.js') !!}"></script>
        <![endif]-->
    </head>
    <body>
        
        <div class="ui grid maxheight">
            <div class="three wide column" id="sidebar">
                
                <h4 class="ui attached header">Zvoľ svoju polohu</h4>
                <div class="ui attached segment">

                    <div class="ui horizontal list">
                        <div class="item" style="padding: 0!important;">
                            <img class="ui mini circular image icon-you" src="/img/you.png">
                            <div class="content">
                            <div class="ui sub header">Tvoja poloha</div>
                            je na mape zobrazená touto značkou.
                            </div>
                        </div>
                    </div>

                    <div class="fluid ui buttons">
                        <button
                            id="btn-toggle-user-location"
                            class="ui toggle button active"
                            data-hide="Skryť moju polohu"
                            data-show="Zobraziť moju polohu"
                        >Skryť moju polohu</button>
                    </div>

                    <div class="fluid ui buttons">
                        <button
                            id="btn-load-user-location"
                            class="ui positive button"
                        >Nastaviť automaticky</button>
                        <div class="or"></div>
                        <button
                            id="btn-pick-user-location"
                            class="ui button"
                            data-tooltip="Klikni sem a potom na mapu"
                            data-position="top right"
                        >Nastaviť ručne</button>
                    </div>

                    <div class="fluid ui buttons">
                        <button
                            id="btn-center-map"
                            class="ui toggle button"
                        >Vycentrovať mapu na moju polohu</button>
                    </div>

                </div>

                <div class="ui styled fluid accordion">
                    <h4 class="title active ui attached header">Nájsť najlepšiu trasu</h4>
                    <div class="content active">

                        <h5>Ako cestuješ ?</h5>
                        <div class="fluid ui buttons" id="profile-buttons">
                            <button class="ui button positive" data-profile="walking">
                                <i class="maki-pitch"></i>
                            </button>
                            <button class="ui button" data-profile="cycling">
                                <i class="fa fa-bicycle"></i>
                            </button>
                            <button class="ui button" data-profile="driving">
                                <i class="fa fa-car"></i>
                            </button>
                        </div>

                        <h5>Klikni čo potrebuješ vybaviť. Prioritu je možné upravovať zoraďovaním.</h5>

                        <div id="amenities" class="ui middle aligned selection list">
                            <div class="item" data-amenity="toilets">
                                <i class="image maki-toilet"></i>
                                <div class="content">Toalety</div>
                            </div>
                            <div class="item" data-amenity="bank">
                                <i class="image maki-credit-card"></i>
                                <div class="content">Banka</div>
                            </div>
                            <div class="item" data-amenity="pharmacy">
                                <i class="image maki-pharmacy"></i>
                                <div class="content">Lekáreň</div>
                            </div>
                            <div class="item" data-amenity="restaurant">
                                <i class="image maki-restaurant"></i>
                                <div class="content">Reštaurácia</div>
                            </div>
                            <div class="item" data-amenity="post_box">
                                <i class="image fa fa-building-o"></i>
                                <div class="content">Pošta</div>
                            </div>
                            <div class="item" data-amenity="pub">
                                <i class="image fa fa-beer"></i>
                                <div class="content">Krčma</div>
                            </div>
                            <div class="item" data-amenity="cafe">
                                <i class="image fa fa-coffee"></i>
                                <div class="content">Kaviareň</div>
                            </div>
                        </div>

                    </div>

                    <h4 class="title active ui attached header">Zobraziť všetky</h4>
                    <div class="content">
                        <div id="showables" class="ui middle aligned selection list">
                            <div class="item" data-amenity="toilets">
                                <i class="image maki-toilet"></i>
                                <div class="content">Toalety</div>
                            </div>
                            <div class="item" data-amenity="bank">
                                <i class="image maki-credit-card"></i>
                                <div class="content">Banka</div>
                            </div>
                            <div class="item" data-amenity="pharmacy">
                                <i class="image maki-pharmacy"></i>
                                <div class="content">Lekáreň</div>
                            </div>
                            <div class="item" data-amenity="restaurant">
                                <i class="image maki-restaurant"></i>
                                <div class="content">Reštaurácia</div>
                            </div>
                            <div class="item" data-amenity="post_box">
                                <i class="image fa fa-building-o"></i>
                                <div class="content">Pošta</div>
                            </div>
                            <div class="item" data-amenity="pub">
                                <i class="image fa fa-beer"></i>
                                <div class="content">Krčma</div>
                            </div>
                            <div class="item" data-amenity="cafe">
                                <i class="image fa fa-coffee"></i>
                                <div class="content">Kaviareň</div>
                            </div>
                        </div>
                    </div>

                    <h4 class="title active ui attached header">Mestské časti</h4>
                    <div class="content">

                        <div class="fluid ui buttons">
                            <button
                                id="btn-active-parts"
                                class="ui positive button"
                            >Aktivovať všetky</button>
                            <div class="or"></div>
                            <button
                                id="btn-deactive-parts"
                                class="ui negative button"
                            >Deaktivovať všetky</button>
                        </div>

                        <div id="parts" class="ui middle aligned selection list compact">
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Staré Mesto</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Ružinov</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Vrakuňa</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Podunajské Biskupice</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Nové Mesto</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Rača</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Vajnory</div>
                            </div>
                            <div class="item active">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Karlova Ves</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Dúbravka</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Lamač</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Devín</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Devínska Nová Ves</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Záhorská Bystrica</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Petržalka</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Jarovce</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Rusovce</div>
                            </div>
                            <div class="item">
                                <i class="image fa fa-globe"></i>
                                <div class="content">Čunovo</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="maxheight" id="map"></div>
        </div>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{!! asset('js/vendor/jquery-1.11.2.min.js') !!}"><\/script>')</script>
        <script src='{!! asset('https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js') !!}'></script>
        <script src="{!! asset('js/vendor/semantic.min.js') !!}"></script>
        <script src="{!! asset('js/vendor/polyline.js') !!}"></script>
        <script src="{!! asset('jquery-ui-1.12.1.custom/jquery-ui.min.js') !!}"></script>
        <script src="{!! asset('js/main.js') !!}"></script>
    </body>
</html>

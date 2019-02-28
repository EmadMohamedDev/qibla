<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>

    <style type="text/css">
        .compassbg_green
        {
            background: linear-gradient(#fff, #dbfbc8);
            border: 1px solid #a3daab;
        }
        .bg_green
        {
            background-color: #038002;
        }
        .bmargin20
        {
            margin-bottom: 20px;
        }
        .directionImg
        {
            left: 0;
            position: absolute;
            top: 0px;
        }
        .padding10
        {
            padding: 10px;
            color: #fff;
            font-weight: bold;
        }
        .padding0
        {
            padding: 0px;
        }
        .pull-right
        {
            font-size: 14px;
            font-weight: bold;
            color: maroon;
            float: right;
            margin-bottom: 0px !important;
        }
        .lblspan
        {
            font-size: 14px;
            font-weight: bold;
            color: green;
        }
        img{
              max-width: 100%;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  </head>
  <body>
    <div class="row">
      <div class="col-md-4 col-sm-3">
        <div class="compassbg_green clearfix bmargin20" style="min-height: 352px; border-radius: 5px;">
        <div class="col-md-12 col-sm-12 padding10 compass-location">
          <form>
            <input name="ctl00$PageContent$geocomplete" type="hidden" id="geocomplete" class="location-field" />
            <input name="ctl00$PageContent$lat" type="hidden" id="lat" class="location-field" />
            <input name="ctl00$PageContent$lng" type="hidden" id="lng" class="location-field" />
          </form>
        </div>
        <div class="col-md-12 col-sm-12 padding10">
          <img alt="Qibla compass" src="https://hamariweb.com/islam/images/compas.png" class="tie-appear" />
          <img alt="Qibla compass needle direction" class="directionImg tie-appear" id="needle_image" style="transform: rotate(deg); -webkit-transform: rotate(deg); -moz-transform: rotate(deg);" id="image0" src="https://hamariweb.com/islam/images/arrowDir.png" />
        </div>
        <div class="col-md-12 col-sm-12 padding10">
          <div class="col-md-12 col-sm-12 padding0">
            <span class="lblspan">Latitude: </span>
            <label id='show-lat' class='pull-right'>
            0.00000000</label>
          </div>
          <div class="col-md-12 col-sm-12 padding0">
            <span class="lblspan">Longitude: </span>
            <label id='show-long' class='pull-right'>
             0.00000000</label>
          </div>
          <div class="col-md-12 col-sm-12 padding0">
            <span class="lblspan">Direction: </span>
            <label id='show-direct' class='pull-right'>
            0.00000000
            </label>
          </div>
        </div>
      </div>
      </div>
      <div class="col-md-8 col-sm-9">
        <div class="hotelListing bmargin20">
            <div style='display: none; font-weight: bold;' id="location_display_band" class="bg_green tie-appear">
              <p style="color: #fff; padding-left: 10px;">
              </p>
            </div>
            <div id="map-canvas" style="width: 100%; height: 315px;" class="tie-appear">
            </div>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCkPbH3-wDpLOsruf4eBsae2q3xnb6153s&callback"></script>
    <script>
        var lat =  long = 0;
        var klat = 21.423063 ;
        var klong = 39.825951 ;
        var clat = klat;
        var clong = klong;
        function initialize() {
        var mapCanvas = document.getElementById('map-canvas');
        var grayStyles = [
        {
          featureType: "all",
          stylers: [
            { saturation: -90 },
            { lightness: 50 }
          ]
        },
      ];
        var mapOptions = {
            center: new google.maps.LatLng(clat , clong),
            zoom: 3,
            //styles: grayStyles,
            //mapTypeId: google.maps.MapTypeId.SATELLITE
        }
        var map = new google.maps.Map(mapCanvas, mapOptions);
        var icon = 'https://hamariweb.com/islam/images/marker-location.png';
        var qibla = 'https://hamariweb.com/islam/images/qibla.png';
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(klat , klong),
            map: map,
            icon: qibla
        });
        if(lat !== 0 && long !== 0){
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat,long),
                map: map,
                icon: icon
            });
            var lineCoordinates = [
                new google.maps.LatLng(lat,long),
                new google.maps.LatLng(klat,klong)
            ];
            // Define the symbol, using one of the predefined paths ('CIRCLE')
            // supplied by the Google Maps JavaScript API.
            var lineSymbol = {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                scale: 3,
                strokeColor: '#393'
            };

            // Create the polyline and add the symbol to it via the 'icons' property.
            line = new google.maps.Polyline({
                path: lineCoordinates,
                icons: [{
                        icon: lineSymbol,
                        offset: '100%'
                    }],
                geodesic:true,
                strokeColor:'#393',
                map: map
            });
            getLocation()
            animateCircle();
        }
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    // Use the DOM setInterval() function to change the offset of the symbol
    // at fixed intervals.
    function animateCircle() {
        clearTimeout();
        var count = 0;
        window.setInterval(function () {
            count = (count + 1) % 200;
            var icons = line.get('icons');
            icons[0].offset = (count / 2) + '%';
            line.set('icons', icons);
        }, 100);
    }

  $(function () {
           lat = $("#lat").val();
           long = $("#lng").val();
           clat= lat;
           clong = long;
           var bearing = getBearing(klat,klong,lat,long);
           rotateAnimation(bearing);
           setvalue(bearing,lat,long);
           initialize();
    });
   function setvalue(bearing,lat,lng){
       var dir = getCompassDirection(bearing)+ "[" + Math.round(bearing) + "&deg;]";
       lat = new Number(lat); lng = new Number(lng);
       $('#show-lat').html(lat.toPrecision(10));
       $('#show-long').html(lng.toPrecision(10));
        $('#show-direct').html(bearing.toPrecision(5)+ " <sup>o</sup>");
       $('#location_display_band').show();
       $("#location_display_band p").html('Qibla direction for location: '+$("#geocomplete").val());
   }
   function getBearing(klat,klong,lat,long){
        return (rad2deg(Math.atan2(Math.sin(deg2rad(klong) - deg2rad(long)) * Math.cos(deg2rad(klat)), Math.cos(deg2rad(lat)) * Math.sin(deg2rad(klat)) - Math.sin(deg2rad(lat)) * Math.cos(deg2rad(klat)) * Math.cos(deg2rad(klong) - deg2rad(long)))) + 360) % 360;
    }
    function deg2rad(angle) {
        return (angle / 180) * Math.PI; //angle * .017453292519943295; //
    }
    function rad2deg(angle) {
        return angle / Math.PI * 180; //angle * 57.29577951308232; // angle / Math.PI * 180
    }
    function rotateAnimation(degrees){
        var elem = document.getElementById('needle_image');
        if(navigator.userAgent.match("Chrome")){
                elem.style.WebkitTransform = "rotate("+degrees+"deg)";
        } else if(navigator.userAgent.match("Firefox")){
                elem.style.MozTransform = "rotate("+degrees+"deg)";
        } else if(navigator.userAgent.match("MSIE")){
                elem.style.msTransform = "rotate("+degrees+"deg)";
        } else if(navigator.userAgent.match("Opera")){
                elem.style.OTransform = "rotate("+degrees+"deg)";
        } else {
                elem.style.transform = "rotate("+degrees+"deg)";
        }
    }
    function getCompassDirection(bearing) {
                tmp = Math.round(bearing / 22.5);
                switch (tmp) {
                    case 1:
                        direction = "NNE";
                        break;
                    case 2:
                        direction = "NE";
                        break;
                    case 3:
                        direction = "ENE";
                        break;
                    case 4:
                        direction = "E";
                        break;
                    case 5:
                        direction = "ESE";
                        break;
                    case 6:
                        direction = "SE";
                        break;
                    case 7:
                        direction = "SSE";
                        break;
                    case 8:
                        direction = "S";
                        break;
                    case 9:
                        direction = "SSW";
                        break;
                    case 10:
                        direction = "SW";
                        break;
                    case 11:
                        direction = "WSW";
                        break;
                    case 12:
                        direction = "W";
                        break;
                    case 13:
                        direction = "WNW";
                        break;
                    case 14:
                        direction = "NW";
                        break;
                    case 15:
                        direction = "NNW";
                        break;
                    default:
                        direction = "N";
                }
                return direction;
            }


    function getLocation() {
        if( $("#lat").val() == '' && $("#lng").val() == ''){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
           }
        }
    }
    function showPosition(position) {
        $("#lat").val(position.coords.latitude);
        $("#lng").val(position.coords.longitude);
        lat = position.coords.latitude;
        long = position.coords.longitude;
        console.log("lat:"+lat+"-long:"+long);
        var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[1]) {
                    clat= lat = $("#lat").val();
                    clong = long = $("#lng").val();
                    $("#geocomplete").val(results[0].formatted_address);
                    var bearing = getBearing(klat,klong,lat,long);
                    rotateAnimation(bearing);
                    setvalue(bearing,lat,long);
                    initialize();

            } else {
                consol.log('No results found');
              }
            } else {
              consol.log('Geocoder failed due to: ' + status);
            }
          });

    }
    </script>
  </body>
</html>

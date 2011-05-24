<?php
if (($_GET['mapnumber'] == "") or ($_GET['mapnumber'] <= 0) or ($_GET['mapnumber'] > 23)) {
?>
<!DOCTYPE html>
  <html>
    <head>
    <title>Charlottetown Fire Insurance Maps</title>
  </head>
  <body>
    <h1>Charlottetown Fire Insurance Maps</h1>
    <p>This is a simply a test of an alternative way of viewing <a href="http://www.islandimagined.ca/islandora/solr/search/Queens/genre%3A%22fire%20insurance%20maps%22~city%3A%22Charlottetown%22~county%3A%22Queens%22/dismax">Island Imagined Fire Insurance Maps</a>,
      using <a href="http://oldmapsonline.googlecode.com/svn/trunk/openlayers/examples/zoomify.html">Zoomify and OpenLayers</a>.</p>
      
    <div style="float: left; padding-right: 40px;">
      <ul>
      <?
      for ($i = 1 ; $i <= 20 ; $i++) {
        if ($i <= 9) {
          $mapnumber = "0" . $i;
        }
        else {
          $mapnumber = $i;
        }
        print "<li><a href=\"index.php?mapnumber=$mapnumber\">Map Page $i</a></li>";
      }
      ?>
    </ul>
  </div>
  <img src="firemap-key.jpg" height="543" width="488" style="float: left">
  <div style="padding-left: 30px; float: left;">
  <p>Map Source: <a href="http://www.islandimagined.ca/islandora/solr/search/Queens/genre%3A%22fire%20insurance%20maps%22~city%3A%22Charlottetown%22~county%3A%22Queens%22/dismax">Island Imagined Fire Insurance Maps</a>.</p>
  <p>Rights Statement from Source: <i>This material is provided for research, education, and private use only. For all other uses, please contact the rights holder. All responsibilities for copyright are the responsibility of the user.</i></p>
  </div>
  </body>
  </html>
  <?  
  exit;
}
list($width, $height, $type, $attr) = getimagesize("/www/htdocs-maps/fire/fedoraimages/firemap-" . $_GET['mapnumber'] . ".jpg");

if ($_GET['lon'] and $_GET['lat'] and $_GET['zoom']) {
  $setcenter = "map.setCenter(new OpenLayers.LonLat(" . $_GET['lon'] . "," . $_GET['lat'] . ")," . $_GET['zoom'] . ");";
}
else {
  $setcenter = "map.zoomToMaxExtent();";
}
?>
<!DOCTYPE html>
<html>
    <head>
    <title>Charlottetown Fire Insurance Map</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
    <script src="http://oldmapsonline.googlecode.com/svn/trunk/openlayers/lib/OpenLayers/Layer/Zoomify.js"></script>

    <script type="text/javascript">

        var zoomify_width = <? echo $width; ?>;
        var zoomify_height = <? echo $height; ?>;
        var zoomify_url = "http://maps.ruk.ca/fire/fedoraimages/firemap-<? echo ($_GET['mapnumber']); ?>/";

        var map, zoomify;

        function init(){
	        var zoomify = new OpenLayers.Layer.Zoomify( "Zoomify", zoomify_url,	new OpenLayers.Size( zoomify_width, zoomify_height ) );

	        var options = {
	            controls: [],
	            maxExtent: new OpenLayers.Bounds(0, 0, zoomify_width, zoomify_height),
	            maxResolution: Math.pow(2, zoomify.numberOfTiers-1 ),
	            numZoomLevels: zoomify.numberOfTiers,
	            units: 'pixels',
	        };

	        map = new OpenLayers.Map("map", options);
	        map.addLayer(zoomify);

	        map.addControl(new OpenLayers.Control.PanZoomBar());
	        map.addControl(new OpenLayers.Control.MouseDefaults());
	        map.addControl(new OpenLayers.Control.KeyboardDefaults());

          map.setBaseLayer(zoomify);
          <?
          echo $setcenter; 
          ?>
        };
        
        function makeLink() {
          mapcenter = map.getCenter();
          newurl = "index.php?mapnumber=<? echo $mapnumber; ?>&lon=" + (mapcenter.lon) + "&lat=" + (mapcenter.lat) + "&zoom=" + map.getZoom();
          location.href= newurl;
        }
        
    </script>
    <style>
    
        html, body {
            margin  : 0;
            padding : 0;
            height  : 100%;
            width   : 100%;
        }

        #map {
            width    : 100%;
            position : relative;
            height   : 100%;
        }
     
    </style>    
  </head>
  <body onload="init()">
    <div id="map" class="smallmap"></div>
    <div style="position: absolute; top: 30px; right: 30px; background: #fff; border: 2px solid black; padding: 2px 5px; font-family: verdana"><a href="javascript:makeLink()">Link</a></div>
  </body>
</html>


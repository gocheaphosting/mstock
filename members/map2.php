<?$site="map";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<? include("../admin/function/show.php");?>
<div class="page_internal">
<h1><?=word_lang("Google map")?></h1>



    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script src="map_json.php"></script>
  <script type="text/javascript" src="<?=site_root?>/inc/js/jquery-ui-map-3.0-rc/markerclustererplus-2.0.6/markerclusterer.js"></script>

    <script>
      var styles = [[]];

      var markerClusterer = null;
      var map = null;
      var imageUrl = 'http://chart.apis.google.com/chart?cht=mm&chs=24x32&' +
          'chco=FFFFFF,008CFF,000000&ext=.png';

      function refreshMap() {
        if (markerClusterer) {
          markerClusterer.clearMarkers();
        }

        var markers = [];

        var markerImage = new google.maps.MarkerImage(imageUrl,
          new google.maps.Size(24, 32));

        for (var i = 0; i < data.count; ++i) {

          var latLng = new google.maps.LatLng(data.photos[i].latitude,
              data.photos[i].longitude)
          var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            title:data.photos[i].photo_title
          });
          
			marker['infowindow'] = new google.maps.InfoWindow({
            content: '<a href="'+data.photos[i].photo_url+'"><img border="0" src="'+data.photos[i].photo_file_url+'"></a><div style="padding:5px 0px 3px 0px"><a href="'+data.photos[i].photo_url+'"><b>'+data.photos[i].photo_title+'</b></a></div>'+data.photos[i].description
        	});

    		google.maps.event.addListener(marker, 'click', function() {
       		 this['infowindow'].open(map, this);
    		});
          
          markers.push(marker);         
        }

        var zoom = parseInt(document.getElementById('zoom').value, 10);
        var size = parseInt(document.getElementById('size').value, 10);
        var style = parseInt(document.getElementById('style').value, 10);
        zoom = zoom === 7 ? null : zoom;
        size = size === -1 ? null : size;
        style = style === -1 ? null: style;

        markerClusterer = new MarkerClusterer(map, markers, {
          maxZoom: 10,
          gridSize: size,
          styles: styles[style]
        });
      }

      function initialize() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 2,
          center: new google.maps.LatLng(39.91, 116.38),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var refresh = document.getElementById('refresh');
        google.maps.event.addDomListener(refresh, 'click', refreshMap);

        var clear = document.getElementById('clear');
        google.maps.event.addDomListener(clear, 'click', clearClusters);

        refreshMap();
      }

      function clearClusters(e) {
        e.preventDefault();
        e.stopPropagation();
        markerClusterer.clearMarkers();
      }

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>

      <div id="map" style="width:100%;height:500px;margin-bottom:20px"></div>

	<div class="row">
		<div class="col-md-4">
      <?=word_lang("zoomer")?>:<br>
        <select id="zoom" class="form-control">
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
        </select>
        </div>
        <div class="col-md-4">

      	<?=word_lang("Cluster size")?>:<br>
        <select id="size" class="form-control">
          <option value="-1"></option>
          <option value="1">1</option>
          <option value="40">40</option>
          <option value="50">50</option>
          <option value="70">70</option>
          <option value="80">80</option>
        </select>
     	</div>
		<div class="col-md-4"><br>
        <input type="hidden" id="style" value="-1">

       <input id="refresh" type="button" value="<?=word_lang("refresh")?>" class="btn btn-primary"/>
       <a href="#" id="clear" style="display:none">Clear</a>
       </div>
	</div>


</div>
<?include("../inc/footer.php");?>
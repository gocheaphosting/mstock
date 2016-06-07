<?$site="map";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<? include("../admin/function/show.php");?>
<div class="page_internal">
<h1><?=word_lang("Google map")?></h1>

<div id="map_canvas" class="map" style="width:1000px;height:700px"></div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src="<?=site_root?>/inc/js/jquery-ui-map-3.0-rc/markerclustererplus-2.0.6/markerclusterer.js"></script>
<script type="text/javascript" src="<?=site_root?>/inc/js/jquery-ui-map-3.0-rc/jquery.ui.map.min.js"></script>
<script type="text/javascript">
	$(function() { 


			$('#map_canvas').gmap({'zoom': 2, 'disableDefaultUI':true,'zoomControl': true,'panControl': true,'mapTypeControl': true,'scaleControl': true,'streetViewControl': true,
'overviewMapControl': true}).bind('init', function(evt, map) { 
			
				var bounds = map.getBounds();
				var southWest = bounds.getSouthWest();
				var northEast = bounds.getNorthEast();
				var lngSpan = northEast.lng() - southWest.lng();
				var latSpan = northEast.lat() - southWest.lat();
				

				
				<?
				
				$sql_mass=array();
				
				if($global_settings["allow_photo"])
				{
					$sql_mass["photo"]="select id_parent,title,server1,google_x,google_y,description from photos where google_x<>0 and google_y<>0 order by id_parent desc";
				}
				
				 if($global_settings["allow_video"])
				{
					$sql_mass["video"]="select id_parent,title,server1,google_x,google_y,description from videos where google_x<>0 and google_y<>0 order by id_parent desc";
				}
				
				if($global_settings["allow_audio"])
				{
					$sql_mass["audio"]="select id_parent,title,server1,google_x,google_y,description from audio where google_x<>0 and google_y<>0 order by id_parent desc";
				}
				
				 if($global_settings["allow_vector"])
				{
					$sql_mass["vector"]="select id_parent,title,server1,google_x,google_y,description from vector where google_x<>0 and google_y<>0 order by id_parent desc";
				}
				
				foreach ($sql_mass as $key => $value) 
				{
					$rs->open($value);
					$n=0;
					while(!$rs->eof)
					{
						$img_url="";
			
						$remote_width=0;
						$remote_height=0;
						$flag_storage=false;
					
						if($key=="photo")
						{	
							$img_url=show_preview($rs->row["id_parent"],"photo",1,1,$rs->row["server1"],$rs->row["id_parent"],false);
				
							$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and (filename1='thumb1.jpg' or filename1='thumb1.jpeg')";
							$ds->open($sql);
							if(!$ds->eof)
							{
								$remote_width=$ds->row["width"];
								$remote_height=$ds->row["height"];
								$flag_storage=true;
							}
						}
						if($key=="video")
						{
							$img_url=show_preview($rs->row["id_parent"],"video",1,1,$rs->row["server1"],$rs->row["id_parent"],false);
				
							$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and (filename1='thumb.jpg' or filename1='thumb.jpeg' or filename1='thumb0.jpg' or filename1='thumb0.jpeg')";
							$ds->open($sql);
							if(!$ds->eof)
							{
								$remote_width=$ds->row["width"];
								$remote_height=$ds->row["height"];
								$flag_storage=true;
							}
						}
						if($key=="audio")
						{
							$img_url=show_preview($rs->row["id_parent"],"audio",1,1,$rs->row["server1"],$rs->row["id_parent"],false);
				
							$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and (filename1='thumb.jpg' or filename1='thumb.jpeg')";
							$ds->open($sql);
							if(!$ds->eof)
							{
								$remote_width=$ds->row["width"];
								$remote_height=$ds->row["height"];
								$flag_storage=true;
							}
						}
						if($key=="vector")
						{
							$img_url=show_preview($rs->row["id_parent"],"vector",1,1,$rs->row["server1"],$rs->row["id_parent"],false);
				
							$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and (filename1='thumb1.jpg' or filename1='thumb1.jpeg' or filename1='thumb.jpg' or filename1='thumb.jpeg')";
							$ds->open($sql);
							if(!$ds->eof)
							{
								$remote_width=$ds->row["width"];
								$remote_height=$ds->row["height"];
								$flag_storage=true;
							}
						}
			
						if(!$flag_storage)
						{
							$size = @getimagesize ($_SERVER["DOCUMENT_ROOT"].$img_url);
							$img_width=round($size[0]/2);
							$img_height=round($size[1]/2);
						}
						else
						{
							$img_width=round($remote_width/2);
							$img_height=round($remote_height/2);
						}
						?>
							$(this).gmap('addMarker', 
							{
								'position': '<?=$rs->row["google_x"]?>,<?=$rs->row["google_y"]?>', 
								'bounds': true,
								/*
								'icon':
								{
									'url':'<?=$img_url?>',
									'size':
									{
										'width':'<?=$img_width?>', 
										'height':'<?=$img_height?>'
									}
								},
								*/
							} 
						 ).click(function() 
						{
							$('#map_canvas').gmap('openInfoWindow', {'content': '<a href="<?=item_url($rs->row["id_parent"])?>"><img border="0" src="<?=$img_url?>"></a><div style="padding:5px 0px 3px 0px"><a href="<?=item_url($rs->row["id_parent"])?>"><b><?=addslashes(str_replace("\r","",str_replace("\n","",$rs->row["title"])))?></b></a></div><?=addslashes(str_replace("\r","",str_replace("\n","",$rs->row["description"])))?>'}, this);
						})
						<?
					$n++;
					$rs->movenext();
				}
			}
		?>
				$(this).gmap('set', 'MarkerClusterer', new MarkerClusterer(map, $(this).gmap('get', 'markers')));
			});


	});
</script>



</div>
<?include("../inc/footer.php");?>
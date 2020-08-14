<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="uinitial-scale=1.0, user-scalable=no, width=640" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<style type="text/css">
body {margin:0px;}
#map_canvas {width:500px;height:500px;background:#0af}
</style>

</head>
<body>
<div id="map_canvas"></div>
<script type="text/javascript">
var celllocation = Array();
<?
$db = new SQLite3("consolidated.db");
$r = $db->query("SELECT * FROM CellLocation");
$i=0;
while ($res=$r->fetchArray()) {
	$lt = $res["Latitude"];
	$lg = $res["Longitude"];
	$ac = $res["HorizontalAccuracy"];
	$d = $res["Timestamp"];
	echo "celllocation[$i]=[$lt,$lg,$ac,$d];\n";
	$i++;
}
?>
function d2c(dd){
	d = new Date();
	d_cli = d.getTime();
	d_upd = (dd*1000) + 978303600000;
	d_diff = d_cli-d_upd;
	n = Math.round(d_diff/(3600*1000));
	if (n>255) n=255;
	if (n<0) n=0;
	return "#"+hex(n)+hex(255-n)+"00";
}
function hex(n){
	s=n.toString(16);
	if (n<16) s="0"+s;
	return s;
}
</script>
<div id="disp"></div>

<script type="text/javascript">
var latitude = 43.732406;
var longitude = 7.259216;
var initmap = false;
var client = true;
var map;
var marker = new Array();
var cityCircle = new Array();
var c = d2c(<?=$dt*1000?>);

var latlng = new google.maps.LatLng(latitude,longitude);
var myOptions = {zoom:9, center:latlng, mapTypeId:google.maps.MapTypeId.HYBRID};
map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

showdots();

function showdots() {
	for (var i=0; i < celllocation.length; i++){
		if (celllocation[i][2] > 5000) continue;
		latlng = new google.maps.LatLng(celllocation[i][0],celllocation[i][1]);		
		var populationOptions = {strokeColor:d2c(celllocation[i][3]), strokeOpacity:0.8, strokeWeight:2, fillColor: d2c(celllocation[i][3]),fillOpacity: 0.15,
		map: map,
		center: latlng,
		radius: celllocation[i][2]/2
		};
		cityCircle[i] = new google.maps.Circle(populationOptions);
		dd = new Date(celllocation[i][3]*1000+978303600000);
		marker[i] = new google.maps.Marker({position:latlng, map:map, title:dd.toString()});
		marker[i].setMap(map);
	}
}

</script>
</body>
</html>
// Instead the old map_init.js 2012-9-9
var map;
var gmarkers = [];
var count = 0;
var geocoder = null; 

function createMarker(point) {
 count++;
 // Set draggable markers
 var marker = new GMarker(point, {draggable:true, bouncy:false, dragCrossMove:true});
 marker.content = count;
 gmarkers.push(marker);

 GEvent.addListener(marker, "dragstart", function() {
  // Close infowindow when dragging a marker
  map.closeInfoWindow();
 });

 GEvent.addListener(marker, "dragend", function() {
  // Update gmarkers array to get the right points   
	point2=marker.getLatLng();
		geocoder.getLocations(
		  point2,
		  function(response)
		  {
		   if(!response || response.Status.code != 200)
		   {
				 
				   $("#spanlat_lon").html("lat:"+point2.lat()+""+" lng:"+""+point2.lng()+"")
				   $("#spanaddress").html("");
				   $("#txtaddress").val("");
				   $("#spanaddress").show();
				   $("#txtaddress").hide();
				   $("#edit").hide();
				   $("#hdn_lng").val(""+point2.lng()+"");
				   $("#hdn_lat").val(""+point2.lat()+"");
				return;
		   }
		   place = response.Placemark[0];
		   point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
	   	  $("#spanlat_lon").html(""+point2.lat()+""+","+""+point2.lng()+"");
	   	  $("#maplocation").val(""+point2.lat()+""+","+""+point2.lng()+"");
		  $("#spanaddress").html(""+place.address+"");
		  $("#txtaddress").val(""+place.address+"");
		  $("#edit").show();
		    $("#spanaddress").show();
		$("#txtaddress").hide();
				   
		  $("#hdn_lng").val(""+point2.lng()+"");
		  $("#hdn_lat").val(""+point2.lat()+"");
		  }
		 );
 });

point2=marker.getLatLng();
	geocoder.getLocations(
	  point2,
	  function(response)
	  {
	   if(!response || response.Status.code != 200)
	   {
			 
			   $("#spanlat_lon").html("lat:"+point2.lat()+""+" lng:"+""+point2.lng()+"")
			   $("#spanaddress").html("");
			   $("#txtaddress").val("");
				$("#spanaddress").show();
			   $("#txtaddress").hide();
			   $("#edit").hide();
			   $("#hdn_lng").val(""+point2.lng()+"");
			   $("#hdn_lat").val(""+point2.lat()+"");
			return;
	   }
	   place = response.Placemark[0];
	   point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
	   map.openInfoWindow(map.getCenter(),document.createTextNode(place.address));
	  $("#spanlat_lon").html(""+point2.lat()+""+","+""+point2.lng()+"");
	  $("#maplocation").val(""+point2.lat()+""+","+""+point2.lng()+"");
	  $("#spanaddress").html(""+place.address+"");
	  $("#txtaddress").val(""+place.address+"");
	   $("#spanaddress").show();
	$("#txtaddress").hide();
	  $("#edit").show();
	  $("#hdn_lng").val(""+point2.lng()+"");
	  $("#hdn_lat").val(""+point2.lat()+"");
	  }
	 );
return marker;
}   

function buildMap() {
if(GBrowserIsCompatible()) {
map=new GMap2(document.getElementById("mainmap"),{draggableCursor:'auto',draggingCursor:'move'});
map.setMapType(G_NORMAL_MAP);
map.setCenter(new GLatLng(39.9081726,116.3979471), 12);
map.addControl(new GLargeMapControl());
map.addControl(new GMenuMapTypeControl());
map.enableScrollWheelZoom();
geocoder = new GClientGeocoder(); 

GEvent.addListener(map, "click", mapClick);

}
}

function mapClick(overlay, point) {
if(point) {
for(var i = 0; i < gmarkers.length; i++) {
	
			gmarkers[i].hide();
			}
map.addOverlay(createMarker(point));
map.setCenter(point);
map.openInfoWindow(map.getCenter(),
		  document.createTextNode(place.address));
}
} 

function pressEnter() {
var address = document.getElementById("address").value;
  var geocoder = new GClientGeocoder();
   if(address!=""){
  if (geocoder) {
	geocoder.getLatLng(
	  address,
	  function(point) {
		if (!point) {
			  } else {
		 for(var i = 0; i < gmarkers.length; i++) {
	
			gmarkers[i].hide();
			}
		map.clearOverlays();
		map.addOverlay(createMarker(point));
		map.panTo(point);
		map.setCenter(point,12);
	   }
	  }
	);

  }	
  }
}   

function showtxtaddress(){
$("#txtaddress").val($("#spanaddress").html());
$("#spanaddress").hide();
$("#achange").hide();
$("#edit").hide();
$("#txtaddress").show();
}

function savelatlng(){
	
	if($("#hdn_lat").val()==""){
	parent.document.getElementById("hdnflglatlng").value=1;	
	parent.document.getElementById("spanaddress").innerHTML="";	
	parent.document.getElementById("spanlat").innerHTML=$("#hdn_lat").val();
		parent.document.getElementById("spanlng").innerHTML=$("#hdn_lng").val();
	}else{
	parent.document.getElementById("hdnflglatlng").value=0;	
	parent.document.getElementById("spanaddress").innerHTML=$("#txtaddress").val();	
	parent.document.getElementById("maplocation").innerHTML=$("#txtaddress").val();	
	parent.document.getElementById("spanlat").innerHTML=$("#hdn_lat").val();
	parent.document.getElementById("spanlng").innerHTML=$("#hdn_lng").val();

	}
}

function givemap(){
	var addressname = document.getElementById("addressname").value;
	var address_name = addressname;
	if(document.getElementById("address").value = address_name){
		pressEnter();
	}
	
}
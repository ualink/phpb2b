<!--//
var data_path = null;
if(typeof(cache_path)=="undefined" || cache_path==null){
	data_path = '';
}else{
	data_path = cache_path;
}
if(typeof(area_id1)=="undefined" || area_id1==null){
	areaid1 = 0;
}else{
	areaid1 = area_id1;
}
if(typeof(area_id2)=="undefined" || area_id2==null){
	areaid2 = 0;
}else{
	areaid2 = area_id2;
}
if(typeof(area_id3)=="undefined" || area_id3==null){
	areaid3 = 0;
}else{
	areaid3 = area_id3;
}
document.write("<script type=\"text/javascript\" src=\""+data_path+"data/cache/"+app_language+"/area.js\"></script>");
$(function() {
	var options	= {
		data	: data_area
	}
	
	var loc = new multi_select(options);
	loc.bind('#dataArea .level_1',areaid1);
	loc.bind('#dataArea .level_2',areaid2);
	loc.bind('#dataArea .level_3',areaid3);
})
//-->
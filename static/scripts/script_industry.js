<!--//
var data_path = null;
if(typeof(cache_path)=="undefined"){
	data_path = '';
}else{
	data_path = cache_path;
}
if(typeof(industry_id1)=="undefined" || industry_id1==null){
	industryid1 = 0;
}else{
	industryid1 = industry_id1;
}
if(typeof(industry_id2)=="undefined" || industry_id2==null){
	industryid2 = 0;
}else{
	industryid2 = industry_id2;
}
if(typeof(industry_id3)=="undefined" || industry_id3==null){
	industryid3 = 0;
}else{
	industryid3 = industry_id3;
}
document.write("<script type=\"text/javascript\" src=\""+data_path+"data/cache/"+app_language+"/industry.js\"></script>");
$(function() {
	var options	= {
		data	: data_industry
	}
	
	var loc = new multi_select(options);
	loc.bind('#dataIndustry .level_1',industryid1);
	loc.bind('#dataIndustry .level_2',industryid2);
	loc.bind('#dataIndustry .level_3',industryid3);
})
//-->
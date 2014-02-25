<?php
class Rss extends PbController {
	var $name = "Rss";
	
	function __construct()
	{
		$this->loadModel("setting");
	}
	
	function index()
	{
		global $G;
		include_once(CLASS_PATH. 'feedcreator.class.php');
		$rss = new UniversalFeedCreator();
		$rss->useCached();
		$rss->title = $G['setting']['site_name'];
		$rss->description = $this->setting->getValue("site_description");
		$rss->link = URL;
		$rss->syndicationURL = URL."rss.php";

		//announce
		$announce = $this->setting->dbstuff->GetArray("SELECT * FROM {$this->setting->table_prefix}announcements ORDER BY display_order ASC,id DESC LIMIT 0,5");
		if (!empty($announce)) {
			foreach ($announce as $key=>$val) {
				$item = new FeedItem();
				$item->title = $val['subject'];
				$item->link = $this->url(array("module"=>"announce", "id"=>$val['id']));
				$item->description = $val['message'];
				$item->date = $val['created'];
				$item->source = $this->url(array("module"=>"announce", "id"=>$val['id']));
				$item->author = $G['setting']['company_name'];
				$rss->addItem($item);
			}
		}
		$image = new FeedImage();
		$image->title = $G['setting']['site_banner_word'];
		$image->url = URL.STATICURL."images/logo.jpg";
		$image->link = URL;
		$image->description = $rss->description;
		$rss->image = $image;
		$rss->saveFeed("RSS1.0", DATA_PATH. "appcache/rss.xml");
	}
}
?>
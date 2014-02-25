<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require('libraries/common.inc.php');
require('libraries/securimage/securimage.php');
require('libraries/core/paths.php');
$img = new securimage();
if (!empty($_GET['do'])) {
	$do = trim($_GET['do']);
	if ($do == "play") {
		$img->audio_path = STATICURL. 'images/audio/';
		$img->outputAudioFile();
		die();
	}
}
$img->code_length = 4;
//$img->gd_font_file = 3;
$img->ttf_file = 'data/ttffonts/COOLVETI.ttf';
$img->wordlist_file = 'data/words/words.txt';
//Change some settings
$img->perturbation = 0.45;
$img->image_bg_color = new Securimage_Color("#f6f6f6");
$img->text_color = new Securimage_Color('#cc3300');
$img->use_multi_text = true;
$img->num_lines = 2;
$img->line_color = new Securimage_Color("#eaeaea");
if ($handle = @opendir(STATICURL. 'images/backgrounds/'))
{
	while ($bgfile = @readdir($handle))
	{
		if (preg_match('/\.jpg$/i', $bgfile))
		{
			$backgrounds[] = STATICURL. 'images/backgrounds/'.$bgfile;
		}
	}
	@closedir($handle);
}
srand ((float) microtime() * 10000000);
$rand_keys = array_rand ($backgrounds);
$background = $backgrounds[$rand_keys];
$img->show($background);
?>
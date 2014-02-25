<?php include 'header.share.php';?>
	<div class="content">
		<div id="installdiv">
		  <h3><?php echo $welcome_to_install;?> <?php echo $software_name;?> <?php echo PHPB2B_VERSION."(".PHPB2B_RELEASE.strtoupper($charset).")";?></h3>
		  <ul>
			<li>
			<p><br />
			<form name="language" id="language" action="install.php" method="get">
			<?php echo $select_language;?> : <select name="app_lang"><?php echo showLanguages();?></select><input type="button" name="switch_language" id="SwitchLanguage" onClick="$('#language').submit();" value="<?php echo $language_switch;?>" />&nbsp;
			<img src="images/help.gif" style="cursor:pointer;" title="<?php echo $if_want_to_change_language;?>" align="absmiddle" />
			</form></p>
			<p><br /><?php echo $the_guide_to_install;?></p>
			<p><br /><?php echo $to_install_please_attention;?></p>

	<ul>
		<li>MySQL 5.0 <?php echo $or_higher_version;?></li>
		<li>PHP 5.0.0 <?php echo $or_higher_version;?></li>
	</ul>

	<p><strong><?php echo $attention;?></strong> <?php echo $software_name;?><?php echo $mysql_only_suppport;?></p>
	</li>
		  </ul>
		</div>
		<br />
		<input type="button" class="btn" onClick="$('#install').submit();" value="<?php echo $start_to_install.$software_name;?>" title="<?php echo $click_and_next;?>" />
	</div>
	<form id="install" action="install.php" method="get">
	<input type="hidden" name="step" value="2">
	<input type="hidden" name="app_lang" value="<?php echo trim($app_lang);?>">
	</form>
  </div>
</div>
</body>
</html>
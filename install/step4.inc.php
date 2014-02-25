<?php include 'header.share.php';?>
<div class="content">
<?php if($no_writablefile !='') { ?>
<?php echo $file_perm_result;?> : <br>
<span class="error">
<?php echo $no_writablefile;?>
</span>
	<input type="button" onclick="javascript:history.go(-1);" value="<?php echo $go_back;?> : <?php echo $env_check;?>" class="btn">
	<input type="button" onclick="window.location.reload()" value="<?php echo $re_check;?>" class="btn">
	<input type="button" onclick="if(confirm('<?php echo $maybe_wrong_but_go_on;?>')) $('#install').submit();" value="<?php echo $strong_install;?>" class="btn" title="<?php echo $strong_install;?>">
<?php
}
else
{
?>
<span class="no_error">

<?php echo $writablefile;?>

</span>
<a href="javascript:history.go(-1);" class="btn"><?php echo $go_back;?> : <?php echo $steps[--$step];?></a> 
<a onclick="$('#install').submit();" class="btn"><?php echo $check_go_next;?></a>
<?php
}
?>
<form id="install" action="install.php" method="get">
<input type="hidden" name="step" value="5">
<input type="hidden" name="app_lang" value="<?php echo trim($_GET['app_lang']);?>">
 </form>
</div>
</div>
</div>
</body>
</html>
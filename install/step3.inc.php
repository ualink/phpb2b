<?php include 'header.share.php';?>
	 <table width="100%" cellpadding="0" cellspacing="0" class="table_list">
                  <tr>
                    <th><?php echo $check_project;?></th>
                    <th><?php echo $current_env;?></th>
                    <th><?php echo $suggest_env;?></th>
                    <th><?php echo $function_respond;?></th>
                  </tr>
                  <tr>
                    <td><?php echo $operation_system;?></td>
                    <td><?php echo php_uname();?></td>
                    <td>Windows_NT/Linux/Freebsd</td>
                    <td><font color="yellow">&radic;</font></td>
                  </tr>
                  <tr>
                    <td>Web <?Php echo $server;?></td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
                    <td>Apache/IIS</td>
                    <td><font color="yellow">&radic;</font></td>
                  </tr>
                  <tr>
                    <td>php <?Php echo $version;?></td>
                    <td>php <?php echo phpversion();?></td>
                    <td>php 5.0.0 <?php echo $or_higher;?></td>
                    <td><?php if(phpversion() >= '5.0.0'){ ?><font color="yellow">&radic;<?php }else{ ?><font color="red"><?php echo $cant_install;?></font><?php }?></font></td>
                  </tr>
                  <tr>
                    <td>Mysql <?php echo $extension;?></td>
                    <td><?php if(extension_loaded('mysql')){ ?>&radic;<?php }else{ ?>&times;<?php }?></td>
                    <td><?php echo $open_fine;?></td>
                    <td><?php if(extension_loaded('mysql')){ ?><font color="yellow">&radic;</font><?php }else{ ?><font color="red"><?php echo $cant_install;?></font><?php }?></td>
                  </tr>
                  <tr>
                    <td>GD <?php echo $extension;?></td>
                    <td><?php if($gd_support){ ?>&radic; (<?php echo $support;?> <?php echo $gd_support;?>)<?php }else{ ?>&times;<?php }?></td>
                    <td><?php echo $open_fine;?></td>
                    <td><?php if($gd_support){ ?><font color="yellow">&radic;</font><?php }else{ ?><font color="red"><?php echo $gd_not_support;?></font><?php }?></td>
                  </tr>
                  <tr>
                    <td>Zlib <?php echo $extension;?></td>
                    <td><?php if(extension_loaded('zlib')){ ?>&radic;<?php }else{ ?>&times;<?php }?></td>
                    <td><?php echo $open_fine;?></td>
                    <td><?php if(extension_loaded('zlib')){ ?><font color="yellow">&radic;</font><?php }else{ ?><font color="red">��֧��Gzip����</font><?php }?></td>
                  </tr>
                  <tr>
                    <td>Iconv/mb_string <?php echo $extension;?></td>
                    <td><?php if(extension_loaded('iconv') || extension_loaded('mbstring')){ ?>&radic;<?php }else{ ?>&times;<?php }?></td>
                    <td><?php echo $open_fine;?></td>
                    <td><?php if(extension_loaded('iconv') || extension_loaded('mbstring')){ ?><font color="yellow">&radic;</font><?php }else{ ?><font color="red"><?php echo $low_words_convert;?></font><?php }?></td>
                  </tr>
                  <tr>
                    <td>allow_url_fopen</td>
                    <td><?php if(ini_get('allow_url_fopen')){ ?>&radic;<?php }else{ ?>&times;<?php }?></td>
                    <td><?php echo $open_fine;?></td>
                    <td><?php if(ini_get('allow_url_fopen')){ ?><font color="yellow">&radic;</font><?php }else{ ?><font color="red"><?php echo $distance_image_not_allowed;?></font><?php }?></td>
                  </tr>
                  <tr>
                    <td>PHP<?php echo $information;?> PHPINFO</td>
                    <td colspan="3" align="center"><a href="install.php?act=phpinfo" target="_blank" style="text-decoration:underline;" title="<?php echo L("view_information", "tpl", "phpinfo");?>">PHPINFO</a></td>
                  </tr>
                </table>
<form id="install" action="install.php" method="get">
<input type="hidden" name="step" value="4">
	<input type="hidden" name="app_lang" value="<?php echo trim($_GET['app_lang']);?>">
</form>
<input type="button" onclick="javascript:history.go(-1);" value="<?php echo $go_back;?> : <?php echo $steps[--$step];?>" class="btn" /><?php if($is_right) { ?>
<input type="button" onClick="$('#install').submit();" class="btn" value="<?php echo $next_step;?> : <?php echo $perm_check;?>" />
<?php }else{ ?>
<a onclick="alert('<?php echo $env_not_allowed;?>');" class="btn"><span><?php echo $not_check_cant_install;?></span></a>
 <?php }?>
  </div>
</div>
</body>
</html>
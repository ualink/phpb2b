<?php 
include 'header.share.php';?>
<div class="content">
<form id="install" action="install.php?step=7&app_lang=<?php echo trim($_GET['app_lang']);?>" method="post">
<input type="hidden" name="step" value="7">
<input type="hidden" name="dbhost" value="<?php echo $dbhost;?>">
<input type="hidden" name="dbuser" value="<?php echo $dbuser;?>">
<input type="hidden" name="dbname" value="<?php echo $dbname;?>">
<input type="hidden" name="dbpw" value="<?php echo $dbpasswd;?>">
<input type="hidden" name="tablepre" value="<?php echo $tablepre;?>">
<input type="hidden" name="dbcharset" value="<?php echo $dbcharset;?>">
<input type="hidden" name="pconnect" value="<?php echo $pconnect;?>">
<input type="hidden" name="username" value="<?php echo $username;?>">
<input type="hidden" name="password" value="<?php echo $password;?>">
<input type="hidden" name="email" value="<?php echo $email;?>">
<input type="hidden" name="createdb" value="<?php echo $createdb;?>">
<input type="hidden" name="password_key" value="<?php echo $passwordkey;?>">
<?php if($db_error){ ?>
<span class="error"><?php echo $error_info."<br>".$db_error_and_back;?></span>
<div><input type="button" onclick="javascript:history.go(-1);" value="<?php echo $go_back;?> : <?php echo $steps[--$step];?>" class="btn" /></div>
<?php }else{ ?>
<table width="100%" cellpadding="0" cellspacing="0">
<caption><?php echo $pls_input_site_info;?></caption>
<tr>
<th><?php echo $site_name;?> : </th>
<td><input name="sitename" type="text" id="sitename" value="<?php echo $a_new_b2b_site;?>" style="width: 200px;" /><span> <?php echo $for_site_name;?></span></td>
</tr>
<tr>
<th><?php echo $site_title;?> : </th>
<td><input name="sitetitle" type="text" id="sitetitle" value="<?php echo $a_new_b2b_title;?>" style="width: 200px;" /><span> <?php echo $show_on_the_title;?></span></td>
</tr>
<tr>
<th><?php echo $site_url;?> : </th>
<td><input name="siteurl" type="text" id="siteurl" value="<?php echo $siteUrl;?>" style="width: 200px;" /><span> <?php echo $pls_enter_site_url;?></span></td>
</tr>
<tr>
<th><?php echo $demo_data;?> : </th>
<td><input type="checkbox" name="testdata" id="TestData" value="testdata" checked/>
<label for="TestData"><span class="disabletxt">&nbsp;<?php echo $import_demo_data;?></span> (<?php echo $for_new_users;?>)</label></td>
</tr>
</table>
</form>
</div>

<input type="button" id="btn_goback" onclick="javascript:history.go(-1);" value="<?php echo $go_back;?> : <?php echo $steps[--$step];?>" class="btn" />
<input type="button" onClick="$('#install').submit();$('#btn_installnow,#btn_goback').attr('disabled',true);" id="btn_installnow" class="btn" value="<?php echo $next_step;?> : <?php echo $start_install;?>" />
<?php } ?>
</div>
</div>
</body>
</html>

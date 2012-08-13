<?php
/*
Copyright (C) 2012  Eddy Subratha  (email : eddy.subratha@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>

<div class="wrap">
<?php

include_once('lib/language.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$base_url= stripslashes($_POST['slimswp_base_url']);

	if ($_REQUEST['action'] == 'save') {
		if($base_url == '') {
			$notice = SLIMSWP_REQUIRE;
		} else {
			$base_url= trim($base_url);
		
			if (get_option('slimswp_base_url')) {
				update_option('slimswp_base_url', $base_url);
			}
			else {
				delete_option('slimswp_base_url'); //handles case where option exists with a blank value - fails get_option test in this function
				add_option('slimswp_base_url', $base_url, '', 'yes');
			}
			$message = 'Data tersimpan';
		}
	}
}

?>
<h2><?php echo SLIMSWP_INTRO; ?></h2>
<?php if ( $notice ) : ?>
<div id="notice" class="error"><p><?php echo $notice ?></p></div>
<?php endif; ?>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php endif; ?>

<hr style="border: none; height: 1px; background-color: #ccc;"/>

<form method="post" action="">

<p><?php echo SLIMSWP_INTRO_DETAIL; ?> <a href="http://www.slims.web.id/" target="_blank">www.slims.web.id</a></p>
<hr style="border: none; height: 1px; background-color: #ccc;"/>

<table class="form-table">

<tr valign="top">
<td><?php echo SLIMSWP_BASE_URL; ?></td>
<td>
	<input type=text name="slimswp_base_url" value="<?php echo get_option('slimswp_base_url'); ?>" size="50" placeholder="http://" /><br/>
</td>
<td><?php echo SLIMSWP_BASE_URL_INFO; ?></td>
</tr>


</table>

<p class="submit">
<input name="save" type="submit" class="button-primary" value="<?php echo SLIMSWP_SAVE_BUTTON ?>" />
<input type="hidden" name="action" value="save" />
</form>
<br>

</div>

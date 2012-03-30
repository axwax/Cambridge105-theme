<?php

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
    
?>
<div class="wrap">
<h2><?php echo $themename; ?> General Options</h2>

<form method="post">

<table class="optiontable">

<?php 
$settings = get_option($shortname.'_options');
foreach ($options as $value) { 
	$id = $value['id'];
	$std = $value['std'];
	if ($value['type'] == "text") { ?>
  <tr valign="middle"> 
        <th scope="top" style="text-align:left;"><?php echo $value['name']; ?>:</th>
	<?php if(isset($value['desc'])){?>
	</tr>
	<tr valign="middle"> 
		<td style="width:40%;"><?php echo $value['desc']?></td>
	<?php } ?>
        <td>
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( $settings[$id] != "") { echo $settings[$id]; } else { echo $value['std']; } ?>" size="40" />
    </td>
</tr>
<tr><td colspan=2><hr /></td></tr>
<?php } elseif ($value['type'] == "select") { ?>
<tr valign="middle"> 
        <th scope="top" style="text-align:left;"><?php echo $value['name']; ?>:</th>
	<?php if(isset($value['desc'])){?>
	</tr>
	<tr valign="middle"> 
		<td style="width:40%;"><?php echo $value['desc']?></td>
	<?php } ?>
        <td>
            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                <option<?php if ( $settings[$id] == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
<tr><td colspan=2><hr /></td></tr>
<?php } elseif ($value['type'] == "multiselect") { ?>
<tr valign="middle"> 
        <th scope="top" style="text-align:left;"><?php echo $value['name']; ?>:</th>
	<?php if(isset($value['desc'])){?>
	</tr>
	<tr valign="middle"> 
		<td style="width:40%;"><?php echo $value['desc']?></td>
	<?php } ?>
        <td>		
            <select  multiple="multiple" size="3" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" style="height:50px;">
                <?php $ch_values=explode(',',$settings[$id] ); foreach ($value['options'] as $option) { ?>
                <option<?php if ( in_array($option,$ch_values)) { echo ' selected="selected"'; }?> value="<?php echo $option; ?>"><?php echo $option; ?></option>
                <?php } ?>
            </select>		
        </td>
    </tr>
<tr><td colspan=2><hr /></td></tr>

<?php } elseif ($value['type'] == "radio") { ?>
<tr valign="middle"> 
        <th scope="top" style="text-align:left;"><?php echo $value['name']; ?>:</th>
	<?php if(isset($value['desc'])){?>
	</tr>
	<tr valign="middle"> 
		<td style="width:40%;"><?php echo $value['desc']?></td>
	<?php } ?>
        <td>
		<?php foreach ($value['options'] as $option) { ?>
			<?php echo $option; ?><input name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php echo $option; ?>" <?php if ( $settings[$id] == $option) { echo 'checked'; } ?>/>|
		<?php } ?>
        </td>
    </tr>
<tr><td colspan=2><hr /></td></tr>
<?php } elseif ($value['type'] == "textarea") { ?>
<tr valign="middle"> 
        <th scope="top" style="text-align:left;"><?php echo $value['name']; ?>:</th>
	<?php if(isset($value['desc'])){?>
	</tr>
	<tr valign="middle"> 
		<td style="width:40%;"><?php echo $value['desc']?></td>
	<?php } ?>
        <td>
            <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="40" rows="5"/><?php if ( $settings[$id] != "") { echo $settings[$id]; } else { echo $value['std']; } ?></textarea>
		</td>
    </tr>
<tr><td colspan=2><hr /></td></tr>

<?php } elseif ($value['type'] == "checkbox") { ?>

    <tr valign="middle"> 
        <th scope="top" style="text-align:left;"><?php echo $value['name']; ?>:</th>
	<?php if(isset($value['desc'])){?>
	</tr>
	<tr valign="middle"> 
		<td style="width:40%;"><?php echo $value['desc']?></td>
	<?php } ?>
        <td>
		<?php 
		$ch_values=explode(',',$settings[$id]);
		foreach ($value['options'] as $option) { 
		echo $option; ?><input name="<?php echo $value['id']; ?>[]" type="<?php echo $value['type']; ?>" value="<?php echo $option; ?>" <?php if ( in_array($option,$ch_values)) { echo 'checked'; } ?>/>|
<?php 		} ?>

        </td>
    </tr>
<tr><td colspan=2><hr /></td></tr>
<?php } 
}//End of foreach loop
?>
</table>
<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<h2>Preview (updated when options are saved)</h2>
<iframe src="../?preview=true" width="100%" height="600" ></iframe>
<h4>Theme Option page for <?php echo $themename; ?>&nbsp; | &nbsp; Framework by <a href="http://clark-technet.com/" title="Jeremy Clark">Jeremy Clark</a></h4>
<?php

?>

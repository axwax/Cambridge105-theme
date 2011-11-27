<?php global $wpalchemy_media_access; ?>
<div class="my_meta_control metabox">
 
	<?php $mb->the_field('mp3url'); ?>
	<?php $wpalchemy_media_access->setGroupName('nn')->setInsertButtonLabel('Insert MP3')->setTab('type'); ?>
 
	<p>
		<?php echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value())); ?>
		<?php echo $wpalchemy_media_access->getButton(array('label' => 'Add MP3 From Library')); ?>
	</p>
 
 </div>
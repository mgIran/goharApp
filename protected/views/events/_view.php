<?php
/* @var $this EventsController */
/* @var $data Events */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
	<?php echo CHtml::encode($data->subject); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('organizer')); ?>:</b>
	<?php echo CHtml::encode($data->organizer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sex')); ?>:</b>
	<?php echo CHtml::encode($data->sex); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ages')); ?>:</b>
	<?php echo CHtml::encode($data->ages); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date_run')); ?>:</b>
	<?php echo CHtml::encode($data->start_date_run); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration')); ?>:</b>
	<?php echo CHtml::encode($data->duration); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('start_time_run')); ?>:</b>
	<?php echo CHtml::encode($data->start_time_run); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_time_run')); ?>:</b>
	<?php echo CHtml::encode($data->end_time_run); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_more_days')); ?>:</b>
	<?php echo CHtml::encode($data->max_more_days); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('more_days')); ?>:</b>
	<?php echo CHtml::encode($data->more_days); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state_id')); ?>:</b>
	<?php echo CHtml::encode($data->state_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_id')); ?>:</b>
	<?php echo CHtml::encode($data->city_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('town')); ?>:</b>
	<?php echo CHtml::encode($data->town); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('main_street')); ?>:</b>
	<?php echo CHtml::encode($data->main_street); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('by_street')); ?>:</b>
	<?php echo CHtml::encode($data->by_street); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('boulevard')); ?>:</b>
	<?php echo CHtml::encode($data->boulevard); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('afew_ways')); ?>:</b>
	<?php echo CHtml::encode($data->afew_ways); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('squary')); ?>:</b>
	<?php echo CHtml::encode($data->squary); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bridge')); ?>:</b>
	<?php echo CHtml::encode($data->bridge); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quarter')); ?>:</b>
	<?php echo CHtml::encode($data->quarter); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('area_code')); ?>:</b>
	<?php echo CHtml::encode($data->area_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postal_code')); ?>:</b>
	<?php echo CHtml::encode($data->postal_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complete_address')); ?>:</b>
	<?php echo CHtml::encode($data->complete_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complete_details')); ?>:</b>
	<?php echo CHtml::encode($data->complete_details); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reception')); ?>:</b>
	<?php echo CHtml::encode($data->reception); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('invitees')); ?>:</b>
	<?php echo CHtml::encode($data->invitees); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activator_area_code')); ?>:</b>
	<?php echo CHtml::encode($data->activator_area_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activator_postal_code')); ?>:</b>
	<?php echo CHtml::encode($data->activator_postal_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ceremony_poster')); ?>:</b>
	<?php echo CHtml::encode($data->ceremony_poster); ?>
	<br />

	*/ ?>

</div>
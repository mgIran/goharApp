<?php
/* @var $this TicketsCategoriesController */
/* @var $model TicketsCategories */

$this->breadcrumbs=array(
	'Tickets Categories'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List TicketsCategories', 'url'=>array('index')),
	array('label'=>'Create TicketsCategories', 'url'=>array('create')),
	array('label'=>'Update TicketsCategories', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TicketsCategories', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TicketsCategories', 'url'=>array('admin')),
);
?>

<h1>View TicketsCategories #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
	),
)); ?>

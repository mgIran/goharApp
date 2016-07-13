<?php
/* @var $this TicketsContentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tickets Contents',
);

$this->menu=array(
	array('label'=>'Create TicketsContent', 'url'=>array('create')),
	array('label'=>'Manage TicketsContent', 'url'=>array('admin')),
);
?>

<h1>Tickets Contents</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

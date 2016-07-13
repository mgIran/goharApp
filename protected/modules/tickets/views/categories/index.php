<?php
/* @var $this TicketsCategoriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tickets Categories',
);

$this->menu=array(
	array('label'=>'Create TicketsCategories', 'url'=>array('create')),
	array('label'=>'Manage TicketsCategories', 'url'=>array('admin')),
);
?>

<h1>Tickets Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<?php
/* @var $this CostsController */
/* @var $model Costs */


$this->menu=array(
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
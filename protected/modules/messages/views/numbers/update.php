<?
$this->menu=array(
    array('label'=>'مدیریت', 'url'=>array('admin')),
    array('label'=>'افزودن خط', 'url'=>array('create')),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
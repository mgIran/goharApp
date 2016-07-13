<?
$this->menu = array(
    array('label'=>'مدیریت دسته ها', 'url'=>array('admin')),
    array('label'=>'مدیریت شماره موبایل ها', 'url'=>array('mobiles/admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model,'categories'=>$categories)); ?>
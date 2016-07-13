<?
$this->menu = array(
    array('label'=>'مدیریت دسته ها', 'url'=>array('admin')),
    array('label'=>'مدیریت ایمیل ها', 'url'=>array('emails/admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model,'categories'=>$categories)); ?>
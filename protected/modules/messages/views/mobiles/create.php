<?php
/* @var $this FeedController */
/* @var $model Feed */

$this->menu=array(
    array('label'=>'مدیریت بانک شماره موبایل', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model,'categories'=>$categories)); ?>
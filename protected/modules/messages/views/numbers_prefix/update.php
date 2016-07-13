<?php
/* @var $this FeedController */
/* @var $model Feed */

$this->menu=array(

    array('label'=>'مدیریت پیش شماره ها', 'url'=>array('admin')),
    array('label'=>'افزودن پیش شماره جدید', 'url'=>array('create')),

);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
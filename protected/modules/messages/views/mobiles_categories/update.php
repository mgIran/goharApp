<?php
/* @var $this FeedController */
/* @var $model Feed */

$this->menu=array(

    array('label'=>'مدیریت دسته ها', 'url'=>array('admin')),
    array('label'=>'افزودن دسته جدید', 'url'=>array('create')),
    array('label'=>'مدیریت شماره موبایل ها', 'url'=>array('mobiles/admin')),

);

?>

<?php $this->renderPartial('_form', array('model'=>$model,'categories'=>$categories)); ?>
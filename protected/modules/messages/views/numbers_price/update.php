<?php
/* @var $this FeedController */
/* @var $model Feed */

$this->menu=array(

    array('label'=>'مدیریت', 'url'=>array('admin')),
    array('label'=>'افزودن', 'url'=>array('create')),

);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
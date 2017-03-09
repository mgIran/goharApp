<?php
/* @var $this UnityManageController */
/* @var $model Unity */
/* @var $poster array */

$this->breadcrumbs=array(
	'لیست همصدایی ها'=>array('admin'),
	$model->subject=>array('view','id'=>$model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'ثبت همصدایی', 'url'=>array('create')),
	array('label'=>'نمایش "'.$model->subject.'"', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'لیست همصدایی ها', 'url'=>array('admin')),
);
?>

<h1>ویرایش <?php echo $model->subject; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'poster'=>$poster)); ?>
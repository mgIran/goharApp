<?php
/* @var $this ManageController */
/* @var $model Events */
/* @var $states array */
/* @var $categories array */
/* @var $poster array */

$this->breadcrumbs=array(
	'مراسمات'=>array('admin'),
	$model->subject1=>array('view','id'=>$model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'ثبت مراسم', 'url'=>array('create')),
	array('label'=>'مشاهده این مراسم', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'لیست مراسمات', 'url'=>array('admin')),
);
?>

<h1>ویرایش <?php echo $model->subject1; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'states'=>$states, 'categories'=>$categories, 'poster'=>$poster)); ?>
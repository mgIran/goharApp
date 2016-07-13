<?php
$this->menu=array(
	array('label'=>'ایجاد بخش', 'url'=>array('create')),
	array('label'=>'مدیریت بخش ها', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
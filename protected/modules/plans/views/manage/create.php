<?php
/* @var $this PlansManageController */
/* @var $model Plans */
$this->menu=array(
    array('label'=>'مدیریت پلن ها', 'url'=>array('admin')),
);

?>
<?php $this->renderPartial('_form', array('model'=>$model,'rolesModel'=>$rolesModel)); ?>
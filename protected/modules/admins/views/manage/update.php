
<? if(($flashMessage = Yii::app()->user->getFlash('success')) !== null):?>    <div class="alert alert-success">
        <i class="fa fa-check-square-o fa-lg"></i>
        <?=$flashMessage;?>    </div>
<? endif;?><? if(($flashMessage = Yii::app()->user->getFlash('info')) !== null):?>    <div class="alert alert-info">
        <i class="fa fa-info-circle fa-lg"></i>
        <?=$flashMessage;?>    </div>
<? endif;?><? if(($flashMessage = Yii::app()->user->getFlash('danger')) !== null):?>    <div class="alert alert-danger">
        <i class="fa fa-frown-o fa-lg"></i>
        <?=$flashMessage;?>    </div>
<? endif;?>



<?php
$this->menu=array(
    array('label'=>'مدیریت', 'url'=>array('admin')),
    array('label'=>'افزودن مدیر', 'url'=>array('create')),
    array('label'=>'نقش مدیران', 'url'=>array('roles/admin')),
);

$this->renderPartial('_form', array('model'=>$model,'roles'=>$roles,'title'=>'ویرایش اطلاعات مدیر')); ?>
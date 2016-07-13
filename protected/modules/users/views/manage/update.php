
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
<?
Yii::app()->clientScript->registerCss('ajaxForm',"
    .ajax-form-overlay{
        z-index:11;
    }
    .ajax-form-area{
        background:#eee;
    }
    .ajax-form-area input,.ajax-form-area select{
        width:100% !important;
        border-radius:4px !important;
        font-size:17px !important;
    }
    .ajax-form-cancel{
        left:auto;
        bottom:34px;
        right:31px;
    }
");
?>
<?php $this->renderPartial('users.views.account._form', array('model'=>$model,'roles'=>$roles,'title'=>'ویرایش اطلاعات کاربر','chargeForm' => TRUE)); ?>
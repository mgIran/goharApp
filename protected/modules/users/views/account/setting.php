<?
Yii::app()->clientScript->registerScript('pageScripts', "
if($('#Users_avatar').attr('value') && $('#Users_avatar').val()!=='')
    $('.image-crop-form').attr('style','display: block!important');

");
?>
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
$this->renderPartial("_form",array(
    'model' => $model
));
?>
<?
if(Yii::app()->user->hasFlash('success')){
    $type = 'success';
    $message = "لینک فعال سازی به ایمیل شما ارسال شد،جهت فعال سازی حساب کاربری به ایمیل خود مراجعه نمایید";
}
elseif(Yii::app()->user->hasFlash('failed')){
    $type = 'danger';
    $message = "درهنگام ارسال لینک فعال سازی خطای رخداد،مجددا تلاش نمایید";
}

?>
<?if(isset($message)):?>
    <div style="text-align: center" class="alert alert-<?=$type?> col-md-8 col-md-offset-2" role="alert">
        <?=$message?>
    </div>
<?endif;?>
<div class="clearfix"></div>
<div class="col-md-offset-2 pull-left">
    <? echo CHtml::link("ارسال مجدد لینک فعال سازی",Yii::app()->createAbsoluteUrl("users/account/activate/".$_GET['id']."?resend=true"));?>
</div>
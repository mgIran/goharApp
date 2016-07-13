<div class="col-md-12">
    <? if (($flashMessage = Yii::app()->user->getFlash('success')) !== null): ?>    <div class="alert alert-success">
        <i class="fa fa-check-square-o fa-lg"></i>
        <?= $flashMessage; ?>    </div>
    <? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('info')) !== null): ?>    <div class="alert alert-info">
        <i class="fa fa-info-circle fa-lg"></i>
        <?= $flashMessage; ?>    </div>
    <? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('failed')) !== null): ?>    <div class="alert alert-failed">
        <i class="fa fa-frown-o fa-lg"></i>
        <?= $flashMessage; ?>    </div>
    <? endif; ?>
</div>
<?
$this->renderPartial("_latest_changes");?>
<?
if(!is_null($lastCheckout))
    $this->renderPartial("_last_checkout",array(
        'lastCheckout' => $lastCheckout
    ));?>
<div class="clearfix"></div>
<br/>
<?
$this->renderPartial("_bank_details",array(
    'model' => $bankDetails,
    'lastCheckout' => $lastCheckout,
));?>



<div class="col-md-5">
    <? $this->renderPartial("_charge_credit",array(
        'model' => $creditModel
    ));?>

    <?
    $this->renderPartial("_checkouts",array(
        'model' => $checkoutsModel
    ));?>
</div>

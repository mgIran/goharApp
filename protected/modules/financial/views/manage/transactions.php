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
<?
//echo CHtml::link("خروجی اکسل","#",array('submit'=>array('commissions','export'=>'true'),'class'=>'btn btn-info'));
?>
<h1>گزارشات اعتبار موجودی نقدی</h1>
<?php
$this->confirmMessage = 'آیا از پرداخت این پورسانت اطمینان دارید؟';

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'credits-transactions-grid',
    'summaryText'=>'',
    'dataProvider'=>$model->search(),
    //'filter'=>$model,
    'columns'=> array(
        'descriptions',
        array(
            'name' => 'gateway',
            'value' => '(!is_null($data->buy->gateway))?$data->buy->gateway:"سایت گهر"',
        ),
        array(
            'name' => 'sum_price',
            'value' => 'number_format((is_null($data->buy->sum_price))?$data->price:$data->buy->sum_price)'
        ),
        array(
            'name' => 'time',
            'value' => '(!is_null($data->buy))?Yii::app()->jdate->date(\'Y/m/d\',$data->buy->date):NULL',
        ),
        array(
            'name' => 'user_price',
            'value' => 'number_format($data->user_price)'
        ),

    ),
));


if(!is_null($lastCheckout)):?>
    <div class="row">
        <?
        $this->renderPartial("application.modules.financial.views.checkouts._last_checkout", array(
            'lastCheckout' => $lastCheckout
        ));?>
    </div>
    <div class="clearfix"></div>
    <br/>
    <div class="row">
        <?
        $this->renderPartial("application.modules.financial.views.checkouts._latest_changes", array(
            'lastCheckout' => $lastCheckout
        ));?>
    </div>
<?endif;
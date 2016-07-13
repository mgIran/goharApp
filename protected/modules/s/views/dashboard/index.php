<?php
/* @var $transactionsPaid CActiveDataProvider */
/* @var $transactionsUnPaid CActiveDataProvider */
?>
<?php if(Yii::app()->user->hasFlash('success')):?>
    <div class="alert alert-success fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php elseif(Yii::app()->user->hasFlash('failed')):?>
    <div class="alert alert-danger fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('failed');?>
    </div>
<?php endif;?>
<p>
    <?= Yii::app()->user->name; ?>
	خوش آمدید
</p>
<div class="panel panel-default col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="panel-heading">
        آمار بازدیدکنندگان
    </div>
    <div class="panel-body">
        <p>
            افراد آنلاین: <?php echo Yii::app()->userCounter->getOnline(); ?><br />
            بازدید امروز: <?php echo Yii::app()->userCounter->getToday(); ?><br />
            بازدید دیروز: <?php echo Yii::app()->userCounter->getYesterday(); ?><br />
            تعداد کل بازدید ها: <?php echo Yii::app()->userCounter->getTotal(); ?><br />
            بیشترین بازدید: <?php echo Yii::app()->userCounter->getMaximal(); ?><br />
        </p>
    </div>
</div>
<div class="panel panel-default col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="panel-heading">
        گزارش ثبت نام در کلاس ها
    </div>
    <div class="panel-body">
        <p>
            ثبت نام جدید: <?php echo $transactionsPaid->totalItemCount ?><br />
        </p>
        <p>
            <a class="btn btn-info" href="<?= Yii::app()->createUrl('/courses/register/admin') ?>">مشاهده جزییات</a>
        </p>
    </div>
</div>

<div class="clearfix panel panel-success col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="panel-heading">
        تراکنش های انجام شده
    </div>
    <div class="panel-body">
        <h5>
            مجموع کل پرداختی ها: <?= Controller::parseNumbers(number_format($totalTransactionsPaidAmount)); ?> تومان
        </h5>

        <p>
            <?php
            $this->widget('zii.widgets.grid.CGridView',array(
                'id' => 'paid-grid-view',
                'dataProvider' => $transactionsPaid,
                'columns'=>array(
                    array(
                        'header'=>'کاربر',
                        'value'=>'$data->user->userDetails->name?$data->user->userDetails->name." ".$data->user->userDetails->family:$data->user->email',
                    ),
                    array(
                        'header'=>'مبلغ تراکنش',
                        'value'=>'Controller::parseNumbers(number_format($data->amount))." تومان"',
                    ),
                    array(
                        'header'=>'تاریخ',
                        'value'=>'JalaliDate::date("Y/m/d ساعت H:i:s",$data->date)',
                    ),
                    array(
                        'header'=>'کد رهگیری',
                        'value'=>'$data->token',
                    ),
                    array(
                        'header'=>'توضیحات تراکنش',
                        'value'=>'$data->description',
                    )
                )
            ));
            ?>
        </p>
    </div>
</div>


<div class="panel panel-warning col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="panel-heading">
        تراکنش های انجام نشده
    </div>
    <div class="panel-body">
        <p>
            <?php
            $this->widget('zii.widgets.grid.CGridView',array(
                'id' => 'unpaid-grid-view',
                'dataProvider' => $transactionsUnPaid,
                'columns'=>array(
                    array(
                        'header'=>'کاربر',
                        'value'=>'$data->user->userDetails->name?$data->user->userDetails->name." ".$data->user->userDetails->family:$data->user->email',
                    ),
                    array(
                        'header'=>'مبلغ تراکنش',
                        'value'=>'number_format($data->amount)." تومان"',
                    ),
                    array(
                        'header'=>'تاریخ',
                        'value'=>'JalaliDate::date("Y/m/d ساعت H:i:s",$data->date)',
                    ),
                    array(
                        'header'=>'توضیحات تراکنش',
                        'value'=>'$data->description',
                    )
                )
            ));
            ?>
        </p>
    </div>
</div>

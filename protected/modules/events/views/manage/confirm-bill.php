<?php
/* @var $this ManageController */
/* @var $model Events */

$this->breadcrumbs=array(
	'مراسمات'=>array('admin'),
	$model->subject1,
);

$this->menu=array(
	array('label'=>'ثبت مراسم', 'url'=>array('create')),
	array('label'=>'ویرایش این مراسم', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف این مراسم', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف این مراسم مطمئن هستید؟')),
	array('label'=>'لیست مراسمات', 'url'=>array('admin')),
);
$billInfo=$model->calculatePrice($model->user->activePlan->plansBuys->plan->extension_discount);
?>

<?php $this->renderPartial("//layouts/_flashMessage");?>

<h1><?php echo $model->subject1; ?></h1>

<h3>پیش فاکتور</h3>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
            'name'=>'default_show_price',
            'value'=>number_format($billInfo["defaultPrice"])." تومان"
        ),
		array(
            'name'=>'more_than_default_show_price',
            'value'=>number_format($billInfo["showMoreThanDefaultPrice"])." تومان"
        ),
		array(
            'name'=>'هزینه ثبت مراسم',
            'value'=>number_format($billInfo["eventPrice"])." تومان"
        ),
        array(
            'name'=>$billInfo["planOff"].'% تخفیف پلنی',
            'value'=>number_format($billInfo["planOffPrice"])." تومان"
        ),
        array(
            'name'=>'هزینه ثبت مراسم با تخفیف',
            'value'=>number_format($billInfo["eventPriceWithOff"])." تومان"
        ),
        array(
            'name'=>$billInfo['tax'].'% مالیات',
            'value'=>number_format($billInfo["taxPrice"])." تومان"
        ),
        array(
            'name'=>'صورتحاسب قابل پرداخت',
            'value'=>number_format($billInfo["price"])." تومان"
        ),
	),
));?>
<hr>
<h3>اطلاعات مراسم</h3>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'subject1',
		'subject2',
		'conductor1',
		'conductor2',
		array(
			'name'=>'start_date_run',
			'value'=>JalaliDate::date('d F Y', $model->start_date_run)
		),
		array(
			'name'=>'long_days_run',
			'value'=>$model->long_days_run.' شبانه روز'
		),
		array(
			'name'=>'start_time_run',
			'value'=>date('H:i', $model->start_time_run)
		),
		array(
			'name'=>'end_time_run',
			'value'=>date('H:i', $model->end_time_run)
		),
		'more_days',
		array(
			'name'=>'showStartTime',
			'value'=>JalaliDate::date('d F Y - H:i', $model->showStartTime)
		),
		array(
			'name'=>'showEndTime',
			'value'=>JalaliDate::date('d F Y - H:i', $model->showEndTime)
		),
		array(
			'name'=>'creator_mobile',
			'value'=>($model->creator_type != "admin")?Users::model()->findByPk($model->creator_id)->mobile:"-",
		),
	),
));?>
<?php echo CHtml::beginForm();?>
    <div class="row">
        <?php echo CHtml::submitButton('تایید نهایی', array('class'=>'btn btn-success pull-left', 'name'=>'confirm'));?>
    </div>
<?php echo CHtml::endForm();?>

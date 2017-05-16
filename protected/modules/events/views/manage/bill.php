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

$eventSubmitPrice = (float)$model->default_show_price + (float)$model->more_than_default_show_price;
$eventPriceWithOff = $eventSubmitPrice - (float)($model->plan_off * $eventSubmitPrice / 100);
?>

<?php $this->renderPartial("//layouts/_flashMessage");?>

<h1><?php echo $model->subject1; ?></h1>

<h3>پیش فاکتور</h3>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
            'name'=>'default_show_price',
            'value'=>number_format($model->default_show_price)." تومان"
        ),
		array(
            'name'=>'more_than_default_show_price',
            'value'=>number_format($model->more_than_default_show_price)." تومان"
        ),
		array(
            'name'=>'هزینه ثبت مراسم',
            'value'=>number_format($eventSubmitPrice)." تومان"
        ),
        array(
            'name'=>$model->plan_off.'% تخفیف پلنی',
            'value'=>number_format($model->plan_off*$eventSubmitPrice/100)." تومان"
        ),
        array(
            'name'=>'هزینه ثبت مراسم با تخفیف',
            'value'=>number_format($eventPriceWithOff)." تومان"
        ),
        array(
            'name'=>$model->tax.'% مالیات',
            'value'=>number_format($model->tax*$eventPriceWithOff/100)." تومان"
        ),
        array(
            'name'=>'صورتحاسب قابل پرداخت',
            'value'=>number_format($model->getPrice())." تومان"
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
			'name'=>'show_start_time',
			'value'=>JalaliDate::date('d F Y - H:i', $model->show_start_time)
		),
		array(
			'name'=>'show_end_time',
			'value'=>JalaliDate::date('d F Y - H:i', $model->show_end_time)
		),
		array(
			'name'=>'creator_mobile',
			'value'=>($model->creator_type != "admin")?Users::model()->findByPk($model->creator_id)->mobile:"-",
		),
		array(
			'name'=>'confirm_date',
			'value'=>JalaliDate::date('d F Y - H:i', $model->confirm_date)
		),
		array(
			'name'=>'create_date',
			'value'=>JalaliDate::date('d F Y - H:i', $model->create_date)
		),
        array(
            'name'=>'paymentStatus',
            'value'=>($model->creator_type == 'admin')?'تایید شده توسط مدیر':AppTransactions::$statusLabels[AppTransactions::model()->find('model_name = :model_name and model_id = :model_id', array(':model_name'=>'Events', ':model_id'=>$model->id))->status],
        ),
        array(
            'name'=>'bankName',
            'value'=>($model->creator_type == 'admin')?'-':AppTransactions::model()->find('model_name = :model_name and model_id = :model_id', array(':model_name'=>'Events', ':model_id'=>$model->id))->bank_name,
        ),
        array(
            'name'=>'bankRefID',
            'value'=>($model->creator_type == 'admin')?'-':AppTransactions::model()->find('model_name = :model_name and model_id = :model_id', array(':model_name'=>'Events', ':model_id'=>$model->id))->ref_id,
        ),
		array(
            'name'=>'user_mobile',
            'value'=>($model->user_mobile)?$model->user_mobile:'-',
        ),
	),
));?>
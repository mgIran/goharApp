<?php
/* @var $this PlacesController */
/* @var $model Places */

$this->breadcrumbs=array(
	'UsersPlaces'=>array('index'),
	'Manage',
);

$this->menu=array(
    array('label'=>'افزودن استان', 'url'=>array('createTown')),
    array('label'=>'افزودن شهر', 'url'=>array('createCity')),
    array('label'=>'مدیریت استان ها', 'url'=>array('adminTowns')),
    array('label'=>'مدیریت شهر ها', 'url'=>array('adminCities')),
);
?>

<h1>مدیریت مکان ها</h1>

<a href="<?= Yii::app()->createAbsoluteUrl('users/places/adminTowns')?>">مدیریت استان ها</a>
<br>
<a href="<?= Yii::app()->createAbsoluteUrl('users/places/adminCities')?>">مدیریت شهر ها</a>

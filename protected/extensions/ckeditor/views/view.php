<?php
if(!$multiLanguage)
{
    echo CHtml::activeTextArea($model, $attribute, $htmlOptions);
    Yii::app()->clientScript->registerScript("CKEditor-{$id}","
        CKEDITOR.replace( '".get_class($model).'_'.$attribute."', {
            customConfig: '".$config."'
        });
    ");
}
else
{
    echo EMHelper::megaOgogo($model, $attribute, $htmlOptions,'textarea');
    $scripts = "CKEDITOR.replace( '".get_class($model).'_'.$attribute."', {customConfig: '".$config."'});\r\n";
    $tmp = Yii::app()->params['languages'];
    unset($tmp[Yii::app()->params['default_language']]);
    $languages = array_keys($tmp);
    foreach($languages as $lang)
        $scripts .= "CKEDITOR.replace( '".get_class($model).'_'.$attribute.'_'.$lang."', {customConfig: '".$config."'});\r\n";
    Yii::app()->clientScript->registerScript("CKEditor-{$id}",$scripts);
}
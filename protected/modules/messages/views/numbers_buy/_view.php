<?
$userPlan = json_decode(Yii::app()->user->plan);
$bgColor = ($data->id==$userPlan->id)?' style="background-color: #'.CHtml::encode($data->color).'"':'';
/*<div<?=($data->id!=$userPlan->id AND intval($data->approved_price!=0))?' data-toggle="modal" data-target="#confirm-plan"':''?> class="plans-columns col-md-2 padding-reset padding-left-4 pull-right<?=($data->id==$userPlan->id)?' plan-selected':''?>" data-href="<?=Yii::app()->createAbsoluteUrl('plans/select/buy/'.CHtml::encode($data->id))?>">*/
?>
<div class="plans-columns col-md-2 padding-reset padding-left-4 pull-right<?=($data->id==$userPlan->id)?' plan-selected':''?>">
    <?if(0)://if(intval($data->approved_price!=0)):?>
        <div class="overlay"></div>
    <?endif;?>
    <div style="background-color: #<?= CHtml::encode($data->color) ?>" class="plan-name"><?= CHtml::encode($data->name) ?></div>
    <div style="background-color: #<?= CHtml::encode($data->color) ?>" class="plan-price<?= (intval($data->real_price)!=0)?' old':'' ?>"><?= (intval($data->real_price!=0))?CHtml::encode($data->real_price):'قیمت قدیم' ?></div>
    <div style="background-color: #<?= CHtml::encode($data->color) ?>" class="plan-price"><?= (intval($data->approved_price)!=0)?CHtml::encode($data->approved_price):'قیمت جدید' ?></div>
    <div class="plan-title" style="background: transparent">&nbsp;</div>
    <div class="height-2"></div>
    <div class="plans-labels">
        <?
        $serialize = Plans::getPagesRangeTitle();
        $values = json_decode($data->pages,true);
        $values = iWebActiveForm::mergeValues($serialize,$values);
        foreach($values  as $row):?>
            <div<?=$bgColor?> class="plan-subtitle"><?=(isset($row['value']) AND $row['value']!='')?$row['value']:'0'?></div>
        <?endforeach?>
    </div>
    <div class="height-10"></div>
    <div class="plan-title" style="background: transparent">&nbsp;</div>
    <div class="plans-labels">
        <?
        $serialize = Plans::model()->serializedFields['agency'];
        $values = json_decode($data->agency,true);
        $values = iWebActiveForm::mergeValues($serialize,$values);
        foreach($values  as $row):?>
            <div<?=$bgColor?> class="plan-subtitle"><?=(isset($row['value']) AND $row['value']!='')?$row['value']:'0'?></div>
        <?endforeach?>
    </div>
    <div class="height-10"></div>
    <div class="plan-title" style="background: transparent">&nbsp;</div>
    <div class="plans-labels">
        <div<?=$bgColor?> class="plan-subtitle">&nbsp;</div>
        <div<?=$bgColor?> class="plan-subtitle">&nbsp;</div>
        <div<?=$bgColor?> class="plan-subtitle"><?=($data->extension_discount)?$data->extension_discount:'0'?></div>
        <div<?=$bgColor?> class="plan-subtitle">&nbsp;</div>
        <div<?=$bgColor?> class="plan-subtitle">&nbsp;</div>
    </div>
    <div class="height-10"></div>
    <div class="plan-title" style="background: transparent">&nbsp;</div>
    <div class="plans-labels">
        <?
        $serialize = Plans::model()->serializedFields['ratio'];
        $values = json_decode($data->ratio,true);
        $values = iWebActiveForm::mergeValues($serialize,$values);
        $k = 0;
        foreach($values  as $row):
            if(in_array($k,array(0,6,7,9))):?>
                <div<?=$bgColor?> class="plan-subtitle">&nbsp;</div>
            <?endif;?>
            <div<?=$bgColor?> class="plan-subtitle"><?=(isset($row['value']) AND $row['value']!='')?$row['value']:'0'?></div>
        <?$k++;endforeach;?>
    </div>
    <div class="height-10"></div>
</div>
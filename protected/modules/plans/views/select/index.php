<div class="col-md-3 pull-right">
    <div class="plan-name" style="background: transparent;color: transparent">&nbsp;</div>
    <div class="plan-price" style="background: transparent;color: transparent">&nbsp;</div>
    <div class="head plans-labels">لیست امکانات اختصاصی هر پنل</div>
    <div class="height-2"></div>
    <div class="plan-title"><?=Plans::model()->getAttributeLabel('pages')?></div>

    <div class="plans-labels">
        <?foreach(Plans::getPagesRangeTitle() as $key=>$row):
            if($key == 'price' OR $key == 'tax')
                continue;
            ?>
            <div class="plan-subtitle"><?=$row['title']?></div>
        <?endforeach?>
    </div>
    <div class="height-10"></div>
    <div class="plan-title"><?=Plans::model()->getAttributeLabel('agency')?></div>

    <div class="plans-labels align-right">
        <?foreach(Plans::model()->serializedFields['agency'] as $row):?>
            <div class="plan-subtitle"><?=$row['title']?></div>
        <?endforeach?>
    </div>

    <div class="height-10"></div>
    <div class="plan-title"><?=Plans::model()->getAttributeLabel('extension_discount')?></div>

    <div class="plans-labels align-right">
        <div class="plan-subtitle">
            خرید خط مجازی اختصاصی (گهر پیامک)
        </div>
        <div class="plan-subtitle">
            خرید آی پی اختصاصی (گهر میل)
        </div>
        <div class="plan-subtitle">
            خرید اعتبار سرعت-زمان (گهر میل)
        </div>
        <div class="plan-subtitle">
            تمدید یا تغییر پنل (انتخاب پنل جدید)
        </div>
        <div class="plan-subtitle desc">
            * تخفیف پنلی با تخفیف مناسبتی قابل تجمیع است.
        </div>
    </div>
    <div class="height-10"></div>
    <div class="plan-title"><?=Plans::model()->getAttributeLabel('ratio')?></div>
    <div class="plans-labels">
        <?foreach(Plans::$ratioPlans as $key=>$ratio):?>
            <div class="plan-subtitle desc"><?=$ratio?></div>
            <?
            foreach(Plans::model()->serializedFields[$key] as $row):?>
                <div class="plan-subtitle align-right"><?=$row['title']?></div>
            <?endforeach?>
        <?endforeach?>
    </div>
    <div class="height-10"></div>
</div>
<div class="col-md-6 pull-right" style="overflow-x:auto;overflow-y:hidden;">
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'itemView'=>'_view',
        'summaryText'=> '',
        'htmlOptions' => array('style'=>'height: 954px; min-width: 900px;'),

    )); ?>
</div>
<div class="col-md-3 pull-left">
    <div class="plan-name" style="background: transparent;color: transparent">&nbsp;</div>
    <div class="plan-price" style="background: transparent;color: transparent">&nbsp;</div>
    <div class="head plans-facilities"><?=$listText->title?></div>
    <div class="height-2"></div>
    <div class="plans-facilities text"><?=$listText->text?></div>
</div>
<div class="clearfix"></div>
<?if(!is_null($plansFooter->text) AND !empty($plansFooter->text)):?>
    <div class="plans-desc">
        <?=$plansFooter->text?>
    </div>
<?endif;?>
<? $this->renderPartial('_confirm_popup')?>
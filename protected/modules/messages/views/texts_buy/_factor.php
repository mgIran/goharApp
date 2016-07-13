<div class="factor-title">
    <span>فاکتور خرید</span>
</div>
<div class="factor-sections col-md-12">
    <div class="row">
        <div class="title col-md-4">عنوان محصول : </div>
        <div class="value col-md-8"><?=$productTitle?></div>
    </div>
    <?if(isset($aboutProduct) AND !is_null($aboutProduct)):?>
        <div class="row">
            <div class="title col-md-4">درباره محصول : </div>
            <div class="value col-md-8"><?=$aboutProduct?></div>
        </div>
    <?endif;?>
</div>
<div class="factor-parts">
    <?
    foreach($factorFields as $key=>$field):
        if($key === 'final') continue;
        ?>
        <? if($field == 'border')
            $field = array(
                'label' => '<span class="border"></span>',
                'value' => '<span class="border"></span>',
                'unit'  => '<span class="border"></span>'
            );
            ?>
        <div class="factor-sections col-md-4"><?=$field['label']?></div>
        <div class="factor-sections col-md-4 col-md-offset-1 value"><span><?=$field['value']?></span></div>
        <div class="factor-sections col-md-2 col-md-offset-1 unit"><span><?=$field['unit']?></span></div>
        <div class="clearfix"></div>
    <?endforeach;?>
</div>
<div class="factor-final">
    <div class="factor-sections col-md-4"><?=$factorFields['final']['label']?></div>
    <div class="factor-sections col-md-4 col-md-offset-1 value"><span><?=number_format($factorFields['final']['value'])?></span></div>
    <div class="factor-sections col-md-2 col-md-offset-1 unit" style="text-align: center"><span><?=$factorFields['final']['unit']?></span></div>
</div>
<div class="col-md-12">
    <h4>خطوط اختصاصی آماده فروش</h4>
    <?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'messages-texts-numbers-specials-grid',
        'dataProvider' => $specialsModel->search(),
        'emptyText' => 'خطی ثبت نشده است.',
        'summaryText' => '',
        'columns' => array(
            array(
                'name' => 'view',
                'header' => 'شماره',
                'value' => '(!is_null($data->view) AND !empty($data->view))?$data->view:((isset($data->prefix->number))?$data->prefix->number." ":"").$data->number',
                'htmlOptions' => array(
                    'style' => 'direction:ltr;text-align:center'
                )
            ),
            array(
                'name' => 'price',
                'value' => 'number_format($data->price)'
            ),
            array(
                'class'=>'CButtonColumn',
                'template'=>'{buy}',
                'buttons' => array(
                    'buy' => array
                    (
                        'label'=>'',
                        'options'=> array(
                            'class' => 'fa fa-shopping-cart',
                            'style' => 'padding:0 3px;color:#7e569f;',
                            'title' => 'خرید'
                        ),
                        'url'=>'Yii::app()->createUrl("messages/numbers_buy/buy/?special_id=".$data->id)',
                    ),
                )
            ),
        ),
    )); ?>
</div>
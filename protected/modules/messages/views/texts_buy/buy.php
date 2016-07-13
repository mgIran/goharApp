<div id="factor-refresh">
    <div class="col-md-12">
        <?$this->renderPartial("_factor_change",array(
            'model' => $model,
        ));?>
        <div class="plans-labels<?=((isset($_GET['num']))?' active':'')?>" id="factor-details">
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('//buys/_factor',array(
                        'productTitle' => $productTitle,
                        'factorFields' => $factorFields
                    )
                )?>
            </div>
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('//report/_desc',array(
                    'about' => 'text_buy_about'
                ))?>
                <div class="factor-sections factor-box col-md-12">
                    <?$this->renderPartial('//buys/_form', array(
                            'model' => $model,
                            'factorFields' => $factorFields,
                        )
                    )?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="clearfix"></div>
    <br>
    <div class="col-md-12">
        <div class="plans-labels">
            <?$this->renderPartial('//report/_recent',array(
                    'title' => $productTitle,
                    'dataProvider' => $dataProvider
                )
            )?>
        </div>
    </div>
</div>
<div class="item" style="line-height: 3.5;">
    <div class="checkbox-column">
        <input type="checkbox" class="item-check" style="margin-top: 15px !important" name="selectedItems[]" value="<?=$data->id ?>" />
    </div>

    <div class="avatar-column">

    </div>
    <div class="width-40"><?= CHtml::encode($data->title) ?></div>

    <div class="width-40"><?= CHtml::encode((is_object($data->parent)?$data->parent->getFullName():'')) ?></div>
    <div class="width-10">
        <div class="item-buttons hidden">
            <a class="btn btn-info" title="ویرایش" href="<?=Yii::app()->createAbsoluteUrl('messages/emails_categories/update/'.$data->id)?>"><i class="fa fa-edit"></i></a>
            <a href='#' data-toggle="modal" data-backdrop="static" data-target="#confirm-delete" class="btn btn-danger list-button" id="list-button-del" title="حذف" data-href="<?=Yii::app()->createAbsoluteUrl('messages/emails_categories/delete/'.$data->id)?>"><i class="fa fa-trash-o"></i></a>
        </div>
    </div>
</div>
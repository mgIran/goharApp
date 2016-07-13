



<div class="item">
    <div class="checkbox-column">
        <input type="checkbox" class="item-check" name="selectedItems[]" value="<?=$data->id ?>" />
    </div>
    <div class="width-20"><?= CHtml::encode($data->title); ?></div>
    <div class="width-20">
        <div class="item-buttons hidden">
            <a class="btn btn-info" title="ویرایش" href="<?=Yii::app()->createAbsoluteUrl('admins/roles/update/'.$data->id)?>"><i class="fa fa-edit"></i></a>
            <a href='#' data-toggle="modal" data-backdrop="static" data-target="#confirm-delete" class="btn btn-danger list-button" id="list-button-del" title="حذف" data-href="<?=Yii::app()->createAbsoluteUrl('admins/roles/delete/'.$data->id)?>"><i class="fa fa-trash-o"></i></a>
        </div>
    </div>
</div>
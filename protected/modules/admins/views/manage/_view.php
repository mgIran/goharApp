<div class="item" style="line-height: 3.5">
    <div class="checkbox-column">
        <input type="checkbox" class="item-check" style="margin-top: 15px !important" name="selectedItems[]" value="<?=$data->id ?>" />
    </div>
    <div class="avatar-column">
        <a href="<?=Yii::app()->createAbsoluteUrl('admins/manage/'.$data->id)?>">
            <div class="profile-image">
                <img <? if (!is_null( $data->avatar) AND !empty($data->avatar)) echo 'src="' .  Yii::app()->createAbsoluteUrl('upload/admins/avatars/thumbnails_45x45/'.CHtml::encode($data->avatar)) . '"'; ?>  />
            </div>
        </a>
            
    </div>
    <div class="width-20">
        <a href="<?=Yii::app()->createAbsoluteUrl('admins/manage/'.$data->id)?>">
            <?= CHtml::encode($data->first_name).' '.CHtml::encode($data->last_name); ?>
        </a>
    </div>
    
    <div class="width-20"><?= CHtml::encode($data->user_name) ?></div>
    <div class="width-30"><?= CHtml::encode($data->email) ?></div>
    <div class="width-20"><?= $data->AdminsRoles->title ?></div>
    <div class="width-10">
        <div class="item-buttons hidden">
            <a class="btn btn-info" title="ویرایش" href="<?=Yii::app()->createAbsoluteUrl('admins/manage/update/'.$data->id)?>"><i class="fa fa-edit"></i></a>
            <a href='#' data-toggle="modal" data-backdrop="static" data-target="#confirm-delete" class="btn btn-danger list-button" id="list-button-del" title="حذف" data-href="<?=Yii::app()->createAbsoluteUrl('admins/manage/delete/'.$data->id)?>"><i class="fa fa-trash-o"></i></a>
        </div>
    </div>
</div>
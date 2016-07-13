<?php

class iWebFieldsMaker extends CInputWidget
{
    public $name,$items,$list,$model,$options,$id;

    function run()
    {
        $js = "$('#".$this->id."').iWebDropDown(".CJavaScript::encode($this->options).");";
        if(isset($_POST['ajaxInsert']))
        {
            echo '<script type="text/javascript" src="'.$this->getAddress('jquery.iWebDropDown.js').'"></script>';
            echo '<script type="text/javascript">'.$js.'</script>';
        }
        else
        {
            Yii::app()->clientScript->registerCssFile($this->getAddress('iWebDropDown.css'));
            Yii::app()->clientScript->registerScriptFile($this->getAddress('jquery.iWebDropDown.js'), CClientScript::POS_END);



            Yii::app()->clientScript->registerScript(__CLASS__ . $this->id, $js, CClientScript::POS_READY);
        }
        $this->render('dropDown', array(
            'name'=>$this->name,
            'label'=>$this->label,
            'items'=>$this->items,
            'allOption'=>$this->allOption,
            'list'=>$this->list,
        ));
    }

    function getAddress($file){
        return Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.messages.extensions.iWebDropDown.assets').'/'.$file, false, -1);
    }

}
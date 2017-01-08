<?php

class iWebDropDown extends CInputWidget{
    public $name,$label,$items,$allOption,$list,$model,$options,$id,$value = '',
        $headCssClass, $optionCssClass, $icon;

    function run(){
        if(is_null($this->id))
            throw new Exception('id must set for iWebDropDown widget');
        $js = "$('#".$this->id."').iWebDropDown(".((!is_null($this->options))?CJavaScript::encode($this->options):'').");";
        if(isset($_POST['ajaxInsert'])){
            echo '<script type="text/javascript" src="'.$this->getAddress('jquery.iWebDropDown.js').'"></script>';
            echo '<script type="text/javascript">'.$js.'</script>';
        }
        else{
            Yii::app()->clientScript->registerCssFile($this->getAddress('iWebDropDown.css'));
            Yii::app()->clientScript->registerScriptFile($this->getAddress('jquery.iWebDropDown.js'), CClientScript::POS_END);
            Yii::app()->clientScript->registerScript(__CLASS__ . $this->id, $js, CClientScript::POS_READY);
        }
        $this->render('dropDown', array(
            'id'=>$this->id,
            'model'=>$this->model,
            'name'=>$this->name,
            'label'=>$this->label,
            'items'=>$this->items,
            'allOption'=>$this->allOption,
            'list'=>$this->list,
            'headCssClass'=>$this->headCssClass,
            'optionCssClass'=>$this->optionCssClass,
            'icon'=>$this->icon,
        ));
    }

    function getAddress($file){
        return Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.iWebDropDown.assets').'/'.$file, false, -1);
    }

}
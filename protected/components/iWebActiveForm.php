<?php
/**
 * Created by PhpStorm.
 * User: Masoud
 * Date: 12/24/14
 * Time: 5:26 PM
 */

class iWebActiveForm extends CActiveForm {

    public function tripleCheckBox($model,$attribute,$htmlOptions=array())
    {
        $htmlOptions = array_merge($htmlOptions,array('disabled'=>($model->isNewRecord || $model->$attribute==0 )?'disabled':'','value'=>($model->$attribute),'checked'=>($model->$attribute==2)?'checked':''));
        return parent::checkBox($model,$attribute,$htmlOptions);
    }

    public function tripleLabelEx($model,$attribute,$htmlOptions=array())
    {
        $htmlOptions=array_merge($htmlOptions,array('class'=>($model->isNewRecord || $model->$attribute==0)?'gray-label':''));
        return parent::labelEx($model,$attribute,$htmlOptions);
    }

    /*
     * json serialize fields start
     */
    public function serializeFields($model,$name,$fieldsBase,$labelHtmlOptions = array(),$textHtmlOptions = array(),$fieldsType = 'textField',$template=NULL){
        $className = get_class($model);

        $fields = $model->$name;
        $baseTemplate = array(
            'start' =>      '<div class="row">',
            'end' =>        '</div>',
            'labelStart' => '<div class="col-md-4 pull-right">',
            'labelEnd' =>   '</div>',
            'textStart'=>   '<div class="col-md-8 pull-right">',
            'textEnd'=>     '</div>'
        );

        if($template != NULL AND is_array($template))
            $template = array_merge($baseTemplate,$template);
        else
            $template = $baseTemplate;


        $content = "";

        @$fields = json_decode($fields,true);

        if(is_array($fields))
            $fields = $this->mergeValues($fieldsBase,$fields);
        else
            $fields = $fieldsBase;

        $i = 0;
        foreach($fields as $key=>$field)
        {
            if(!isset($field['name']))
            {
                $field['name'] = 'y'.$i;
                $i++;
            }
            if(!isset($field['value']))
                $field['value'] = '';
            if(!isset($field['title']))
                $field['title'] = $field['name'];

            // start of each row
            $content .= $template['start'];

            // start of each label
            $content .= $template['labelStart'];
            $content .= CHtml::label($field['title'],$key,$labelHtmlOptions);
            $content .= $template['labelEnd'];
            // end of each label

            // start of each text field
            $content .= $template['textStart'];
            $defaultTextHtmlOptions = array(
                'id'=>$key,
            );


            $tempHtmlOptions = array_merge($defaultTextHtmlOptions,$textHtmlOptions);
            if(!is_array($fieldsType)){
                if(strpos($fieldsType,',') != -1)
                    $fieldsType = explode(',',$fieldsType);
                else
                    $fieldsType = array($fieldsType);
            }
            foreach($fieldsType as $type){

                if($type == 'checkBox'){

                    if(count($fieldsType) >= 2){
                        if(!isset($field['checked']))
                            $field['checked'] = '';
                        $inputName = $className.'['.$name.']['.$key.'][checked]';
                        $value = $field['checked'];
                    }
                    else{
                        $inputName = $className.'['.$name.']['.$key.'][value]';
                        $value = $field['value'];
                    }
                    $content .= CHtml::checkBox($inputName,$value,array(
                        'id' => "{$className}_{$name}_{$key}",
                        'class' => 'pull-right css-checkbox',
                        'style' => 'margin-left:4px;',
                    ));
                    $content .= CHtml::label('',"{$className}_{$name}_{$key}",array('class'=>'pull-right css-label','style'=>'margin-top:8px'));
                }
                else
                    $content .= CHtml::$type($className.'['.$name.']['.$key.'][value]',$field['value'],$tempHtmlOptions);
            }

            $content .= $template['textEnd'];
            // end of each text field

            $content .= '<input type="hidden" id="'.$className.'_'.$name.'"/>';

            // end of each row
            $content .= $template['end'];

        }

        // script for validation
        Yii::app()->clientScript->registerScript('iWebActiveForm_'.$className.'_'.$name,"
                $(document).on('blur','input[name^=\"".$className."[$name]\"]',function(){
                    $(\"#$className"."_"."$name\").val(($(\"#$className"."_"."$name\").val()==\"\")?\"1\":\"\").blur();
                });
            ",CClientScript::POS_END);

        return $content;
    }

    public static function mergeValues($fields1,$fields2){
        $temp = $fields1;
        foreach($fields1 as $key1=>$field1){
            if(!is_array($field1))
                continue;
            foreach($fields2 as $key2=>$field2){
                if($key1 == $key2){
                    if(!is_array($field2))
                        continue;
                    $temp[$key1] = array_merge($field1,$field2);
                    break;

                }
            }
        }
        return $temp;
    }
    /*
     * json serialize fields end
     */

} 
<?php

class UsersOtherLegalDocuments extends CFormModel
{
	public $id,$title,$file,$user_id;

	public function rules()
	{
		return array(
            array('id','filter','filter'=>'time','on'=>'insert'),
            array('title','default','value'=>'مدرک حقوقی'),
            array('file,id','safe')
		);
	}

    public function save($runValidation=true)
    {
        $model = Users::model()->findByPk($this->user_id);
        if(!$runValidation || $this->validate()){
            // if other is empty add first
            if(empty($model->other_legal_documents) OR is_null($model->other_legal_documents)){
                $array = array($this->attributes);
                return $this->saveArray($model,$array);
            }

            $array = json_decode($model->other_legal_documents,TRUE);

            // check for if exist,update that
            foreach($array as $key=>$item){
                if($item['id'] == $this->id){
                    if(is_null($this->file))
                        $this->file = $item['file'];
                    $array[$key] = $this->attributes;
                    return $this->saveArray($model,$array);
                }
            }

            // check if not greater that 5 save it
            if(count($array) < 5){
                $array[] = $this->attributes;
                return $this->saveArray($model,$array);
            }
            else{
                return 5;
            }
        }
        else
            return false;
    }

    // function for update other_legal_documents
    public function saveArray($model,$array){
        $model->other_legal_documents = json_encode($array);
        $model->scenario = 'changeValue';
        return $model->save();
    }

    public function find($id){
        $id = intval($id);
        $model = Users::model()->findByPk($this->user_id);
        $array = json_decode($model->other_legal_documents,TRUE);
        // check for if exist,update that
        foreach($array as $item){
            if($item['id'] == $id){
                $this->attributes = $item;
                return $this;
            }
        }
    }

    public function delete(){
        $model = Users::model()->findByPk($this->user_id);
        $array = json_decode($model->other_legal_documents,TRUE);

        foreach($array as $key=>$item){
            if($item['id'] == $this->id){
                unset($array[$key]);
                return $this->saveArray($model,$array);
            }
        }
    }

	public function attributeLabels()
	{
		return array(
            'title'=>'عنوان',
            'file'=>'فایل آپلودی',
		);
	}
}

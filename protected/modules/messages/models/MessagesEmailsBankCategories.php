<?php
class MessagesEmailsBankCategories extends iWebActiveRecord
{
	public function tableName()
	{
        return '{{messages_emails_bank_categories}}';
	}
	public function rules()
	{
		return array(
			array('title', 'required'),
            array('title', 'UniqueAttributesValidator', 'with'=>'parent_id',"message"=>'دسته بندی "{value}" قبلا ثبت شده است، لطفا نام دیگری را وارد نمایید','on'=>'insert'),
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255,'min'=>2),
            array('title','filter','filter'=>'htmlspecialchars'),
            array('title', 'match', 'pattern'=>'!^[^/]+$!', 'message'=>'عنوان نمی تواند شامل کاراکتر های خاص باشد.'),
			array('id, parent_id, title', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'parent' => array(self::BELONGS_TO, 'MessagesEmailsBankCategories', 'parent_id'),
            'children' => array(self::HAS_MANY, 'MessagesEmailsBankCategories', 'parent_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'parent_id' => 'والد',
			'title' => 'عنوان',
            'parent' => 'والد',
		);
	}

    public function beforeValidate()
    {
        if(is_null($this->parent_id))
        {
            $condition="";
            $params = array(':title'=>$this->title);
            if(!$this->isNewRecord)
            {
                $params[':id'] = $this->id;
                $condition= " AND id != :id";
            }

            if($this->find("title = :title AND parent_id IS NULL".$condition,$params))
            {
                $this->addError("title",'دسته بندی "'.$this->title.'" قبلا ثبت شده است، لطفا نام دیگری را وارد نمایید');
                return false;
            }
        }
        elseif(!$this->isNewRecord)
        {
            if($this->find("title = :title AND id != :id",array(':title'=>$this->title,':id'=>$this->id)))
            {
                $this->addError("title",'دسته بندی "'.$this->title.'" قبلا ثبت شده است، لطفا نام دیگری را وارد نمایید');
                return false;
            }
        }
        return parent::beforeValidate();
    }

    public function optionChildren($optionId) {
        $result = $this::model()->findAll(
            'parent_id=:p2',array(
            ':p2'=>$optionId
        ));
        return $result;
    }

    public function firstOption()
    {
        $model = BooksSubjects::model()->find(
            "parent_id IS NULL AND prev_id IS NULL");
        return $model;
    }

    public function nextOption($parentId,$prevOptionId) {
        $params = array(
            ':p3'=>$prevOptionId
        );
        if($parentId == 0)
            $parentCondition = 'IS NULL';
        else
        {
            $parentCondition = '= :parent';
            $params = array(
                ':parent'=>$parentId,
                ':p3'=>$prevOptionId
            );

        }
        $model = $this::model()->find("parent_id $parentCondition and prev_id = :p3",$params);
        return $model;
    }

    public function otherOptions($parentId) {
        $params = array();
        if($parentId == 0)
            $parentCondition = 'IS NULL';
        else
        {
            $parentCondition = '= :parent';
            $params = array(
                ':parent'=>$parentId,
            );

        }
        $model = $this::model()->findAll(
            "parent_id $parentCondition AND prev_id IS NOT NULL",$params);
        return $model;
    }

    public function getFullName()
    {
        $title = $this->title;
        $temp = $this;
        while(!is_null($temp->parent))
        {
            $temp = $temp->parent;
            $title = $temp->title.'/'.$title;
        }
        return $title;
    }

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('title',$this->title,true);
        $criteria->order="id DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

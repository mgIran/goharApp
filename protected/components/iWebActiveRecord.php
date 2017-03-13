<?php
class iWebActiveRecord extends CActiveRecord
{
    public $otherClassNames = [
        'Events' => 'Ceremony',
        'Tickets' => 'Ticket',
        'Notifications' => 'Notification',
    ];

    protected function afterSave()
    {
        $module = get_called_class();
        if(key_exists($module, $this->otherClassNames))
            $module = $this->otherClassNames[$module];
        if($module != "Log"){
            $logAttribute = array();
            $logAttribute["user_id"] = (isset(Yii::app()->user->userID)?Yii::app()->user->userID:NULL); // get user ID
            $logAttribute["type"] = (isset(Yii::app()->user->type)?Yii::app()->user->type:NULL);
            $logAttribute["module"] = $module; // get module ID
            $logAttribute["action"] = $this->scenario;
            if(isset($this->id))
                $logAttribute["pk"] = $this->id;
            $log = new Log;
            $log->attributes = $logAttribute;
            $log->save();
        }
        return parent::afterSave();
    }

    protected function afterDelete()
    {
        $module = get_called_class();
        if(key_exists($module, $this->otherClassNames))
            $module = $this->otherClassNames[$module];
        if($module != "Log"){
            $logAttribute = array();
            $logAttribute["user_id"] = (isset(Yii::app()->user->userID)?Yii::app()->user->userID:NULL); // get user ID
            $logAttribute["type"] = (isset(Yii::app()->user->type)?Yii::app()->user->type:NULL);
            $logAttribute["module"] = $module; // get module ID
            $logAttribute["action"] = "delete";
            $log = new Log;
            $log->attributes = $logAttribute;
            $log->save();
        }
        return parent::afterDelete();
    }

    public function multipleRowInsert($array = array())
    {
        if($array == array())
            return false;
        $builder = Yii::app()->db->schema->commandBuilder;

        $command = $builder->createMultipleInsertCommand($this->tableName(), $array);
        if($command->execute())
            return true;

    }

    public function sortRow($oldPos, $newPos, $inStart = false)
    {
        // set table table
        $table = $this->tableName();

        // positions
        $oldPos = intval($oldPos);
        $newPos = intval($newPos);

        $this::model()->updateAll(array("in_stack" => 0), "sort = $oldPos");

        if($inStart)    // when drag to start of rows
            $newPos++;

        if($oldPos > $newPos)   // when drag from top to bottom
        {
            $tempStart = $newPos;
            $j = $oldPos;
            $sign = 1;
        }else    // when drag from bottom to top
        {
            $tempStart = $oldPos;
            $newPos--;
            $j = $newPos;
            $sign = -1;
        }

        // make where clause for select ids
        $where = array();
        for($i = $tempStart;$i <= $j;$i++){
            $where[] = $i;
        }
        $where = implode(',', $where);
        $sql = "SELECT GROUP_CONCAT(id ORDER BY sort SEPARATOR ',') AS ids FROM $table WHERE sort IN($where)";
        $ids = Yii::app()->db->createCommand($sql)->queryRow();
        $ids = $ids['ids'];

        // make where clause for change position
        $sql = "";
        $i = $tempStart;
        $where = array();
        foreach(explode(",", $ids) as $id){
            if($i == $oldPos)
                $temp = $newPos;
            else
                $temp = $i + $sign;
            $sql .= "WHEN $id THEN $temp ";
            $where[] = $id;
            $i++;
        }
        $where = implode(',', $where);

        if($sql != ""){
            // update sort fields query
            $sql = "UPDATE $table
                      SET sort = CASE id
                      $sql
                      END
                      WHERE id IN ($where)";

            $command = Yii::app()->db
                ->createCommand($sql);

            return ($command->execute()?true:false);
        }
        Yii::app()->end();

    }

    public function updateSortInList($parentId, $sql, $where)
    {
        // set table table
        $table = $this->tableName();
        $sql = "UPDATE $table
                      SET parent_id = $parentId,sort = CASE id
                      $sql
                      END
                      WHERE id IN ($where);";
        Yii::app()->db
            ->createCommand($sql)
            ->execute();

    }
}
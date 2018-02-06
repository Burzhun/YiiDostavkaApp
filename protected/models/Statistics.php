<?php

class Statistics extends CActiveRecord{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{statistics}}';
    }


    static function Increase($id){
        $stat=Statistics::model()->findByPk($id);
        $stat->count+=1;
        $stat->save();
    }
}
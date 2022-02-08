<?php

/**
 * This is the model class for table "ptw_condition_list".
 *
 * The followings are the available columns in table 'ptw_condition_list':
 * @property string $condition_id
 * @property string $condition_name
 * @property string $condition_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwTypeList[] $ptwTypeLists
 * @author LiuXiaoyuan
 */
class UniFormDataAcci extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'uni_form_data_acci';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('condition_id, condition_name, condition_name_en, record_time', 'required'),
            array('condition_id', 'length', 'max'=>64),
            array('condition_name, condition_name_en', 'length', 'max'=>4000),
            array('status', 'length', 'max'=>2),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('condition_id, condition_name, condition_name_en, status, record_time', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ptwTypeLists' => array(self::MANY_MANY, 'PtwTypeList', 'ptw_type_condition(condition_id, type_id)'),
        );
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PtwCondition the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    //查询安全单
    public static function detailList($apply_id){

        $sql = "SELECT * FROM uni_form_data_acci WHERE  apply_id = '".$apply_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $i => $j){
            $rs[$j['item_id']]['data_id'] = $j['data_id'];
            $rs[$j['item_id']]['apply_id'] = $j['apply_id'];
            $rs[$j['item_id']]['form_id'] = $j['form_id'];
            $rs[$j['item_id']]['item_value'] = $j['item_value'];
        }
        return $rs;
    }

}
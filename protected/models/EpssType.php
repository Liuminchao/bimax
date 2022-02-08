<?php

/**
 * This is the model class for table "ptw_type_list".
 *
 * The followings are the available columns in table 'ptw_type_list':
 * @property string $type_id
 * @property string $type_name
 * @property string $type_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwConditionList[] $ptwConditionLists
 * @author LiuXiaoyuan
 */
class EpssType extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_epss_type';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PtwType the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }


    //EPSS类型列表
    public static function levelText() {
        $sql = "select * from bac_epss_type where status='00' and contractor_id = '0' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['type_id']] = $row['type_name'];

            }
        }
        return $rs;
    }

    //EPSS类型列表
    public static function typeListByType($type_id) {
        $sql = "select * from bac_epss_role a,bac_epss_type b where a.status='00' and  a.type='".$type_id."' and a.type=b.type_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['team_name']][$row['role_id']] = $row['role_name'];
            }
        }
        return $rs;
    }
}

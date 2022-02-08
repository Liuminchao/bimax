<?php

/**
 * 功能列表(字典)
 * @author Liumc
 */
class App extends CActiveRecord {

    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_app';
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function appList(){
        if (Yii::app()->language == 'zh_CN')
            $field = "app_name";
        else
            $field = "app_name";

        $sql = "SELECT app_id, ".$field." as app_name FROM bac_app WHERE status=0";
        $sql .= "  order by app_id";//var_dump($sql);

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['app_id']] = $row['app_name'];
            }
        }
        return $rs;
    }

    public static function appAllList(){
        if (Yii::app()->language == 'zh_CN')
            $field = "app_name";
        else
            $field = "app_name";

        $sql = "SELECT app_id, ".$field." as app_name FROM bac_app ";
        $sql .= "  order by app_id";//var_dump($sql);

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['app_id']] = $row['app_name'];
            }
        }
        return $rs;
    }

    public static function appMenuList(){
        if (Yii::app()->language == 'zh_CN')
            $field = "app_name";
        else
            $field = "app_name_en";

        $sql = "SELECT app_id, ".$field." as app_name FROM sys_app WHERE app_status ='00' ";
        $sql .= "  order by app_id";//var_dump($sql);

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['app_id']] = $row['app_name'];
            }
        }
        return $rs;
    }

}

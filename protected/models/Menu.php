<?php

/**
 * 功能列表(字典)
 * @author Liumc
 */
class Menu extends CActiveRecord {

    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'sys_menu';
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

    public static function menuList(){
        if (Yii::app()->language == 'zh_CN')
            $field = "menu_name";
        else
            $field = "menu_name";

        $sql = "SELECT menu_id, ".$field." as menu_name FROM sys_menu WHERE status=0";
        $sql .= "  order by menu_id";//var_dump($sql);

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['menu_id']] = $row['menu_name'];
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
    public static function MenuListByApp() {
        $menu = array(
            'OBS' => '205',
            'CTC' => '206',
            'CLT' => '207',
            'INS' => '208',
        );
        return $menu;
    }

    public static function appMenuList(){

        if (Yii::app()->language == 'zh_CN')
            $field = "menu_name";
        else
            $field = "menu_name_en";
        $sql = "SELECT menu_id, ".$field." as menu_name FROM sys_menu WHERE menu_status ='00' and menu_id not in (101,102) and app_type like '%2%' ";
        $sql .= "  order by menu_id";
//        var_dump($sql);
//        exit;
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();


        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['menu_id']] = $row['menu_name'];
            }
        }
//        var_dump($rs);
//        exit;
        return $rs;
    }

}

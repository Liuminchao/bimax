<?php

/**
 * 分包管理
 * @author LiuXiaoyuan
 */
class Subcon extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_subcon';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'subson_name' => Yii::t('sys_role', 'role_name'),
            'subson_name_en' => Yii::t('sys_role', 'role_name_en'),
            'status' => Yii::t('sys_role', 'status'),
        );
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

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('sys_role', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_role', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
    }
    
    //分包类型列表
    public static function subconList() {

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT id,subcon_name FROM bac_subcon WHERE status=00 order by id";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['id']] = $row['subcon_name'];
                }
            }   
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT id,subcon_name_en FROM bac_subcon WHERE status=00 order by id";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['id']] = $row['subcon_name_en'];
                }
            } 
        }
        return $rs;
    }

    
    //根据id得到分包类型
    public static function subconByTypeList($type) {
        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT id,subcon_name,team FROM bac_subcon WHERE id=:id AND status='00' ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":id", $type, PDO::PARAM_STR);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['subcon_name'] = $row['subcon_name'];
                    $rs[$key]['team'] = $row['team'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT id,team,subcon_name_en FROM bac_subcon WHERE id=:id AND status='00'";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":contractor_type", $type, PDO::PARAM_STR);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['subcon_name_en'] = $row['subcon_name_en'];
                    $rs[$key]['team'] = $row['team'];
                }
            }
        }
        return $rs;
    }
}

<?php

/**
 * EPSS角色管理
 * @author LiuXiaoyuan
 */
class EpssRole extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_epss_role';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'role_id' => Yii::t('sys_role', 'role_id'),
            'contractor_type' => Yii::t('sys_role', 'contractor_type'),
            'role_name' => Yii::t('sys_role', 'role_name'),
            'role_name_en' => Yii::t('sys_role', 'role_name_en'),
            'team_name' => Yii::t('sys_role', 'team_name'),
            'team_name_en' => Yii::t('sys_role', 'team_name_en'),
            'sort_id' => Yii::t('sys_role', 'order'),
            'status' => Yii::t('sys_role', 'status'),
            'record_time' => Yii::t('sys_role', 'record_time'),
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


    //查询角色分组
    public static function roleTeamList(){

        $sql = "SELECT team_id,team_name FROM bac_epss_role WHERE status=00 group by team_name order by sort_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['team_id']] = $row['team_name'];
            }
        }
//        $rs['No'] = '-'.Yii::t('sys_role', 'team_name').'-';
        return $rs;
        
    }
    
    public static function roleListByTeam($team_id=''){
            
        $sql = "SELECT role_id, role_name FROM bac_epss_role WHERE status=00";
        if($team_id <> '')
            $sql .= " and team_id='".$team_id."'";
        $sql .= "  order by sort_id";//var_dump($sql);
        
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_id']] = $row['role_name'];
            }
        }
        return $rs;
    }
    
    //角色列表
    public static function roleList() {

        $sql = "SELECT role_id,role_name FROM bac_epss_role WHERE status=00 order by sort_id";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_id']] = $row['role_name'];
            }
        }

        return $rs;
    }

    //团队列表
    public static function teamListByType($type_id) {

        $sql = "SELECT team_id,team_name FROM bac_epss_role WHERE status=00 and type='".$type_id."' order by sort_id";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['team_id']] = $row['team_name'];
            }
        }
        return $rs;
    }

    
    //角色列表
    public static function roleidListByTeam(){
            
        $sql = "SELECT role_id, role_name FROM bac_epss_role WHERE status=00";
        
        $sql .= "  order by sort_id";//var_dump($sql);
        
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_name']] = $row['role_id'];
            }
        }
        return $rs;
    }


}

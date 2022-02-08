<?php

/**
 * Routine项目区域
 * @author LiuMinchao
 */
class RoutineCheckBlock extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_routine_check_block';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'region' => Yii::t('proj_project', 'region'),
        );
    }
    //查询PTW区域分布
    public static function regionList($apply_id){

        $sql = "SELECT block,secondary_region FROM bac_routine_check_block WHERE  check_id = '".$apply_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['block']][] = $row['secondary_region'];
            }
        }else{
            $rs = array();
        }
        return $rs;

    }
}

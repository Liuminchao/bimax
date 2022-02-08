<?php

/**
 * 例行检查详情
 * @author LiuMinchao
 */
class RoutineCheckDetail extends CActiveRecord {

    //状态：0-进行中，1－已关闭，2-超时强制关闭。
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_CLOSE = '1'; //已关闭
    const STATUS_TIMEOUT_CLOSE = '2'; //超时强制关闭


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_routine_check_detail';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

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

    //根据安全单编号查询安全单步骤
    public static function detailList($check_id){

        $sql = "SELECT * FROM bac_routine_check_detail WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    //根据承包商和日期查询安全单步骤
    public static function detailAllList($args){
        $contractor_id = Yii::app()->user->contractor_id;
        $sql = "SELECT a.* FROM bac_safety_check_detail a,bac_safety_check b WHERE a.check_id = b.check_id AND b.contractor_id = '".$contractor_id."'
           AND apply_time >= '".$args['start_date']."' AND apply_time <= '".$args['end_date']."' AND b.status != 0";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows)>0){
            foreach ($rows as $cnt => $list){
                $rs[$list['check_id']][] = $list;
            }
            //步骤如果超过或包含三步，取第一步和倒数第二步，如果少于三步，取第一步和最后一步
            foreach($rs as $check_id => $list){
//                $r[$check_id][] = $list[0];
                if(count($list) >= 3){
                    $r[$check_id][] = array_slice($list,0,1);
                    $r[$check_id][]= array_slice($list,-2,1);
                }else{
                    $r[$check_id][] = array_slice($list,0,1);
                    $r[$check_id][] = array_slice($list,-1,1);
                }
//            var_dump($r);
//            exit;
            }
            return $r;
        }else{
            return $rows;
        }

    }
    //处理类型列表
    public static function dealList() {
        $deal_list = array(
            '1'  => '安全检查组->负责人',
            '2'  => '负责人->安全检查组',
            '3'  => '关闭检查单',
            '4'  => '强制关闭检查单',
        );
        return $deal_list;
    }
}

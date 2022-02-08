<?php

/**
 * 质量检查详情
 * @author LiuMinchao
 */
class QaDefectDetail extends CActiveRecord {

    //状态：0-进行中，1－已关闭，2-超时强制关闭。
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_CLOSE = '1'; //已关闭
    const STATUS_TIMEOUT_CLOSE = '2'; //超时强制关闭


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_defect_record_detail';
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

        $sql = "SELECT * FROM qa_checklist_record_detail WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    //根据安全单编号查询安全单当前步骤时间
    public static function currentDetail($check_id){

        $sql = "SELECT b.record_time FROM qa_checklist_record a,qa_checklist_record_detail b WHERE  a.check_id = '".$check_id."' and a.current_step = b.step ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    //详情
    public static function detailRecord($check_id){

        $sql = "SELECT * FROM qa_checklist_record_detail WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
    //详情
    public static function detaildataRecord($check_id,$data_id){

        $sql = "SELECT * FROM qa_checklist_record_detail WHERE  check_id = '".$check_id."' and data_id = '".$data_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //详情(按照最后一步)
    public static function stepRecord($check_id,$step){

        $sql = "SELECT * FROM qa_checklist_record_detail WHERE  check_id = '".$check_id."' and step = '".$step."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //处理类型列表
    public static function dealList() {
        $deal_list = array(
            '0'  => 'Raised',
            '1'  => 'Rectified',
            '2'  => 'Replied',
            '3'  => 'Commented',
            '4'  => 'Closed',
            '5'  => 'Rejected',
            '6'  => 'Closed',
        );
        return $deal_list;
    }

    //状态CSS
    public static function dealCss($key = null) {
        $rs = array(
            '1'  => 'bg-info',
            '5'  => 'bg-success',
            '91'  => 'bg-info',
            '92'  => 'bg-info',
            '93'  => 'bg-info',
            '99'  => 'bg-danger',
            '2'  => 'bg-info',
            '3'  => 'bg-info',
            '4'  => 'bg-info',
            '6'  => 'bg-info',
            '7'  => 'bg-info',
            '9'  => 'bg-danger',
        );
        return $key === null ? $rs : $rs[$key];
    }
}

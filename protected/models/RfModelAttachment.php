<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfModelAttachment extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_model_attachment';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            '0' => 'unsynchronized',
            '1' => 'Synchronized',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            '0' => 'label-success', //未同步
            '1' => 'label-info', //已同步
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyBasicLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //添加
    public static function insertList($args){

        if($args['entityId']){
            $sql = "delete from rf_model_attach where attach_id = '".$args['id']."' ";
            $command = Yii::app()->db->createCommand($sql);
            $re = $command->execute();
            $uuid = explode(',',$args['uuid']);
            $entity_id = explode(',',$args['entityId']);
            foreach($uuid as $i => $j){
                $sql = "insert into rf_model_attach (attach_id, model_id,version,uuid,entity_id) values (:attach_id,:model_id,:version,:uuid,:entity_id)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":attach_id", $args['id'], PDO::PARAM_STR);
                $command->bindParam(":model_id", $args['model_id'], PDO::PARAM_STR);
                $command->bindParam(":version", $args['version'], PDO::PARAM_STR);
                $command->bindParam(":uuid", $j, PDO::PARAM_STR);
                $command->bindParam(":entity_id", $entity_id[$i], PDO::PARAM_STR);
                $rs = $command->execute();
            }
        }else{
            $rs =1;
        }
        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 详情
     */
    public static function dealList($id) {
        $sql = "select * from rf_model_attach
                 where attach_id=:id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":id", $id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $uuid= '';
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $uuid .= $row['uuid'] . ',';
                $r['model_id'] = $row['model_id'];
                $r['version'] = $row['version'];
            }
            if ($uuid != '')
                $r['uuid'] = substr($uuid, 0, strlen($uuid) - 1);

        }else{
            $r['model_id'] = '';
            $r['version'] = '';
            $r['uuid'] = '';
        }

        return $r;
    }


}

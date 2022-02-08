<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfModelView extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_record_view';
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
        if($args['view']){
            $sql = "insert into rf_record_view (check_id,step,model_id,version,view) values (:check_id,:step,:model_id,:version,:view)";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
            $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
            $command->bindParam(":model_id", $args['model_view_id'], PDO::PARAM_STR);
            $command->bindParam(":version", $args['model_view_version'], PDO::PARAM_STR);
            $command->bindParam(":view", $args['view'], PDO::PARAM_STR);
            $rs = $command->execute();
        }else{
            $rs = 1;
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
    public static function dealList($check_id,$step) {
        $sql = "select * from rf_record_view
                 where check_id=:check_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }


}

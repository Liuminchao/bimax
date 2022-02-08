<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class PbuExportDetail extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pbu_export_detail';
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

    public static function InsertExportDetail($export_args,$export_id){
        try {
            foreach($export_args as $i => $j){
                $record_time = date('Y-m-d H:i:s', time());
                $status= '0';
                $model = new PbuExportDetail('create');
                $model->export_id = $export_id;
                $model->col = $i;
                $model->value = $j[0];
                $model->save();

                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
    }

    public static function SaveExportDetail($export_args,$export_id){
        try {
            foreach($export_args as $i => $j){
                $sql = 'UPDATE pbu_export_detail set value=:value WHERE export_id=:export_id and col=:col';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":value", $j[0], PDO::PARAM_STR);
                $command->bindParam(":export_id", $export_id, PDO::PARAM_STR);
                $command->bindParam(":col", $i, PDO::PARAM_STR);
                $result = $command->execute();
                if($result){
                    $r['msg'] = Yii::t('common', 'success_update');
                    $r['status'] = 1;
                    $r['refresh'] = true;
                }
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //查询详情
    public static function detailList($export_id){

        $sql = "SELECT * FROM pbu_export_detail where export_id = '".$export_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
}

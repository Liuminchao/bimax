<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class PbuExport extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pbu_export';
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


    public static function SaveExport($export_args,$pro_args){
        $sql = "SELECT count(*) as cnt FROM pbu_export WHERE project_id=:project_id and status='0' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $pro_args['program_id'], PDO::PARAM_STR);
        $rows = $command->queryAll();
        $cnt = $rows[0]['cnt']+1;
        $trans = Yii::app()->db->beginTransaction();
        try {
            if(!$pro_args['template_id']){
                $record_time = date('Y-m-d H:i:s', time());
                $status= '0';
                $model = new PbuExport('create');
                $model->project_id = $pro_args['program_id'];
                $model->export_name = 'Export Template '.$cnt;
                $model->save();
                $export_id = $model->export_id;
                $r = PbuExportDetail::InsertExportDetail($export_args,$export_id);
            }else{
                $r = PbuExportDetail::SaveExportDetail($export_args,$pro_args['template_id']);
            }
            $trans->commit();
            $r['msg'] = Yii::t('common', 'success_update');
            $r['status'] = 1;
            $r['refresh'] = true;
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }

    //查询详情
    public static function detailList($program_id){

        $sql = "SELECT * FROM pbu_export where project_id = '".$program_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    //按项目查找模版
    public static function templateByProgram($project_id){
        $sql = "SELECT export_id,export_name FROM pbu_export WHERE status=0 and project_id='".$project_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['export_id']] = $row['export_name'];
            }
        }else{
            $sql = "SELECT export_id,export_name FROM pbu_export WHERE status=0 and project_id='0'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            foreach ($rows as $key => $row) {
                $rs[$row['export_id']] = $row['export_name'];
            }
        }

        return $rs;
    }

    public static function DelExport($export_id){
        try {
            $sql = "UPDATE pbu_export set status = '9' WHERE export_id=:export_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":export_id", $export_id, PDO::PARAM_STR);
            $result = $command->execute();
            if($result){
                $r['msg'] = Yii::t('common', 'success_delete');
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
                $r['msg'] = Yii::t('common', 'error_delete');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }

}

<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfModelComponent extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_record_component';
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

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition_1 = '';
        $condition_2 = ' 1=1 ';
        $params = array();
        $sql = "select * from rf_list where ";

        $program_id = $args['program_id'];
        $pro_model =Program::model()->findByPk($args['program_id']);
        $program_id = $pro_model->root_proid;

        $condition_1.= " program_id = '$program_id' ";

        if ($args['model_id'] != '') {
            $model_id = $args['model_id'];
            $condition_2.= " and model_id = '$model_id'";
        }

        if ($args['entityId'] != '') {
            $entityId = $args['entityId'];
//            $condition_2.= " and entityId like '%$entityId%'";
            $condition_2.= " and entityId in ($entityId) ";
        }

        $sql = "select * from rf_record where check_id in (select check_id from rf_record_component where $condition_2 ) and $condition_1";
//        var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($data);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($data,$start,$pageSize);
        }else{
            $pagedata = array();
        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;

        return $rs;
    }

    //添加
    public static function insertList($args){
        if($args['uuid']){
            $uuid = explode(',',$args['uuid']);
            $entityId = explode(',',$args['entityId']);
            foreach($uuid as $i => $j){
                $sql = "insert into rf_record_component (check_id,step,model_id,version,entityId,uuid) values (:check_id,:step,:model_id,:version,:entityId,:uuid)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
                $command->bindParam(":model_id", $args['model_component_id'], PDO::PARAM_STR);
                $command->bindParam(":version", $args['model_component_version'], PDO::PARAM_STR);
                $command->bindParam(":entityId", $entityId[$i], PDO::PARAM_STR);
                $command->bindParam(":uuid", $j, PDO::PARAM_STR);
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
    public static function dealList($check_id,$step) {
        $sql = "select * from rf_record_component
                 where check_id=:check_id and step=:step ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $uuid = '';
        $entityId= '';
        if(count($rows)>0){
            foreach ($rows as $key => $row) {
                $uuid .= $row['uuid'] . ',';
                $entityId .= $row['entityId'] . ',';
                $model_id = $row['model_id'];
                $version = $row['version'];
            }
            if ($uuid != '')
                $r[0]['uuid'] = substr($uuid, 0, strlen($uuid) - 1);

            if ($entityId != '')
                $r[0]['entityId'] = substr($entityId, 0, strlen($entityId) - 1);

            $r[0]['model_id'] = $model_id;
            $r[0]['version'] = $version;
        }
        return $r;
    }

}

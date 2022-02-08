<?php

/**
 * 聊天室
 * @author LiuMinchao
 */
class RfiChat extends CActiveRecord {

    //状态：0-进行中，1－结束
    const STATUS_NORMAL = '0'; //进行中
    const STATUS_STOP = '1'; //结束

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_rfi_chat';
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

    //添加
    public static function insertMsg($args){

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $exist_data = Device::model()->count('device_id=:device_id and contractor_id=:contractor_id', array('device_id' => $args['device_id'],'contractor_id' => $contractor_id));

        $model = new RfChat('create');
        $args['status'] =self::STATUS_NORMAL;
//        $trans = $model->dbConnection->beginTransaction();
        try{
            $model->id= $args['id'];
            $model->room_id = $args['program_id'];
            $model->clinent_id = $args['clinent_id'];
            $model->client_name = $args['client_name'];
            $model->msg = $args['msg'];
            $model->record_time = date("Y-m-d H:i:s");
            $result = $model->save();//var_dump($result);exit;

            if ($result) {
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
//                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }

        }
        catch(Exception $e){
//            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //查询聊天记录
    public static function queryMsg($args){
        $sql = "SELECT * FROM bac_rfi_chat WHERE id=:id AND room_id=:room_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":id", $args['id'], PDO::PARAM_INT);
        $command->bindParam(":room_id", $args['program_id'], PDO::PARAM_INT);
        $rows = $command->queryAll();
        return $rows;
    }
}

<?php

/**
 * 分包项目区域
 * @author LiuMinchao
 */
class RfNoSet extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'rf_no_set';
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
    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('device', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('device', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //已启用
            self::STATUS_DISABLE => ' label-danger', //未启用
        );
        return $key === null ? $rs : $rs[$key];
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'region' => Yii::t('proj_project', 'region'),
        );
    }

    //展示项目区域
    public static function regionShow($program_id){

        $sql = "SELECT location,block,secondary_region FROM bac_program_block WHERE status=0 and program_id = '".$program_id."' order by location asc ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['block']][] = $row['secondary_region'];
            }
        }
        return $rs;

    }

    //展示项目区域固定位置
    public static function locationShow($program_id){

        $sql = "SELECT location,block,secondary_region FROM bac_program_block WHERE status=0 and program_id = '".$program_id."' order by location asc ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['location']][] = $row['secondary_region'];
            }
        }
        return $rs;

    }

    //展示项目区域固定位置
    public static function locationBlock($program_id){

        $sql = "SELECT location,block FROM bac_program_block WHERE status=0 and program_id = '".$program_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['location']]['block'] = $row['block'];
            }
        }
        return $rs;

    }
    /**
     * 查询总包项目下的分包项目
     */
    public static function ScProgram($program_id){
        $sql = "select program_id
                  from bac_program
                 where  root_proid =".$program_id." and status=00 ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    //插入区域数据
    public static function InsertRegion($rf,$rfa,$rfi){
        $status = self::STATUS_NORMAL;
        $trans = Yii::app()->db->beginTransaction();
        try {
            if(count($rfa)>0){
                $rfa_type = '2';
                $sql = 'DELETE FROM rf_no_set WHERE program_id=:program_id and type=:type';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":program_id", $rf['program_id'], PDO::PARAM_INT);
                $command->bindParam(":type", $rfa_type, PDO::PARAM_STR);
                $command->execute();
                foreach($rfa as $i => $j){
                    foreach ($j as $x => $y){
                        $sub_sql = 'INSERT INTO rf_no_set(program_id,type,attribute,val,status) VALUES(:program_id,:type,:attribute,:val,:status)';
                        $command = Yii::app()->db->createCommand($sub_sql);
                        $command->bindParam(":program_id", $rf['program_id'], PDO::PARAM_INT);
                        $command->bindParam(":type", $rfa_type, PDO::PARAM_INT);
                        $command->bindParam(":attribute", $i, PDO::PARAM_INT);
                        $command->bindParam(":val", $y, PDO::PARAM_INT);
                        $command->bindParam(":status", $status, PDO::PARAM_INT);
                        $rs = $command->execute();
                    }
                }
            }

            if(count($rfi)>0){
                $rfi_type = '1';
                $sql = 'DELETE FROM rf_no_set WHERE program_id=:program_id and type=:type';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":program_id", $rf['program_id'], PDO::PARAM_INT);
                $command->bindParam(":type", $rfi_type, PDO::PARAM_STR);
                $command->execute();
                foreach($rfi as $i => $j){
                    foreach ($j as $x => $y){
                        $sub_sql = 'INSERT INTO rf_no_set(program_id,type,attribute,val,status) VALUES(:program_id,:type,:attribute,:val,:status)';
                        $command = Yii::app()->db->createCommand($sub_sql);
                        $command->bindParam(":program_id", $rf['program_id'], PDO::PARAM_INT);
                        $command->bindParam(":type", $rfi_type, PDO::PARAM_INT);
                        $command->bindParam(":attribute", $i, PDO::PARAM_INT);
                        $command->bindParam(":val", $y, PDO::PARAM_INT);
                        $command->bindParam(":status", $status, PDO::PARAM_INT);
                        $rs = $command->execute();
                    }
                }
            }

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }

    //查询项目区域
    public static function regionList($program_id,$type){
        $pro_model = Program::model()->findByPk($program_id);
        $program_id = $pro_model->root_proid;
        $sql = "SELECT * FROM rf_no_set WHERE status=0 and program_id = '".$program_id."' and type = '".$type."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['attribute']][] = $row['val'];
            }
        }
        return $rs;
    }
}

<?php

/**
 * 项目位置图纸
 * @author Liumc
 */
class ProgramDrawing extends CActiveRecord {

    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_drawing';
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

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('sys_role', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_role', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
    }

    //添加到图纸库
    public static function addProgramDrawing($args) {
        $status = self::STATUS_NORMAL;
        $project_id = $args['project_id'];
        $operator_id = Yii::app()->user->id;
        $date = date('Y-m-d H:i:s',time());//获取精确时间
        $operator_id = Yii::app()->user->id;
        $upload = '/opt/www-nginx/web/filebase/data/block_attach/' .$project_id .'/';
        if (!file_exists($upload)) {
            umask(0000);
            @mkdir($upload, 0777, true);
        }

        if (file_exists($args['drawing_path'])) {
            $name = substr($args['drawing_path'], 30);
            $file_list = explode('.', $name);
            $file_name = $file_list[0];
            $file_type = $file_list[1];
            $upload_file = $upload . $name;
            //移动文件到指定目录下
            if (rename($args['drawing_path'], $upload_file)) {
                $r['msg'] = "Success moving";
                $r['status'] = 1;
                $r['refresh'] = true;
//                $sql = 'INSERT INTO bac_program_drawing(drawing_id,drawing_name,drawing_path,status,user_id,record_time) VALUES(:drawing_id,:drawing_name,:drawing_path,:status,:user_id,:record_time)';
//                $record_time = date('Y-m-d H:i:s', time());
//                $command = Yii::app()->db->createCommand($sql);
//                $command->bindParam(":drawing_name", $name, PDO::PARAM_STR);
//                $command->bindParam(":drawing_path", $upload_file, PDO::PARAM_STR);
//                $command->bindParam(":status", $status, PDO::PARAM_STR);
//                $command->bindParam(":user_id", $operator_id, PDO::PARAM_STR);
//                $command->bindParam(":record_time", $date, PDO::PARAM_STR);
//                $rs = $command->execute();

                $draw_model = new ProgramDrawing('create');
                $record_time = date('Y-m-d H:i:s');
                $draw_model->drawing_name = $name;
                $draw_model->drawing_path = $upload_file;
                $draw_model->status = '0';
                $draw_model->user_id = $operator_id;
                $draw_model->record_time = $record_time;
                $rs = $draw_model->save();
                $drawing_id = $draw_model->drawing_id;

                if ($rs) {
                    $r['msg'] = Yii::t('common', 'success_update');
                    $r['status'] = 1;
                    $r['refresh'] = true;
                    $r['drawing_id'] = $drawing_id;
                } else {
                    $r['msg'] = Yii::t('common', 'error_update');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                }
            } else {
                $r['msg'] = "Error moving";
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }


        return $r;
    }


}

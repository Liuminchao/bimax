<?php
class PdfToPngCommand extends CConsoleCommand
{
    //0,10 3-4 * * * php72 /opt/www-nginx/web/test/bimax/protected/yiic pdftopng bach
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    public function actionBatch($param1)
    {
        $project_id = $param1;

        if (!extension_loaded('imagick')) {
            return false;
        }

        $sql = "select * from bac_program_block_q where program_id=:program_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
        $rows = $command->queryAll();

        $upload = '/opt/www-nginx/web/filebase/data/block_attach/' .$project_id .'/';
        if (!file_exists($upload)) {
            umask(0000);
            @mkdir($upload, 0777, true);
        }

        foreach($rows as $i => $j){
            if (file_exists($j['drawing_tmp_path'])) {
                $name = substr($j['drawing_tmp_path'],30);
                $file_list = explode('.',$name);
                $file_name = $file_list[0];
                $file_type = $file_list[1];
                $upload_file = $upload.$name;
                //移动文件到指定目录下
                if (rename($j['drawing_tmp_path'],$upload_file)) {
                    $r['msg'] = "Success moving";
                    $r['status'] = 1;
                    $r['refresh'] = true;
                }else{
                    $r['msg'] = "Error moving";
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                if($file_type == 'pdf'){
                    $im = new \imagick();
                    $im->setResolution(180, 180); //设置分辨率 值越大分辨率越高
                    $im->setCompressionQuality(100);
                    $im->readImage($upload_file);
                    foreach ($im as $k => $v) {
                        $v->setImageFormat('png');
                        $fileName = $upload.$file_name.'_page_' . ($k + 1) . '.png';
                        if ($v->writeImage($fileName) == true) {
                            $return[] = $fileName;
                        }
                    }
                    $pic_str  = '[';
                    foreach($return as $key => $value){
                        $pic_str .= '"'.$value.'",';
                    }
                    $pic_str = substr($pic_str,0,  strlen($pic_str)-1);
                    $pic_str .= ']';
                }else{
                    $pic_str  = '[';
                    $pic_str .= '"'.$upload_file.'",';
                    $pic_str .= ']';
                }


                $draw_model = new ProgramDrawing('create');
                $record_time = date('Y-m-d H:i:s');
                $draw_model->drawing_name = $name;
                $draw_model->drawing_path = $upload_file;
                $draw_model->drawing_pic_list = $pic_str;
                $draw_model->status = '0';
                $draw_model->user_id = 'Admin';
                $draw_model->record_time = $record_time;
                $draw_model->save();
                $drawing_id = $draw_model->drawing_id;

                $block_model = ProgramRegion::model()->findByPk($j['id']);
                $block_model->drawing_id = $drawing_id;
                $block_model->save();
            }

        }
    }

    public function actionBatch2($param1,$param2){
        $drawing_id = $param1;
        $project_id = $param2;
        $drawing_model = ProgramDrawing::model()->findByPk($drawing_id);
        $drawing_path = $drawing_model->drawing_path;
        $upload = '/opt/www-nginx/web/filebase/data/block_attach/' .$project_id .'/';
        if (!file_exists($upload)) {
            umask(0000);
            @mkdir($upload, 0777, true);
        }
        if (file_exists($drawing_path)) {
            $drawing_name = $drawing_model->drawing_name;
            $file_list = explode('.',$drawing_name);
            $file_name = $file_list[0];
            $file_type = $file_list[1];

            if($file_type == 'pdf'){
                $im = new \imagick();
                $im->setResolution(120, 120); //设置分辨率 值越大分辨率越高
                $im->setCompressionQuality(100);
                $im->readImage($drawing_path);
                foreach ($im as $k => $v) {
                    $v->setImageFormat('png');
                    $fileName = $upload.$file_name.'_page_' . ($k + 1) . '.png';
                    if ($v->writeImage($fileName) == true) {
                        $return[] = $fileName;
                    }
                }
                $pic_str  = '[';
                foreach($return as $key => $value){
                    $pic_str .= '"'.$value.'",';
                }
                $pic_str = substr($pic_str,0,  strlen($pic_str)-1);
                $pic_str .= ']';
            }else{
                $pic_str  = '[';
                $pic_str .= '"'.$drawing_path.'"';
                $pic_str .= ']';
            }


            $drawing_model->drawing_pic_list = $pic_str;
            $drawing_model->save();
        }
    }
}
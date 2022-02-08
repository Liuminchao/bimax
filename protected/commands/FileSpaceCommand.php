
<?php
 class FileSpaceCommand extends CConsoleCommand{
    //php /opt/www-nginx/web/ctmgr/protected/yiic statistics test
     public function actionTest(){
     	echo 'hello world!';
     }
     public function actionUpdate(){
        $date = date("Y-m-d",strtotime("-1 day"));//获取当前时间
        //$date = '2018-01-24';
        //$statistics_date = '2018-01-25 03:00:00';
        $statistics_date = date('Y-m-d H:i:s',time());;//获取精确时间
        $date_sql = "select a.file_size,b.contractor_id as con_id from stats_date_app a,bac_program b where a.date = '".$date."' and a.root_proid = b.program_id ";
        $date_command = Yii::app()->db->createCommand($date_sql);
        $date_rows = $date_command->queryAll();
        $total_sql = "select * from stats_contractor_store";
        $total_command = Yii::app()->db->createCommand($total_sql);
        $total_rows = $total_command->queryAll();
        //main_cnt
        $main_sql = "select count(program_id) as cnt,contractor_id from bac_program where program_id = root_proid and status = 00 group by contractor_id";
        $command = Yii::app()->db->createCommand($main_sql);
        $main_rows = $command->queryAll();

        $file = array(
            'document' => array(
                '0' => '/opt/www-nginx/web/filebase/company/',//公司级文档
                '1' => '/opt/www-nginx/web/filebase/program/',//项目级文档
            ),
            'photo' => array(
                '0' => '/opt/www-nginx/web/filebase/data/staff/',//人员标签页图片，证书
                '1' => '/opt/www-nginx/web/filebase/data/face/',//人员头像
                '2' => '/opt/www-nginx/web/filebase/data/device/',//设备照片，证书
                '3' => '/opt/www-nginx/web/filebase/data/qrcode/',//人员，设备二维码
            ),
            'appupload' => array(
                '0' => '/opt/www-nginx/appupload',
            )
        );
        $contractor_list = Contractor::compList();//承包商列表
        $num = 0;
//        if(Yii::app()->fcache->get('filecache') != 'false'){
//            $data = Yii::app()->fcache->get('filecache');
//        }else {
        foreach ($contractor_list as $contractor_id => $contractor_name) {
            $data[$num]['contractor_id'] = $contractor_id;
            $data[$num]['flow_pic'] = 0;
            $data[$num]['attribute'] = 0;
            $data[$num]['document'] = 0;
            //人员各种证书
            $staff_cert_path = $file['photo'][0] . $contractor_id . '/';
            if (file_exists($staff_cert_path)) {
                $staff_cert_str = exec('du -s ' . $staff_cert_path);
                $staff_cert_ar = explode('/', $staff_cert_str);
                $staff_cert_size = $staff_cert_ar[0];
                $data[$num]['attribute'] += $staff_cert_size / 1024;
            }
            //人员头像
            $staff_face_path = $file['photo'][1] . $contractor_id . '/';
            if (file_exists($staff_face_path)) {
                $staff_face_str = exec('du -s ' . $staff_face_path);
                $staff_face_ar = explode('/', $staff_face_str);
                $staff_face_size = $staff_face_ar[0];
                $data[$num]['attribute'] += $staff_face_size / 1024;
            }
            //设备照片+设备证书
            $device_path = $file['photo'][2] . $contractor_id . '/';
            if (file_exists($device_path)) {
                $device_str = exec('du -s ' . $device_path);
                $device_ar = explode('/', $device_str);
                $device_size = $device_ar[0];
                $data[$num]['attribute'] += $device_size / 1024;
            }
            //二维码
            $qrcode_path = $file['photo'][3] . $contractor_id . '/';
            if (file_exists($qrcode_path)) {
                $qrcode_str = exec('du -s ' . $qrcode_path);
                $qrcode_ar = explode('/', $qrcode_str);
                $qrcode_size = $qrcode_ar[0];
                $data[$num]['attribute'] += $qrcode_size / 1024;
            }
            $data[$num]['attribute'] = round($data[$num]['attribute'], 2);
            //公司级文档
            $company_document = $file['document'][0] . $contractor_id . '/';
            if (file_exists($company_document)) {
                $company_str = exec('du -s ' . $company_document);
                $company_ar = explode('/', $company_str);
                $company_size = $company_ar[0];
                $data[$num]['document'] += $company_size / 1024;
            }
            //项目级文档
            $program_document_sql = "select sum(doc_size) as doc_size from bac_document where  type='4' and contractor_id = '" . $contractor_id . "' ";
            $command = Yii::app()->db->createCommand($program_document_sql);
            $program_document_rows = $command->queryAll();
            $program_document_sum = 0;
            //统计承包商下有几个总包项目
            foreach($main_rows as $m => $n){
                if($contractor_id == $n['contractor_id']){
                    $pro_cnt = $n['cnt'];
                }
            }

            $data[$num]['document'] += $program_document_rows[0]['doc_size']/1024;
            $data[$num]['document'] = round($data[$num]['document'], 2);
            $data[$num]['total_size'] = $data[$num]['flow_pic'] + $data[$num]['attribute'] + $data[$num]['document'];
            $data[$num]['max_size'] = $pro_cnt*2*1024+2*1024;
            $num++;
        }
        foreach($data as $num => $list){
            $tag=0;
            foreach($total_rows as $i =>$j){
                if($list['contractor_id'] == $j['contractor_id']){
                    $tag =1;
                    $total_size= $j['flow_pic_size']+$list['attribute']+$list['document'];
                    $stats_store =  StatsContractorStore::model()->findByPk($list['contractor_id']);
                    $update_sql = "update stats_contractor_store set attribute_size=:attribute,document_size=:document,flow_pic_size=:flow_pic_size,total_size=:total_size,max_size=:max_size,statistics_date=:statistics_date,record_time=:record_time where contractor_id =:contractor_id";
                    $update_command = Yii::app()->db->createCommand($update_sql);
                    $update_command->bindParam(":flow_pic_size", $j['flow_pic_size'], PDO::PARAM_INT);
                    $update_command->bindParam(":attribute", $list['attribute'], PDO::PARAM_INT);
                    $update_command->bindParam(":document",$list['document'], PDO::PARAM_INT);
                    $update_command->bindParam(":total_size",$total_size, PDO::PARAM_INT);
                    if($stats_store->max_size == 3){
                        //查询是否有总包项目,有的话赋值 没有的话保持原值
                        $proj_cnt = Program::model()->count('contractor_id=:contractor_id AND status=:status', array('contractor_id' => $list['contractor_id'],'status'=>Program::STATUS_NORMAL));
                        if($proj_cnt > 0){
                            $maxsize = 5*1024;
                            $update_command->bindParam(":max_size",$maxsize, PDO::PARAM_INT);
                        }else{
                            $update_command->bindParam(":max_size",$list['max_size'], PDO::PARAM_INT);
                        }
                    }else{
                        //保持原值
                        $update_command->bindParam(":max_size",$list['max_size'], PDO::PARAM_INT);
                    }
                    $update_command->bindParam(":statistics_date",$date, PDO::PARAM_STR);
                    $update_command->bindParam(":record_time",$statistics_date, PDO::PARAM_STR);
                    $update_command->bindParam(":contractor_id",$list['contractor_id'], PDO::PARAM_STR);
                    $rs = $update_command->execute();
                }
            }
            if($tag == 0){
                $flow_pic_size = 0;
                $max_size = $list['max_size'];
                $total_size= $list['attribute']+$list['document'];
                $insert_sql = 'INSERT INTO stats_contractor_store (contractor_id,flow_pic_size,attribute_size,document_size,total_size,max_size,statistics_date,record_time) VALUES (:contractor_id,:flow_pic_size,:attribute_size,:document_size,:total_size,:max_size,:statistics_date,:record_time)';
                $insert_command = Yii::app()->db->createCommand($insert_sql);
                $insert_command->bindParam(":contractor_id",$list['contractor_id'], PDO::PARAM_STR);
                $insert_command->bindParam(":flow_pic_size",$flow_pic_size, PDO::PARAM_STR);
                $insert_command->bindParam(":attribute_size", $list['attribute'], PDO::PARAM_STR);
                $insert_command->bindParam(":document_size",$list['document'], PDO::PARAM_STR);
                $insert_command->bindParam(":total_size",$total_size, PDO::PARAM_STR);
                //查询是否有总包项目,有的话赋值 没有的话保持原值
                $pro_cnt = Program::model()->count('contractor_id=:contractor_id AND status=:status', array('contractor_id' => $list['contractor_id'],'status'=>Program::STATUS_NORMAL));
                if($pro_cnt > 0){
                    $maxsize = 5*1024;
                    $insert_command->bindParam(":max_size",$max_size, PDO::PARAM_INT);
                }else{
                    $maxsize = 3*1024;
                    $insert_command->bindParam(":max_size",$max_size, PDO::PARAM_INT);
                }
                $insert_command->bindParam(":statistics_date",$date, PDO::PARAM_STR);
                $insert_command->bindParam(":record_time",$statistics_date, PDO::PARAM_STR);
                $rs = $insert_command->execute();
            }
            foreach($date_rows as $m =>$n){
                if($list['contractor_id'] == $n['con_id']){
                    $file_size = $n['file_size']/1024;
                    $file_size = round($file_size, 2);
                    $update_sql = "update stats_contractor_store set flow_pic_size=flow_pic_size+:flow_pic_size,total_size=total_size+:flow_pic_size,statistics_date=:statistics_date,record_time=:record_time where contractor_id =:contractor_id";
                    $update_command = Yii::app()->db->createCommand($update_sql);
                    $update_command->bindParam(":flow_pic_size", $file_size, PDO::PARAM_INT);
                    $update_command->bindParam(":statistics_date",$date, PDO::PARAM_STR);
                    $update_command->bindParam(":contractor_id",$list['contractor_id'], PDO::PARAM_STR);
                    $update_command->bindParam(":record_time",$statistics_date, PDO::PARAM_STR);
                    $rs = $update_command->execute();
                }
            }
        }
    echo 'Success Update';
    }
}

<!-- Timelime example  -->
<div class="row">
    <div class="col-md-12">
        <!-- The time line -->
        <div class="timeline">
            <!-- timeline item -->
            <?php
//                $detail_list = QaCheckDetail::detailList($check_id);
//                $num = 0;
//                foreach($detail_list as $i => $j){
//                    $num++;
//                    $user_model = Staff::model()->findByPk($j['user_id']);
//                    $user_name = $user_model->user_name;
//                    $record_time = Utils::DateToEn($j['record_time']);
//                    $step = 'Step '.$num;
                $data = array(
                    'uid' => '861',
                    'token' => 'lalala',
                    'check_id' => $check_id,
                    'module' => 'inspection'
                );

                $post_data = json_encode($data);

                $url = "https://www.beehives.sg/cms_qa/dbapi?cmd=CMSC0303V2";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true); //post提交
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                // 3. 执行并获取HTML文档内容
                $output = curl_exec($ch);
                $rs = json_decode($output,true);
                foreach($rs['result']['detail'] as $i => $j){
                    $record_time = Utils::DateToEn($j['record_time']);
                    $to_list = $j['person_list_to'];
                    if(count($to_list)>0){
                        $to_user = 'To ';
                        foreach ($to_list as $to_index =>$to_val) {
                            $to_user.=  $to_val['user_name'].';';
                        }
                    }else{
                        $to_user = '';
                    }

                    $cc_list = $j['person_list_cc'];
                    if(count($cc_list)>0){
                        $cc_user = 'Cc ';
                        foreach ($cc_list as $cc_index =>$cc_val) {
                            $cc_user.=  $cc_val['user_name'].';';
                        }
                    }else{
                        $cc_user = '';
                    }
            ?>
            <div>
                <i class="fas fa-user bg-green"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock"></i><?php echo $record_time; ?></span>
                    <h3 class="timeline-header no-border"><a href="#"><?php echo $j['user_name']; ?></a> <?php echo $j['next_action_desc'] ?></h3>
                    <div class="timeline-body">
                        <?php echo $j['form_title'] ?><br><?php echo $to_user ?><br><?php echo $cc_user ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div>
                <i class="fas fa-clock bg-gray"></i>
            </div>
        </div>
    </div>
    <!-- /.col -->
</div>

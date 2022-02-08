<!-- Timelime example  -->
<div class="row">
    <div class="col-md-12">
        <!-- The time line -->
        <div class="timeline">
            <!-- timeline time label -->
<!--            <div class="time-label">-->
<!--                <span class="bg-blue">Approval Process</span>-->
<!--            </div>-->
            <!-- /.timeline-label -->
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
            );

            $post_data = json_encode($data);
            $detail_list = QaDefectDetail::dealList();
            $url = "https://www.beehives.sg/cms_qa/dbapi?cmd=CMSC0203";
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
//            var_dump($rs);
            foreach($rs['result']['detail'] as $i => $j){
                $record_time = Utils::DateToEn($j['record_time']);
                ?>
                <div>
                    <i class="fas fa-user bg-green"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i><?php echo $record_time; ?></span>
                        <h3 class="timeline-header no-border"><a href="#"><?php echo $j['user_name']; ?></a> <?php echo $detail_list[$j['deal_type']] ?></h3>
                        <div class="timeline-body">
                            <?php
                                if($j['pic'] != '' && $j['pic'] != '-1'){
                                    $pic_list = explode('|',$j['pic']);
                                    foreach($pic_list as $pic_index => $pic){
                                        $pic_array = explode('/',$pic);
                                        $pic_cnt = count($pic_array);
                                        //拆分每张图片，加入thumb_ 组成新地址
                                        $pic_str = '';
                                        $index = 0;
                                        foreach($pic_array as $self_index => $self){
                                            $index++;
                                            if($index == $pic_cnt){
                                                $pic_str.='thumb_'.$self;
                                            }else{
                                                $pic_str.=$self.'/';
                                            }
                                        }
                            ?>
                                        <img src="<?php echo $pic_str; ?>" style="cursor: pointer;" onclick="img('<?php echo $pic; ?>')">

                            <?php
                                    }
                                }
                            ?>
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
<script type="text/javascript">
    function img(pic_str) {
        window.open("https://www.beehives.sg"+pic_str,"_blank");
    }
</script>

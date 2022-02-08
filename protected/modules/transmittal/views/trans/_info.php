<link href="css/select2.css" rel="stylesheet" type="text/css" />
<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="background-color: #F2F9FA;">
                <form id="form1" >
                    <div class="form-group" style="margin-top: 30px;">
                        <label for="program_name" class="col-sm-1 offset-md-2 control-label padding-lr5" style="padding-top:7px;text-align: left">Form</label>
                        <div class="col-sm-3 padding-lr5">
                            <input  class="form-control"  value="HDB-Transmittal Form" readonly>
                            <input id="form_id" type="hidden" class="form-control" name="Trans[form_id]" value="F00001" >
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <label for="program_name" class="col-sm-1 offset-md-2 control-label padding-lr5" style="padding-top:7px;text-align: left">Project</label>
                        <div class="col-sm-3 padding-lr5">
                            <input value="<?php echo $trans_model->project_name; ?>" class="form-control" readonly>
                            <input id="program_id" value="<?php echo $program_id; ?>" class="form-control" type="hidden" name="Trans[program_id]">
                        </div>
                        <div class="col-sm-1 padding-lr5" >
                        </div>
                        <label for="program_name" class="col-sm-1 control-label padding-lr5" style="padding-top:7px;text-align: left">Project Nos</label>
                        <div class="col-sm-3 padding-lr5">
                            <input  class="form-control" id="project_nos" name="Trans[project_nos]" value="<?php echo $trans_model->project_nos;  ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group"  style="margin-top: 30px;">
                        <label for="program_name" class="col-sm-1 offset-md-2 control-label padding-lr5" style="text-align: left">To<span style="color: #c12e2a">*</span></label>
                        <div class="col-sm-8 padding-lr5">
                            <?php
                            $to_user = '';
                            foreach($user_list as $i => $j){
                                if($j['type'] == '1'){
                                    $user_model = Staff::model()->findByPk($j['user_id']);
                                    $user_name = $user_model->user_name;
                                    $to_user.=$user_name.' ';
                                }
                            }
                            echo $to_user;
                            ?>
                        </div>
                    </div>

                    <div class="form-group"  style="margin-top: 30px;">
                        <label for="program_name" class="col-sm-1 offset-md-2 control-label padding-lr5" style="text-align: left">Cc</label>
                        <div class="col-sm-8 padding-lr5">
                            <?php
                            $cc_user = '';
                            foreach($user_list as $i => $j){
                                if($j['type'] == '2'){
                                    $user_model = Staff::model()->findByPk($j['user_id']);
                                    $user_name = $user_model->user_name;
                                    $cc_user.=$user_name.' ';
                                }
                            }
                            echo $cc_user;
                            ?>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <label for="program_name" class="col-sm-1 offset-md-2 control-label padding-lr5" style="padding-top:7px;text-align: left">Subject<span style="color: #c12e2a">*</span></label>
                        <div class="col-sm-8 padding-lr5" style="text-align: left;">
                            <input id="subject" class="form-control" name="Trans[subject]"  type="text" value="<?php echo $trans_model->subject ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <label for="program_name" class="col-sm-1 offset-md-2 control-label padding-lr5" style="text-align: left">RVO<span style="color: #c12e2a">*</span></label>
                        <div class="col-sm-4 padding-lr5" style="margin-left: 15px;text-align: left;">
                            <?php
                            if($trans_model->rvo == '1' ){
                                ?>
                                <input type="radio" id="yes_rvo" name="rf[rvo]"  value="1"  checked disabled /> Yes
                                <input type="radio" id="no_rvo" name="rf[rvo]"  value="2" style="margin-left: 15px;" disabled /> No
                            <?php }else if($trans_model->rvo == '2'){ ?>
                                <input type="radio" id="yes_rvo" name="rf[rvo]"  value="1" disabled  /> Yes
                                <input type="radio" id="no_rvo" name="rf[rvo]"  value="2" style="margin-left: 15px;" checked disabled /> No
                            <?php }else{ ?>
                                <input type="radio" id="yes_rvo" name="rf[rvo]"  value="1"  disabled /> Yes
                                <input type="radio" id="no_rvo" name="rf[rvo]"  value="2" style="margin-left: 15px;" disabled /> No
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <label for="program_name" class="col-sm-1 offset-md-2 control-label padding-lr5" style="padding-top:7px;text-align: left">Attach</label>

                        <div class='col-sm-5 padding-lr5' style='padding-top:7px;text-align: left;'>
                            <?php
                            if(count($attach_list)>0){
                                echo "<button type='button' class='btn btn-primary' onclick='zip(\"$check_id\",\"$step\")'>
                                    Download Zip
                                </button>
                                <button type='button' class='btn btn-primary' onclick='save_all(\"$check_id\",\"$step\")'>
                                    Save as
                                </button>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <div class="offset-md-2 col-sm-9  padding-lr5">
                            <table id="orderTable" class="table-bordered" width="100%" align="center">
                                <thead>
                                <tr style="height: 32px;">
                                    <th style="width: 5%;text-align: center">S/N</th>
                                    <th style="width: 30%;text-align: center">Attach</th>
                                    <th style="width: 10%;text-align: center">Type</th>
                                    <th style="width: 20%;text-align: center">Purpose of Issue</th>
                                    <th style="text-align: center">Action</th>
                                </tr>
                                </thead>
                                <?php
                                $cnt = 0;
                                foreach($attach_list as $i => $j){
                                    $doc_name = $j['doc_name'];
                                    $doc_list = explode('.',$doc_name);
                                    $doc_type = $doc_list[1];
                                    $purpose = $purpose_list[$j['purpose']];
                                    $doc_path = $j['doc_path'];
                                    $cnt++;
                                    echo "<tr style='height: 32px;'>
                                            <td style='text-align: center'>$cnt</td>
                                            <td>$doc_name</td>
                                            <td style='text-align: center'>$doc_type</td>
                                            <td style='text-align: center'>$purpose</td>
                                            <td style='text-align: center'><a onclick='download_one(\"$doc_path\",\"$doc_name\")'  style='margin-left:10px;cursor:pointer'>Download</a><a onclick='save_one(\"$check_id\",\"$doc_path\")'  style='margin-left:10px;cursor:pointer'>Save as</a></td>
                                          </tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <label for="program_name" class="col-sm-1 offset-md-2 control-label padding-lr5" style="text-align: left">Remarks</label>
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <div class="offset-md-2 col-sm-9 padding-lr5">
                            <textarea rows="10" id="remark" name="Trans[remark]" style="width:100%" readonly><?php echo $detail_list[0]['remark'] ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 30px;margin-bottom: 100px;">
                        <?php
                        $operator_id = Yii::app()->user->id;
                        $user = Staff::userByPhone($operator_id);
                        $type = TransmittalUser::userListByRecord($check_id,$user[0]['user_id']);
                        $trans_model = TransmittalRecord::model()->findByPk($check_id);
                        $status = $trans_model->status;
                        ?>

                        <?php
                        if($status == '0' && $type == '1'){
                            ?>
                            <div class="col-sm-2 offset-md-2 control-label padding-lr5" style="text-align: center">
                                <button id="back_btn" type="button" class="btn btn-primary" style="background-color: #169BD5" onclick="back()" >Back</button>
                            </div>
                            <div class="col-sm-2 offset-md-5 control-label padding-lr5" style="text-align: center">
                                <button id="receive_btn" type="button" class="btn btn-primary" style="background-color: #169BD5" onclick="receive('<?php echo $check_id; ?>')" >Receive</button>
                            </div>
                            <?php
                        }else{
                            ?>
                                <div class="col-sm-2 offset-md-5 control-label padding-lr5" style="text-align: center">
                                    <button id="back_btn" type="button" class="btn btn-primary" style="background-color: #169BD5" onclick="back()" >Back</button>
                                </div>
                            <?php
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="form-group" style="margin-top: 10px;">
    <?php
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $root_model = Program::model()->findByPk($root_proid);
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        $user_model = Staff::model()->findByPk($user[0]['user_id']);
        $user_contractor_id = $user_model->contractor_id;
        $contractor_id = $root_model->contractor_id;
    ?>
</div>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/loading_upload.js"></script>
<script type="text/javascript" src="js/layui.js" ></script>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script type="text/JavaScript">
    $(document).ready(function() {

    })

    //下载
    function download_one (doc_path,doc_name) {
        window.location = "index.php?r=transmittal/trans/download&doc_path="+doc_path+"&doc_name="+doc_name;
    }
    //下载压缩包
    function zip (check_id,step) {
        window.location = "index.php?r=transmittal/trans/zip&check_id="+check_id+"&step="+step;
    }

    function save_all(check_id,step) {
        var diag = new Dialog();
        diag.Width = 930;
        diag.Height = 980;
        diag.Title = "DMS";
        diag.URL = "saveall&step="+step+"&check_id="+check_id+"&login_program_id=<?php echo $login_program_id; ?>";
        diag.show();
    }
    function save_one(check_id,doc_path) {
        var diag = new Dialog();
        diag.Width = 930;
        diag.Height = 980;
        diag.Title = "DMS";
        diag.URL = "saveone&path="+doc_path+"&check_id="+check_id+"&login_program_id=<?php echo $login_program_id; ?>";
        diag.show();
    }

    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['transmittal/trans/list']; ?>";
    }

    //接收
    function receive(check_id) {
        window.location = "index.php?r=transmittal/trans/receive&check_id="+check_id;
    }

    //添加表单其他元素
    function receive(check_id) {
        var program_id = $('#program_id').val();
        $.ajax({
            data: {check_id:check_id},
            url: "index.php?r=transmittal/trans/receive",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud();
            },
            success: function (data) {
                removecloud();//去遮罩
                if(data.status == '-1'){
                    // $('#msgbox').addClass('alert-danger fa-ban');
                    // $('#msginfo').html(data.msg);
                    // $('#msgbox').show();
                    layui.use('layer', function(){
                        layer.msg(data.msg); //提示
                    })
                }
                if(data.status == '1'){
                    // $('#msgbox').addClass('alert-success fa-ban');
                    // $('#msginfo').html(data.msg);
                    // $('#msgbox').show();
                    // alert('success');
                    layui.use('layer', function(){
                        layer.msg('success'); //提示
                    })
                    window.location = "index.php?r=transmittal/trans/list&project_id="+program_id;
                }
            },
            error: function () {
                removecloud();//去遮罩
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统超时');
                $('#msgbox').show();
            }
        });
    }

    //预览
    function previewdoc (path) {
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
        }
    }

</script>
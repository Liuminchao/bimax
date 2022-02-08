<style type="text/css">
    .none_input{
        border:0;​
    outline:medium;
    }
</style>
<div class="row" style="margin-left: -20px;">
    <div class="col-12">
        <div class="dataTables_length">
            <ul class="nav nav-tabs" role="tablist" id="myTab">
                <li role="presentation" class="nav-item"><a  class="nav-link active" href="#tab_1" role="tab" data-toggle="tab"> Revit</a></li>
                <li role="presentation" class="nav-item"><a  class="nav-link" href="#tab_2" role="tab" data-toggle="tab"> Input</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="box" style="margin-bottom: 5px;">
            <div class="row" style="margin-left: -20px;">
                <div class="col-12">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <table class="table table-hover level-tab" style="width: 100%">
                                <?php
                                $data = array(
                                    'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
                                    'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
                                );
                                foreach ($data as $key => $value) {
                                    $post_data[$key] = $value;
                                }
                                //        $data = json_encode($post_data);
                                $url = "https://bim.cmstech.sg/api/v1/token";
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_POST, true); //post提交
                                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                                // 3. 执行并获取HTML文档内容
                                $output = curl_exec($ch);
                                $rs = json_decode($output,true);
                                $arr = array(
                                    'x-access-token:'.$rs['data']['token']
                                );
                                $url = "https://bim.cmstech.sg/api/v1/entity/$guid?modelId=".$model_id."&version=".$version;

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
                                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                                //跳过SSL验证
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
                                // 3. 执行并获取HTML文档内容
                                $output = curl_exec($ch);
                                $r = json_decode($output,true);
                                ?>

                                <?php
                                $data = $r['data'];
                                echo "<tr><td><input type='radio' name='info' value=''></td><td></td><td></td></tr>";
                                foreach($data as $k => $v){
                                    if($k == 'properties'){
                                        foreach ($v as $i => $j){
                                            echo "<tr><td colspan='3' align='center'>".$j['group']."</td></tr>";
                                            echo "<tr><td><input type='radio' name='info' value='".$j['key']."'></td><td>".$j['key']."</td><td>".$j['value']."</td></tr>";
                                        }
                                    }else{
                                        echo "<tr><td><input type='radio' name='info' value='$k'></td><td>$k</td><td>$v</td></tr>";
                                    }
                                }
                                ?>
                            </table>
                            <div class="row">
                                <div class="col-12"  style="text-align: center">
                                    <button class="btn btn-primary btn-sm" onclick="save_1('<?php echo $type; ?>')">Save</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <?php
                            $pbu_info = RevitComponent::pbuInfo($program_id,$guid);
                            if(count($pbu_info)>0){
                                $unit_no = $pbu_info[0]['unit_nos'];
                                $block = $pbu_info[0]['block'];
                                $pbu_type = $pbu_info[0]['pbu_type'];
                                $part = $pbu_info[0]['part'];
                            }else{
                                $unit_no = '';
                                $block = '';
                                $pbu_type = '';
                                $part = '';
                            }
                            ?>
                            <table id="orderTable" style="margin-top: 5px;" class="table-bordered" width="100%" align="center">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th style="width: 30%;text-align: center">Key</th>
                                    <th style="width: 10%;text-align: center">Value</th>
                                    <!--                <th style="text-align: center">Action</th>-->
                                </tr>
                                </thead>
                                <tr id="row0">
                                    <td align="center" width="10%">
                                        <input type='radio' name='info' value='unit_nos'>
                                    </td>
                                    <td align="center" width="30%">
                                        <input type="text"  class="form-control none_input" readonly value="Unit No." style="width:90%"/>
                                    </td>
                                    <td align="center" width="60%">
                                        <input type="text" id="unit_no" class="form-control none_input" readonly name="Model[unit_no]" value="<?php echo $unit_no ?>"  style="width:90%"/>
                                    </td>
                                </tr>
                                <tr id="row1">
                                    <td align="center" >
                                        <input type='radio' name='info' value='block'>
                                    </td>
                                    <td align="center" width="30%">
                                        <input type="text"  class="form-control none_input" readonly value="Block" style="width:90%"/>
                                    </td>
                                    <td align="center" width="70%">
                                        <input type="text" id="block" class="form-control none_input" readonly name="Model[block]" value="<?php echo $block ?>" style="width:90%"/>
                                    </td>
                                </tr>
                                <tr id="row2">
                                    <td align="center" >
                                        <input type='radio' name='info' value='pbu_type'>
                                    </td>
                                    <td align="center">
                                        <input type="text"  class="form-control none_input" readonly value="Type" style="width:90%"/>
                                    </td>
                                    <td align="center">
                                        <input type="text" id="pbu_type" class="form-control none_input" readonly name="Model[pbu_type]"  value="<?php echo $pbu_type ?>"  style="width:90%"/>
                                    </td>
                                </tr>
                                <tr id="row3">
                                    <td align="center" >
                                        <input type='radio' name='info' value='part'>
                                    </td>
                                    <td align="center">
                                        <input type="text"  class="form-control none_input" readonly value="Part" style="width:90%"/>
                                    </td>
                                    <td align="center">
                                        <input type="text" id="part" class="form-control none_input" readonly name="Model[part]" value="<?php echo $part ?>"  style="width:90%"/>
                                    </td>
                                </tr>
                            </table>
                            <div class="row" style="margin-top: 5px;">
                                <div class="col-12" style="text-align: center">
                                    <button class="btn btn-primary btn-sm" onclick="save_2('<?php echo $type; ?>')">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        // var t = window.devicePixelRatio   // 获取下载的缩放 125% -> 1.25    150% -> 1.5
        // document.write('您的显示器分辨率为:\n' + screen.width + '*' + screen.height + ' pixels<br/>');
        // var w1cm = document.getElementById("hutia").offsetWidth, w = screen.width/w1cm, h = screen.height/w1cm, r = Math.round(Math.sqrt(w*w + h*h) / 2.54);
        // document.write('您的显示器尺寸为:\n' + (screen.width/w1cm).toFixed(1) + '*' + (screen.height/w1cm).toFixed(1) + ' cm, '+ r +'寸<br/>');
        // alert(t);
        // document.getElementById("content-header").style.display="none";//隐藏
        // $("#weatherType").selectpicker('refresh');
        $('#tab_1 a').click(function (e) {
            console.log($(this).context);
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
            var tab_text = $(this).context.text;
            console.log(tab_text);
        })
    })
    var save_1 = function (type) {
        var res = $('input[name=info]:checked').val();
        if(res){
            window.parent.document.getElementById("set_"+type).innerText = res;
        }else {
            window.parent.document.getElementById("set_"+type).innerText = '-';
        }
        window.parent.document.getElementById("val_"+type).value = res;
        $("#modal-close").click();
    }

    var save_2 = function (type) {
        var res = $('input[name=info]:checked').val();
        if(res){
            window.parent.document.getElementById("set_"+type).innerText = res;
        }else {
            window.parent.document.getElementById("set_"+type).innerText = '-';
        }
        window.parent.document.getElementById("val_"+type).value = res;
        $("#modal-close").click();
    }
</script>
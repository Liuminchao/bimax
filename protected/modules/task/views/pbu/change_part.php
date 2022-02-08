<div class="row">
    <div class="col-12">
        <div class="card card-info " id="part_info">
            <div class="row" style="margin-top: 10px;">
                <div class="col-8" style="text-align: center">
                </div>
                <div class="col-2" style="text-align: center" >
                </div>
                <div class="col-2" style="text-align: center" >
                    <?php
                    $part_list = ProgramBlockChart::locationPart($project_id,$block,$pbu_tag);
                    if(count($part_list)>0){
                        $cnt = count($part_list);
                        if($cnt <= 5){
                            $cnt = 5;
                        }
                    }else{
                        $cnt = 5;
                    }
                    ?>
                    <input type="hidden" id="part_cnt" value="<?php echo $cnt; ?>">
                    <input type="hidden" id="block" value="<?php echo $block; ?>">
                    <input type="hidden" id="project_id" value="<?php echo $project_id; ?>">
                    <input type="hidden" id="pbu_tag" value="<?php echo $pbu_tag; ?>">
                    <button class="btn btn-primary btn-sm" onclick="itemAdd()">Add</button>
                </div>
            </div>
            <div class="row">
                <div class="col-4" style="text-align: center">
                    Sequence
                </div>
                <div class="col-4" style='text-align: center'>
                    Name
                </div>
                <div class="col-4" style="text-align: center">
                    Action
                </div>
            </div>
            <?php
            $part_list = ProgramBlockChart::locationPart($project_id,$block,$pbu_tag);
            if(count($part_list)>0){
                $index = 0;
                foreach($part_list as $x => $y){
                    $index++;
                    $part = $y['part'];
                    echo "<div class='row' style='margin-top: 5px;margin-bottom: 5px;'>";
                    echo "<div class='col-4' style='text-align: center'>$index</div>";
                    echo "<div class='col-4'><input type='text' id='part_$index' class='form-control input-sm' name='Part[]' value='".$part."'></div>";
                    echo "<div class='col-4' style='text-align: center'><button class=\"btn btn-primary btn-sm\" onclick=\"itemAssign('$block','$project_id','$index')\">Assign</button></div>";
                    echo "</div>";
                }
                $index++;
                for($i=$index;$i<=5;$i++){
                    echo "<div class='row' style='margin-top: 5px;margin-bottom: 5px;'>";
                    echo "<div class='col-4' style='text-align: center'>$i</div>";
                    echo "<div class='col-4' style='text-align: center'><input type='text' id='part_$i' class='form-control input-sm' name='Part[]' ></div>";
                    echo "<div class='col-4' style='text-align: center'><button class=\"btn btn-primary btn-sm\" onclick=\"itemAssign('$block','$project_id','$i')\">Assign</button></div>";
                    echo "</div>";
                }
            }else{
                for($i=1;$i<=5;$i++){
                    echo "<div class='row' style='margin-top: 5px;margin-bottom: 5px;'>";
                    echo "<div class='col-4' style='text-align: center'>$i</div>";
                    echo "<div class='col-4' style='text-align: center'><input type='text' id='part_$i' class='form-control input-sm' name='Part[]' ></div>";
                    echo "<div class='col-4' style='text-align: center'><button class=\"btn btn-primary btn-sm\" onclick=\"itemAssign('$block','$project_id','$i')\">Assign</button></div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var itemAssign = function (block,project_id,part_index) {
        var part = $('#part_'+part_index).val();
        var pbu_tag = $('#pbu_tag').val();
        if(part){
            var modal = new TBModal();
            modal.title = "Zone:"+part;
            modal.url = "index.php?r=task/pbu/assign&block=" + block+"&project_id="+project_id+"&part="+part+"&pbu_tag="+pbu_tag;
            modal.modal();
        }else{
            alert('Please input zone');
        }
    }
    var itemAdd = function(){
        var part_cnt = $('#part_cnt').val();
        var part_cnt = parseInt(part_cnt)+1;
        var project_id = $('#project_id').val();
        var block = $('#block').val();
        var part_str = '<div class="row" style="margin-top: 5px;margin-bottom: 5px;"><div class="col-4" style="text-align: center">'+part_cnt+'</div><div class="col-4" style="text-align: center"><input type="text" id="part_'+part_cnt+'" class="form-control input-sm" name="Part[]"></div><div class="col-4" style="text-align: center"><button class="btn btn-primary btn-sm" onclick="itemAssign('+block+','+project_id+','+part_cnt+')">Assign</button></div></div>';
        $('#part_info').append(part_str);
    }
</script>
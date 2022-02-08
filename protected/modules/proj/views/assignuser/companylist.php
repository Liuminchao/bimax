
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <div class="col-sm-6 padding-lr5">
                <select  id="select_option" class="form-control" style="background: #F2F2F2;" >
                    <?php  foreach($program_list as $i => $j){ ?>
                        <option value="<?php echo $j['program_id'] ?>"><?php echo $company_list[$j['contractor_id']] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row" style="margin-top: 5px;">
    <div class="col-12" style="text-align: center">
        <button class="btn btn-primary" type='button' onclick="submit()">Confirm</button>
    </div>
</div>
<script type="text/javascript">
    var submit =  function(){
        //获取select中的ID
        var selectId = document.getElementById("select_option");
        var index=selectId.selectedIndex;
        var id = selectId.options[index].value;
//        alert(type);
        window.location = "index.php?r=proj/assignuser/userapply&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" +id+"&program_id=<?php echo $program_id ?>";
    }
</script>

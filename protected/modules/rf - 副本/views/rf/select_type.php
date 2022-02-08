
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <div class="col-sm-6 padding-lr5">
                <select  id="select_option" class="form-control" style="background: #F2F2F2;" >
                    <option value="1">RFI</option>
                    <option value="2">RFA</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="working_life" style="word-wrap:break-word;word-break:break-all;"
                   class="col-sm-6 control-label padding-lr5"><?php echo $list['doc_name'];?></label>
            <div class="col-sm-6 padding-lr5">
                <button class="btn btn-default" type='button' onclick="submit('<?php echo $program_id ?>')">Compose</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var submit =  function(id){
        //获取select中的ID
        var selectId = document.getElementById("select_option");
        var index=selectId.selectedIndex;
        var type = selectId.options[index].value;
//        alert(type);
        window.location = "index.php?r=rf/rf/addrfichat&program_id="+id+"&type="+type;
    }
</script>

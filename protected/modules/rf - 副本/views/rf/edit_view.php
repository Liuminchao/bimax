<!doctype html>
<html>
<head>
    <title>Test</title>
    <meta charset="utf-8">
</head>
<style type="text/css">
    body { background: #ffffff;}
</style>
<body >
<div id="WindJS" style="position: relative;background: white;">
    <fieldset >
        <legend>Model</legend>
        <select id="modellist">
        </select>
        <select id="setLeftMouseOperation"> </select>
        <!-- 打开模型 -->
        <button id="openModelData">Open</button>
        <!-- 关闭模型 -->
        <button id="closeModelDatas">Close</button>
        <!-- 复位 -->
        <button id="revertHomePosition">Reset</button>
        <input type="hidden" value="">
        <?php $view_list = RfModelView::dealList($check_id,$step); ?>

        <input type="hidden" id="model_id" value="<?php  echo $view_list[0]['model_id'] ?>">
        <input type="hidden" id="version" value="<?php  echo $view_list[0]['version'] ?>">
        <input type="hidden" id="view" value="<?php  echo $view_list[0]['view'] ?>">
        <input type="hidden" id="program_id" value="<?php echo $program_id ?>">
        <!-- 保存视口 -->
        <button id="saveViewState">Save</button>
        <!--        <input id="loadViewState" type="file">-->
    </fieldset>
    <canvas id="View" style="top:0;left:0;height:490px;width:775px;"></canvas>
<!--    <fieldset>-->
<!--        <legend>视图</legend>-->
<!--        <button id="screenshot">截图</button>-->
<!--        <button id="cubeSectionSwitch">剖切开关</button>-->
<!--        <button id="cubeSectionShowHide">剖切显隐</button>-->
<!--        <button id="resetCubeSection">剖切重置</button>-->
<!--        <button id="measureSwitch">测量开关</button>-->
<!--        <select id="measureTypeList"> </select>-->
<!--    </fieldset>-->
<!--    <fieldset>-->
<!--        <legend>漫游</legend>-->
<!---->
<!---->
<!--        <button id="zoomInPosition">放大</button>-->
<!--        <button id="zoomOutPosition">缩小</button>-->
<!--        <button id="locateSelectEntities">定位选中</button>-->
<!--    </fieldset>-->
<!--    <img id="screenshotView" style="top:0;left:0;height:200px;width:300px;">-->
</div>
<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/WIND.js"></script>
<script type="text/javascript" src="js/model_view.js"></script>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
</body>
</html>
<script type="text/javascript">
    window.onload = function () {
        let model_id = $('#model_id').val();
        let view = $('#model_view').val();
        let version = $('#version').val();
//        alert(view);
        if(!sessionStorage.getItem("model_view_id")){
            sessionStorage.setItem("model_view_id", model_id);
        }
        if(!sessionStorage.getItem("view")){
            sessionStorage.setItem("view", view);
        }
        if(!sessionStorage.getItem("version")){
            sessionStorage.setItem("version", version);
        }
    }
</script>
<!doctype html>
<html>
<head>
    <title>Test</title>
    <meta charset="utf-8">
</head>

<body>
<div id="WindJS" style="position: relative">
    <fieldset>
        <legend>Model</legend>
        <select id="modellist">
        </select>
        <button id="openModelData">打开模型</button>
        <button id="closeModelData">关闭模型</button>
        <button id="closeModelDatas">关闭全部模型</button>
        <button id="getEntityParameter">获取实体属性</button>
        <button id="isEntityExist">判断实体存在</button>
        <button id="getRelationRelParamter">获取关联构件属性</button>
        <button id="getAllComponentParamter">获取所有构件属性</button>
        <button id="getAllRelationParamter">获取所有关系属性</button>
        <button id="getAllStoreyParameter">获取所有楼层属性</button>
    </fieldset>
    <canvas id="View" style="top:0;left:0;height:400px;width:600px;"></canvas>
    <fieldset>
        <legend>视图</legend>
        <!--<button id="bindWINDData">视图绑定数据</button>-->
        <button id="screenshot">截图</button>
        <button id="saveViewState">保存视口</button>
        <input id="loadViewState" type="file">
        <button id="navigateAssistSwitch">导航开关</button>
        <button id="cubeSectionSwitch">剖切开关</button>
        <button id="cubeSectionShowHide">剖切显隐</button>
        <button id="resetCubeSection">剖切重置</button>
        <button id="measureSwitch">测量开关</button>
        <select id="measureTypeList"> </select>
        <button id="viewTest">测试</button>
    </fieldset>
    <fieldset>
        <legend>漫游</legend>
        <select id="setLeftMouseOperation"> </select>
        <button id="personRoamingSwitch">人称开关</button>
        <label><input id = "thirdPersonSwitch" type = "checkbox"/>第三人称</label>
        <label><input id = "gravityFallSwitch" type = "checkbox"/>重力下落</label>
        <label><input id = "collisionDetectSwitch" type = "checkbox"/>碰撞检测</label>
        <button id="revertHomePosition">复位</button>
        <button id="zoomInPosition">放大</button>
        <button id="zoomOutPosition">缩小</button>
        <button id="locateSelectEntities">定位选中</button>
        <button id="locateAssignEntities">定位指定</button>
        <button id="getSelectEntities">获取选中</button>
    </fieldset>
    <fieldset>
        <legend>控制</legend>
        <button id="highlightEntities">高亮指定</button>
        <button id="unhighlightEntities">取消高亮</button>
        <button id="transparentComponents">透明全部</button>
        <button id="untransparentComponents">取消透明</button>

        <button id="showComponents">全显示构件</button>
        <button id="showEachComponents">显示构件</button>
        <button id="hideEachComponents">隐藏构件</button>
        <button id="hideSelectEntities">隐藏选中</button>
        <button id="hideUnselectEntities">隔离选中</button>
        <button id="showAssignEntities">显示指定</button>
        <button id="hideAssignEntities">隐藏指定</button>
        <br>

        BatchID:<input id="BatchID" type="text"/>
        <button id="setBatchEntities">设置批量</button>
        <button id="removeBatchEntities">移除批量</button>
        <button id="coloringBatchEntities">批量上色</button>
        <button id="discoloringBatchEntities">批量去色</button>
        <button id="highlightBatchEntities">批量高亮</button>
        <button id="unhighlightBatchEntities">批量去亮</button>
        <button id="transparentBatchEntities">批量透明</button>
        <button id="untransparentBatchEntities">批量去透</button>
        <br>

        TreeID:<input id="TreeID" type="text"/>
        <button id="showTreeRoot">显示树根</button>
        <button id="hideTreeRoot">隐藏树根</button>
        <button id="displayTreeNode">显隐树节点</button>
        <button id="hightlightTreeNode">高亮树节点</button>
    </fieldset>
    <fieldset>
        <legend>设置</legend>
        Red:<input id="colorRed" type="text"/>
        Green:<input id="colorGreen" type="text"/>
        Blue:<input id="colorBlue" type="text"/>
        Alpha:<input id="colorAlpha" type="text"/><br>
        <button id="setBackgroundColor">设置背景颜色</button>
        <button id="getBackgroundColor">获取背景颜色</button>
        <button id="resetBackgroundColor">重置背景颜色</button>
        <button id="setTransparency">设置透明度</button>
        <button id="revertTransparency">重置透明度</button>
    </fieldset>
    <img id="screenshotView" style="top:0;left:0;height:200px;width:300px;">
</div>
<script type="text/javascript" src="js/WINDTEST.js"></script>
</body>
</html>
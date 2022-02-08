let WINDData = WIND.WINDData;
let WINDView = WIND.WINDView;
let ViewStateType = WIND.ViewStateType;
let LeftMouseOperation = WIND.LeftMouseOperation;
let TreeRuleType = WIND.TreeRuleType;
let MeasureType = WIND.MeasureType;
let setLanguageType = WIND.setLanguageType;
let CallbackType = WIND.CallbackType;

//WINDData初始化
let config = {};
// config.serverIp = 'https://engine.everybim.net';//译筑测试公有云，仅限测试使用
config.serverIp = 'https://bim.cmstech.sg';
// config.appKey = 'ZX7sOit3IEbMvQxwBOhfRIQBRvjmNHq38FP6';
config.appKey = 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT';
// config.appSecret = 'ef1ae3c72df0cfad9ff0fdf81b5e2a36e1aed60b521824beb0e46ad180d2760a';
config.appSecret = '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f';
let data = new WINDData(config);
let loadCallback = function (type, value) {//模型加载百分比回调
    console.log('load:' + value);
};
data.addWINDDataCallback(1, loadCallback);

//获取当前服务器包含的模型列表
let modeldata = new Map();

//WINDView初始化
let canvas = document.getElementById("View");
let view = new WINDView(canvas);
view.bindWINDData(data);//将View与一个Data绑定

//页面加载时初始化ui事件
window.addEventListener('load', onLoad, true);
async function onLoad() {
    let model_id = $('#model_component_id').val();
    let version = $('#model_component_version').val();
    let uuid = $('#uuid').val();
    let entityId = $('#entityId').val();
    // alert(model_id);
    // alert(uuid);
    if(!model_id){
        model_id = sessionStorage.getItem("model_component_id");
    }
    if(!version){
        version = sessionStorage.getItem("model_component_version");
    }
    if(!uuid){
        uuid = sessionStorage.getItem("uuid");
    }
    if (model_id) {
        await data.getWINDDataLoader().openModelData(model_id,version);//打开对应模型id的模型数据
        // await data.getWINDDataLoader().openModelData(model._id);//打开对应模型id的模型数据
    }
    if(uuid){
        arr = uuid.split(',');
        view.getWINDViewControl().highlightEntities(arr);
    }

    // highlightEntities();
}

function initDataUI() {
    let openModelDataUI = document.getElementById("openModelData");
    openModelDataUI.addEventListener("click", openModelData, false);

    let closeModelDatasUI = document.getElementById("closeModelDatas");
    closeModelDatasUI.addEventListener("click", closeModelDatas, false);

    // let getAllComponentParamterUI = document.getElementById("getAllComponentParamter");
    // getAllComponentParamterUI.addEventListener("click", getAllComponentParamter, false);
    //
    // let getAllStoreyParameterUI = document.getElementById("getAllStoreyParameter");
    // getAllStoreyParameterUI.addEventListener("click", getAllStoreyParameter, false);
    //
    let getEntityParameterUI = document.getElementById("save");
    getEntityParameterUI.addEventListener("click", getEntityParameter, false);
}

async function openCurrentModelData(modeldata) {
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    console.log(model);
    if (model) {
        await data.getWINDDataLoader().openModelData(model._id,model._version);//打开对应模型id的模型数据
    }
}

async function openModelData() {
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    console.log(model);
    if (model) {
        await data.getWINDDataLoader().openModelData(model._id,model._version);//打开对应模型id的模型数据
    }
}


function closeModelDatas() {
    data.getWINDDataLoader().closeAllModelDatas();
}

async function getAllComponentParamter() {
    console.log(await data.getWINDDataQuerier().getAllComponentParameterL());
    let msg = JSON.stringify(await data.getWINDDataQuerier().getAllComponentParameterL());
    $("#info").html( msg );
    // alert(msg);
}

async function getAllStoreyParameter() {
    console.log(await data.getWINDDataQuerier().getAllStoreyParameterL());
}

async function getSelectEntities() {
    console.log(await view.getWINDViewRoaming().getSelectEntities());
    let entity = await view.getWINDViewRoaming().getSelectEntities();
}



async function getEntityParameter() {
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    // console.log(entity);
    var entityId = '';
    var model_id = '';
    var uuid = '';
    var model_name = '';
    for ( var i = 0; i <entity.length; i++){
        entity_info = await data.getWINDDataQuerier().getEntityParameterL(entity[i]);
        console.log(entity_info);
        entityId = entityId + entity_info.entityId + ',';
        uuid = uuid + entity_info.uuid + ',';
        model_id = entity_info.modelId;
        model_name = entity_info.model;
    }
    entityId = entityId.substring(0, entityId.lastIndexOf(','));
    uuid = uuid.substring(0, uuid.lastIndexOf(','));
    console.log(model_id);
    console.log(entityId);
    console.log(uuid);
    console.log(model_name);
    sessionStorage.setItem("model_component_id", model_id);
    sessionStorage.setItem("model_component_name", model_name);
    sessionStorage.setItem("entityId", entityId);
    sessionStorage.setItem("uuid", uuid);
    alert('Save Success');
    // document.getElementById("model_id").value = model_id;
    // document.getElementById("entityId").value = entityId;
    // document.getElementById("uuid").value = uuid;
    // document.getElementById("model").value = model_name;
}


//视图
function initViewUI() {

    let cubeSectionSwitchUI = document.getElementById("cubeSectionSwitch");
    cubeSectionSwitchUI.addEventListener("click", cubeSectionSwitch, false);

    let cubeSectionShowHideUI = document.getElementById("cubeSectionShowHide");
    cubeSectionShowHideUI.addEventListener("click", cubeSectionShowHide, false);

    let resetCubeSectionUI = document.getElementById("resetCubeSection");
    resetCubeSectionUI.addEventListener("click", resetCubeSection, false);

    let measureSwitchUI = document.getElementById("measureSwitch");
    measureSwitchUI.addEventListener("click", measureSwitch, false);

    let measureTypeListUI = document.getElementById("measureTypeList");
    measureTypeListUI.add(new Option('点到点', 'dot'));
    measureTypeListUI.add(new Option('净距', 'distance'));
    measureTypeListUI.add(new Option('角度', 'angle'));
    measureTypeListUI.add(new Option('长度', 'length'));
    measureTypeListUI.add(new Option('查看', 'view'));
    measureTypeListUI.addEventListener("change", function (event) {
        measureTypeListUpdate(event.target.value)
    }, false);

    //添加视图回调
    view.addWINDViewCallback('callback', callback);
}
function measureTypeListUpdate(value) {
    if (value === 'dot') {
        view.getWINDViewMeasure().setMeasureType(MeasureType.DOT);
    } else if (value === 'distance') {
        view.getWINDViewMeasure().setMeasureType(MeasureType.DISTANCE);
    } else if (value === 'angle') {
        view.getWINDViewMeasure().setMeasureType(MeasureType.ANGLE);
    } else if (value === 'length') {
        view.getWINDViewMeasure().setMeasureType(MeasureType.LENGTH);
    } else if (value === 'view') {
        view.getWINDViewMeasure().setMeasureType(MeasureType.VIEW);
    }
}
function callback(type, result) {
    if (type === CallbackType.ROAMINGSTATE_CHANGED) {
        //result._personRoamingOpened;
        document.getElementById("thirdPersonSwitch").checked = result._thirdPersonOpened;
        document.getElementById("gravityFallSwitch").checked = result._gravityFallOpened;
        document.getElementById("collisionDetectSwitch").checked = result._collisionDectectOpened;
    }
}

function cubeSectionSwitch() {
    let state = view.getWINDViewSection().getSectionState();
    if (state._cubeSectionOpened) {
        view.getWINDViewSection().closeCubeSection();
    } else {
        view.getWINDViewSection().openCubeSection();
    }
}

function cubeSectionShowHide() {
    let state = view.getWINDViewSection().getSectionState();
    if (state._cubeSectionShowed) {
        view.getWINDViewSection().hideCubeSection();
    } else {
        view.getWINDViewSection().showCubeSection();
    }
}

function resetCubeSection() {
    view.getWINDViewSection().resetCubeSection();
}

function measureSwitch() {
    let state = view.getWINDViewMeasure().getMeasureState();
    if (state._measureOpened) {
        view.getWINDViewMeasure().closeMeasure();
    } else {
        view.getWINDViewMeasure().openMeasure();
    }
}

function saveViewState() {
    let state = view.getWINDViewSection().getSectionState();
    console.log(state);
    console.log(ViewStateType.ALL);
    let t = view.saveWINDViewState(ViewStateType.ALL);
    console.log(t);
    if(t){
        var e=document.createElement("a");
        r=new Blob([t],{type:"text/plain"});
        e.href=window.URL.createObjectURL(r);
        e.download="saveviewState",e.click();
        var oReq = new XMLHttpRequest();
        oReq.open("POST", 'entity.php', true);
        oReq.onload = function (oEvent) {
            // Uploaded.
        };
        // var blob = new Blob(['abc123'], {type: 'text/plain'});
        oReq.send(r);
    }
}

//漫游
function initViewRoamingUI() {
    // let saveUI = document.getElementById("save");
    // saveUI.addEventListener("click", submit, false);
    // let saveViewStateUI = document.getElementById("saveViewState");
    // saveViewStateUI.addEventListener("click", saveViewState, false);
    // document.getElementById("loadViewState").onchange=function(){
    //     this.files.length;
    //     var t=this.files[0];
    //     if(t){
    //         var e=new FileReader;
    //         e.onload=function(t){
    //             view.loadWINDViewState(t.target.result)};
    //             e.readAsArrayBuffer(t);
    //     }
    // }
    // let screenshotUI = document.getElementById("screenshot");
    // screenshotUI.addEventListener("click", screenshot, false);
    let setLeftMouseOperationUI = document.getElementById("setLeftMouseOperation");
    setLeftMouseOperationUI.add(new Option('点选', 'pick'));
    setLeftMouseOperationUI.add(new Option('旋转', 'rotate'));
    setLeftMouseOperationUI.add(new Option('平移', 'pan'));
    setLeftMouseOperationUI.addEventListener("change", function (event) {
        leftmouseOperationUpdate(event.target.value)
    }, false);

    let revertHomePositionUI = document.getElementById("revertHomePosition");
    revertHomePositionUI.addEventListener("click", revertHomePosition, false);
    //
    // let zoomInPositionUI = document.getElementById("zoomInPosition");
    // zoomInPositionUI.addEventListener("click", zoomInPosition, false);
    //
    // let zoomOutPositionUI = document.getElementById("zoomOutPosition");
    // zoomOutPositionUI.addEventListener("click", zoomOutPosition, false);
    //
    // let locateSelectEntitiesUI = document.getElementById("locateSelectEntities");
    // locateSelectEntitiesUI.addEventListener("click", locateSelectEntities, false);
    //
    // let getSelectEntitiesUI = document.getElementById("getSelectEntities");
    // getSelectEntitiesUI.addEventListener("click", getSelectEntities, false);
    //
    // let highlightEntitiesUI = document.getElementById("highlightEntities");
    // highlightEntitiesUI.addEventListener("click", highlightEntities, false);
}

function leftmouseOperationUpdate(value) {
    if (value === 'pick') {
        view.getWINDViewRoaming().setLeftMouseOperation(LeftMouseOperation.PICK);
    } else if (value === 'rotate') {
        view.getWINDViewRoaming().setLeftMouseOperation(LeftMouseOperation.ROTATE);
    } else if (value === 'pan') {
        view.getWINDViewRoaming().setLeftMouseOperation(LeftMouseOperation.PAN);
    }
}

function revertHomePosition() {
    view.getWINDViewRoaming().revertHomePosition();
}

function zoomInPosition() {
    view.getWINDViewRoaming().zoomInPosition();
}

function zoomOutPosition() {
    view.getWINDViewRoaming().zoomOutPosition();
}

function locateSelectEntities() {
    view.getWINDViewRoaming().locateSelectEntities();
}
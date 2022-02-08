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
async function getModelList() {
    let modelarray = await data.getWINDDataQuerier().getAllModelParameterS();
    let model_name = $('#model_name').val();
    let l = modelarray.length;
    for (let i = 0; i < l; i++) {
        let model = modelarray[i];
        let temp = {};
        temp._id = model._id;
        temp._version = model.modelFile.version;
        temp._name = model.name;
        modeldata.set(model.name, temp);
    }
    console.log(modeldata);
    let modellistUI = document.getElementById("modellist");
    modeldata.forEach((model, name) => {
        modellistUI.add(new Option(name));
    });
    $("#modellist option").each(function(i){
        if(this.value == model_name){
            this.selected = true;
        }
    });
    modellistUI.options[0].selected = true;//默认选中第一个
    // var program_id = $('#program_id').val();
    // var formData = new FormData();
    // formData.append("project_id",program_id);
    // $.ajax({
    //     url: "index.php?r=rf/rfi/modellist",
    //     type: "POST",
    //     data: formData,
    //     dataType: "json",
    //     processData: false,         // 告诉jQuery不要去处理发送的数据
    //     contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
    //     beforeSend: function () {
    //
    //     },
    //     success: function(data){
    //         $.each(data, function (name, value) {
    //             if (name == 'data') {
    //                 $.each(value, function (i, j) {
    //                     let temp = {};
    //                     temp._id = j['model_id'];
    //                     temp._version = j['version'];
    //                     temp._name = j['model_name'];
    //                     modeldata.set(j['model_name'], temp);
    //                 })
    //             }
    //         })
    //         // let modelarray = await data.getWINDDataQuerier().getAllModelParameterS();
    //         let model_name = $('#model_name').val();
    //         // let l = modelarray.length;
    //         // for (let i = 0; i < l; i++) {
    //         //     let model = modelarray[i];
    //         //     let temp = {};
    //         //     temp._id = model._id;
    //         //     temp._version = model.modelFile.version;
    //         //     temp._name = model.name;
    //         //     modeldata.set(model.name, temp);
    //         // }
    //         console.log(modeldata);
    //         let modellistUI = document.getElementById("modellist");
    //         modeldata.forEach((model, name) => {
    //             modellistUI.add(new Option(name));
    //         });
    //         $("#modellist option").each(function(i){
    //             if(this.value == model_name){
    //                 this.selected = true;
    //             }
    //         });
    //         // modellistUI.options[0].selected = true;//默认选中第一个
    //     },
    //     error: function(XMLHttpRequest, textStatus, errorThrown) {
    //         //alert(XMLHttpRequest.status);
    //         //alert(XMLHttpRequest.readyState);
    //         //alert(textStatus);
    //     },
    // });
}

//WINDView初始化
let canvas = document.getElementById("View");
let view = new WINDView(canvas);
view.bindWINDData(data);//将View与一个Data绑定

//页面加载时初始化ui事件
window.addEventListener('load', onLoad, true);
async function onLoad() {
    //初始化UI
    await getModelList();
    initDataUI();
    // initViewUI();
    initViewRoamingUI();
    // let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    let model_name = $('#model_name').val();
    // alert(model_name);
    let modelarray = await data.getWINDDataQuerier().getAllModelParameterS();
    let l = modelarray.length;
    for (let i = 0; i < l; i++) {
        let model = modelarray[i];
        let temp = {};
        temp._id = model._id;
        temp._version = model.modelFile.version;
        temp._name = model.name;
        modeldata.set(model.name, temp);
    }
    let model = modeldata.get(model_name);
    console.log(model);
    if (model) {
        await data.getWINDDataLoader().openModelData(model._id);//打开对应模型id的模型数据
    }
    // let rs = ["cd61984a-99da-4770-be0e-a105f328d911-00033b60","cd61984a-99da-4770-be0e-a105f328d911-00033b2f"];
    // view.getWINDViewControl().highlightEntities(rs);
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
    let getEntityParameterUI = document.getElementById("getEntityParameter");
    getEntityParameterUI.addEventListener("click", getEntityParameter, false);
}

async function openCurrentModelData(modeldata) {
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    console.log(model);
    if (model) {
        await data.getWINDDataLoader().openModelData(model._id);//打开对应模型id的模型数据
    }
}

async function openModelData() {
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    console.log(model);
    if (model) {
        await data.getWINDDataLoader().openModelData(model._id);//打开对应模型id的模型数据
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
    console.log(entity);
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
    document.getElementById("model_id").value = model_id;
    document.getElementById("entityId").value = entityId;
    document.getElementById("uuid").value = uuid;
    document.getElementById("model").value = model_name;
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

function submit() {
    let modellistUI = document.getElementById("modellist");
    // let state = view.getWINDViewSection().getSectionState();
    // console.log(state);
    // console.log(ViewStateType.ALL);
    // let t = view.saveWINDViewState(ViewStateType.ALL);
    // console.log(t);
    let r = '';
    // if(t){
    //     var e=document.createElement("a");
    //     r=new Blob([t],{type:"text/plain"});
    //
    //     // alert(r);
    //     document.getElementById("view").value = t;
    //     // e.href=window.URL.createObjectURL(r);
    //     // e.download="saveviewState",e.click();
    // }
    // console.log(r);
    var model = modellistUI.options[modellistUI.selectedIndex].text;
    var title = $('#title').val();
    var model_id = $('#model_id').val();
    var entityId = $('#entityId').val();
    var uuid = $('#uuid').val();
    var remark = $('#remark').val();
    var check_id = $('#check_id').val();
    // var view = $('#view').val();
    var formData = new FormData();
    formData.append("title",title);
    formData.append("model",model);
    formData.append("model_id",model_id);
    formData.append("entityId",entityId);
    formData.append("uuid",uuid);
    formData.append("remark",remark);
    formData.append("check_id",check_id);
    // formData.append("view",r);
    // var request = new XMLHttpRequest();
    // request.open("POST", "entity.php");
    // request.send(formData);
    $.ajax({
        url: "entity.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,         // 告诉jQuery不要去处理发送的数据
        contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
        beforeSend: function () {

        },
        success: function(data){
            alert('save success');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.status);
            //alert(XMLHttpRequest.readyState);
            //alert(textStatus);
        },
    });
}

function screenshot() {
    // document.getElementById("screenshotView").src = view.screenShot();
    console.log(view.screenShot());
    var img = new Image();
//        alert(blob);
    img.src = view.screenShot();
    img.onload = function () {
        var that = this;
        //生成比例
        var w = that.width, h = that.height, scale = w / h;
        new_w = 300;
        new_h = new_w / scale;

        //生成canvas
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        $(canvas).attr({
            width: new_w,
            height: new_h
        });
        ctx.drawImage(that, 0, 0, new_w, new_h);
        // 图像质量
        quality = 0.9;
        // quality值越小，所绘制出的图像越模糊
        var base64 = canvas.toDataURL('image/png', quality);
        // 生成结果
        var result = {
            base64: base64,
            clearBase64: base64.substr(base64.indexOf(',') + 1)
        };
        // alert(result.base64);
        $("#filebase64").val(result.base64);
        console.log(result.base64);
    }

    $("#filebase64").val(view.screenShot());
    sumitImageFile();
}

var index = 0;
var chars = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

function generateMixed(n) {
    var res = "";
    for(var i = 0; i < n ; i ++) {
        var id = Math.ceil(Math.random()*35);
        res += chars[id];
    }
    return res;

}

function sumitImageFile(){
    var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
    var new_name = generateMixed(9);
    //convertBase64UrlToBlob函数是将base64编码转换为Blob
    formData.append("file1", convertBase64UrlToBlob($('#filebase64').val()), new_name+'.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
    //ajax 提交form
    $.ajax({
        url: "https://shell.cmstech.sg/appupload",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,         // 告诉jQuery不要去处理发送的数据
        contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
        beforeSend: function () {
            $("#screenshot").html( "Uploading..." );
        },
        success: function (data) {
            $.each(data, function (name, value) {
                if (name == 'data') {
                    moveFile(value.file1);
                }
            });
        },
    });
}
/**
 * 将以base64的图片url数据转换为Blob
 * @param urlData
 *            用url方式表示的base64图片数据
 */
function convertBase64UrlToBlob(urlData){

    var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte

    //处理异常,将ascii码小于0的转换为大于0
    var ab = new ArrayBuffer(bytes.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < bytes.length; i++) {
        ia[i] = bytes.charCodeAt(i);
    }

    return new Blob( [ab] , {type : 'image/png'});
}
/**
 * 上传至正式服务器
 */
function moveFile(file) {
    var file_src = file.toString();
    var formData = new FormData();
    formData.append("file_src",file_src);
    // alert(file_src);
    $.ajax({
        url: "upload.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,         // 告诉jQuery不要去处理发送的数据
        contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
        beforeSend: function () {

        },
        success: function(data){
            $("#screenshot").html( "截图" );
            document.getElementById("textarea").value = data.src;
            // $("#textarea").html( data.src);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.status);
            //alert(XMLHttpRequest.readyState);
            //alert(textStatus);
        },
    });
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
    let saveUI = document.getElementById("save");
    saveUI.addEventListener("click", submit, false);
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

//highlightEntities
function highlightEntities() {
    var check_id = $('#check_id').val();
    var formData = new FormData();
    var arr = [];
    formData.append("check_id",check_id);
    $.ajax({
        url: "highlight.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,         // 告诉jQuery不要去处理发送的数据
        contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
        beforeSend: function () {

        },
        success: function(data){
            $.each(data, function (name, value) {
                if (name == 'data') {
                    $.each(value, function (i, j) {
                        var arr = j['uuid'].split(',');
                        // if(j['view']){
                        //     alert(123);
                        //     view.loadWINDViewState(j['view']);
                        // }
                        view.getWINDViewControl().highlightEntities(arr);
                        document.getElementById("title").value = j['title'];
                        document.getElementById("model_id").value = j['model_id'];
                        document.getElementById("entityId").value = j['entityId'];
                        document.getElementById("remark").value = j['remark'];
                        if(j['status']){
                            document.getElementById("save").setAttribute("disabled", true);
                            document.getElementById("submit").setAttribute("disabled", true);
                        }
                    })
                }
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.status);
            //alert(XMLHttpRequest.readyState);
            //alert(textStatus);
        },
    });
    // let rs = ["cd61984a-99da-4770-be0e-a105f328d911-00033b60","cd61984a-99da-4770-be0e-a105f328d911-00033b2f"];
}


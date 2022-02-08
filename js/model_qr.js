
var new_front_chinese = 'js/resource/front.png';
var new_back_chinese = 'js/resource/back.png';
var new_left_chinese = 'js/resource/left.png';
var new_right_chinese = 'js/resource/right.png';
var new_top_chinese = 'js/resource/top.png';
var new_bottom_chinese = 'js/resource/bottom.png';
var new_compass_plate_chinese = 'js/resource/compass.png';

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
// let loadCallback = function (type, value) {//模型加载百分比回调
//     console.log('load百分比:' + value);
// };
// data.addWINDDataCallback(1, loadCallback);

$("#View").click(function(){
    var alert_str = $('#text_tag').val();
    if(alert_str == 'Revit Info'){
        revit_tree();
    }
})

//获取当前服务器包含的模型列表
let modeldata = new Map();
let modelgroup_data = [];
let modeldata1 = new Map();
async function getModelList() {
    // let modelarray = await data.getWINDDataQuerier().getAllModelParameterS();
    // let model_name = $('#model_name').val();
    // let l = modelarray.length;
    // for (let i = 0; i < l; i++) {
    //     let model = modelarray[i];
    //     let temp = {};
    //     temp._id = model._id;
    //     temp._version = model.modelFile.version;
    //     temp._name = model.name;
    //     modeldata.set(model.name, temp);
    // }
    // console.log(modeldata);
    // let modellistUI = document.getElementById("modellist");
    // modeldata.forEach((model, name) => {
    //     modellistUI.add(new Option(name));
    // });
    // $("#modellist option").each(function(i){
    //     if(this.value == model_name){
    //         this.selected = true;
    //     }
    // });
    // modellistUI.options[0].selected = true;//默认选中第一个

    var program_id = $('#program_id').val();
    var formData = new FormData();
    let temp = {};
    var small_arr = [];
    formData.append("project_id",program_id);
    $.ajax({
        url: "index.php?r=rf/rf/modellist",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,         // 告诉jQuery不要去处理发送的数据
        contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
        beforeSend: function () {

        },
        success: function(data){
            var map = {};
            $.each(data, function (name, value) {
                console.log(value);
                temp._id = value['model_id'];
                temp._version = value['version'];
                temp._name = value['model_name'];
                temp._group_name = value['group_name'];
                modeldata.set(value['model_name'], temp);
                if(value['group_name']){
                    group_name = value['group_name'];
                }else{
                    group_name = 'Model Group';
                }
                if(!map[group_name]){
                    small_arr.push({
                        name: group_name,
                        children: [value],
                    });
                    map[group_name] = value;
                }else{
                    for(var j = 0; j < small_arr.length; j++){
                        var dj = small_arr[j];
                        if(dj.name == group_name){
                            dj.children.push(value);
                            break;
                        }
                    }
                }
            })
            // let modelarray = await data.getWINDDataQuerier().getAllModelParameterS();
            let model_name = $('#model_name').val();
            // let l = modelarray.length;
            // for (let i = 0; i < l; i++) {
            //     let model = modelarray[i];
            //     let temp = {};
            //     temp._id = model._id;
            //     temp._version = model.modelFile.version;
            //     temp._name = model.name;
            //     modeldata1.set(model.name, temp);
            // }
            // console.log(modeldata);
            // console.log(small_arr);
            // let modellistUI = document.getElementById("modellist");
            let multipleUI = document.getElementById("fselect");
            multipleUI.add(new Option('All','1'));
            for(var j = 0; j < small_arr.length; j++){
                var dj = small_arr[j];
                var group=document.createElement('OPTGROUP');
                group.label = dj.name;
                multipleUI.appendChild(group);
                for(var i = 0; i < dj.children.length; i++){
                    var model = dj.children[i];
                    id_version = model.model_id+'_'+model.version;
                    name = model.model_name;
                    multipleUI.options.add(new Option(name,id_version));
                };
            }
            // modeldata.forEach((model, name) => {
            //     id_version = model._id+'_'+model._version;
            //     // modellistUI.add(new Option(name,id_version));
            //     multipleUI.options.add(new Option(name,id_version));
            //     // modellistUI.add(new Option(name));
            // });
            $('#fselect').fSelect();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.status);
            //alert(XMLHttpRequest.readyState);
            //alert(textStatus);
        },
    });
}

//WINDView初始化
let wind_h  = $('#WindJS').height();
let canvas = document.getElementById("View");
if(wind_h>0){
    $('#View').height(wind_h);
}
let view = new WINDView(canvas);
let atree = {};
let btree = {};
view.bindWINDData(data);//将View与一个Data绑定
//改成真实模式
view.getWINDViewSetting().isRealModeOpened(true);
//设定渐变色类型
view.getWINDViewSetting().setBackgroundType(2);
//设定画布上部颜色
view.getWINDViewSetting().setBackgroundGradientTopColor(135, 206, 235, 225);
//设定画布下部颜色
view.getWINDViewSetting().setBackgroundGradientBottomColor(255, 255, 255, 225);


//页面加载时初始化ui事件
window.addEventListener('load', onLoad, true);
async function onLoad() {
    //初始化UI
    await getModelList();
    initDataUI();
    // initViewUI();
    // initViewRoamingUI();

    //添加视图回调
    view.addWINDViewCallback('callback', callback);
    // highlightAssignedEntities();
    //设置导航辅助六个面显示的图片
    view.getWINDViewAssist().setNavigateResource(new_front_chinese, new_back_chinese, new_top_chinese, new_bottom_chinese, new_left_chinese, new_right_chinese, new_compass_plate_chinese);
    //view.getWINDViewRoaming().setPersonResource(characterdat, characterpng);
    //view.getWINDViewRoaming().setCharacterResource(characterdat, characterpng);
    //开启导航
    view.getWINDViewAssist().setNavigateProperty(20, 0, 120);
    view.getWINDViewAssist().openNavigateAssist();
    // this.setState({
    //     currentData: data,
    //     currentView: view,
    //     floors: [floorTree],
    //     domains: [domainTree],
    // }, () => {});
}

function initDataUI() {
    let openModelDataUI = document.getElementById("openModelData");
    openModelDataUI.addEventListener("click", openModelData, false);

    // let closeModelDatasUI = document.getElementById("closeModelDatas");
    // closeModelDatasUI.addEventListener("click", closeModel, false);

    let getEntityParameterUI = document.getElementById("save");
    getEntityParameterUI.addEventListener("click", getAllComponentParamter, false);
}

async function openCurrentModelData(modeldata) {
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    console.log(model);
    addcloud();
    if (model) {
        await data.getWINDDataLoader().openModelData(model._id,model._version,true);//打开对应模型id的模型数据
    }
    removecloud();
}

async function openModelData() {
    $('#text_tag').val('Open');
    closeModelDatas();
    // let modellistUI = document.getElementById("modellist");
    // let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    // var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = $('#fselect').val();
    console.log(arr);
    var newArr = [];
    if(arr.length > 0){
        for(j = 0,len=arr.length; j < len; j++) {
            if(arr[j] != '1'){
                var model_arr = arr[j].split('_');
                var vote = {};
                vote['modelId'] = model_arr[0];
                vote['version'] = model_arr[1];
                newArr.push(vote);
            }
        }
        console.log(newArr);
        // var alert_str = 'Pan: 鼠标中键\n' + 'Select: 鼠标左键\n' + 'Rotate: 鼠标右键\n' + 'Zoom: 鼠标中键前后\n';
        // alert(alert_str);
        addcloud();
        let loadCallback = function (type, value) {//模型加载百分比回调
            console.log('load百分比:' + Math.round(value));
            $('#loadingDiv').html(Math.round(value)+'%');
        };
        data.addWINDDataCallback(1, loadCallback);
        await data.getWINDDataLoader().openModelDatas(newArr,true);//打开对应模型id的模型数据
        removecloud();
    }
}


function closeModelDatas() {
    data.getWINDDataLoader().closeAllModelDatas();
}

function closeModel() {
    // location.reload();
    data.getWINDDataLoader().closeAllModelDatas();
}

async function solate() {
    view.getWINDViewControl().hideUnselectComponents();
}

async function hide() {
    view.getWINDViewControl().hideSelectComponents();
}

//Block初始化
function BlockInit(block) {
    return block.html('<option value="">--Block--</option>');
}
//Level初始化
function LevelInit(level) {
    return level.html('<option value="">--Level--</option>');
}

//Part初始化
function PartInit(part) {
    return part.html('<option value="">--Part--</option>');
}

//Unit初始化
function UnitInit(unit) {
    return unit.html('<option value="">--Unit--</option>');
}

//Name初始化
function NameInit(name) {
    return name.html('<option value="">--Name--</option>');
}

function transfer() {
    $("#info").empty();
    var arr = $('#fselect').val();
    var model_list = arr.join(',');
    console.log(arr);
    console.log(model_list);
    var block_get = '*';
    var level_get = '*';
    var part_get = '*';
    var unit_get = '*';
    var name_get = '*';
    var guid_get = '*';
    tab="";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Guid1</label><div class='col-sm-10'><button type='button' class='btn btn-primary btn-sm' onclick='transfer_get(1)'>Get</button></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Block</label><div class='col-sm-10'><select id='block_1' class='form-control' onchange='blockchange(1,\""+block_get+"\",\""+level_get+"\")'><option>--Block--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Level</label><div class='col-sm-10'><select id='level_1' class='form-control' onchange='levelchange(1,\""+block_get+"\",\""+level_get+"\",\""+part_get+"\")'><option>--Level--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Part</label><div class='col-sm-10'><select id='part_1' class='form-control' onchange='partchange(1,\""+block_get+"\",\""+level_get+"\",\""+part_get+"\",\""+unit_get+"\")'><option>--Part--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Unit</label><div class='col-sm-10'><select id='unit_1' class='form-control' onchange='unitchange(1,\""+block_get+"\",\""+level_get+"\",\""+part_get+"\",\""+unit_get+"\",\""+name_get+"\")'><option>--Unit--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Name</label><div class='col-sm-10'><select id='name_1' class='form-control' onchange='namechange(1,\""+block_get+"\",\""+level_get+"\",\""+part_get+"\",\""+unit_get+"\",\""+name_get+"\")'><option>--Name--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Guid</label><div class='col-sm-10'><input type='hidden' class='form-control' id='entityid_1' ><input type='text' class='form-control' id='guid_1' placeholder='Guid' readonly></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Guid2</label><div class='col-sm-10'><button type='button' class='btn btn-primary btn-sm' onclick='transfer_get(2)'>Get</button></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Block</label><div class='col-sm-10'><select id='block_2' class='form-control' onchange='blockchange(2,\""+block_get+"\",\""+level_get+"\")'><option>--Block--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Level</label><div class='col-sm-10'><select id='level_2' class='form-control' onchange='levelchange(2,\""+block_get+"\",\""+level_get+"\",\""+part_get+"\")'><option>--Level--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Part</label><div class='col-sm-10'><select id='part_2' class='form-control' onchange='partchange(2,\""+block_get+"\",\""+level_get+"\",\""+part_get+"\",\""+unit_get+"\")'><option>--Part--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Unit</label><div class='col-sm-10'><select id='unit_2' class='form-control' onchange='unitchange(2,\""+block_get+"\",\""+level_get+"\",\""+part_get+"\",\""+unit_get+"\",\""+name_get+"\")'><option>--Unit--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Name</label><div class='col-sm-10'><select id='name_2' class='form-control' onchange='namechange(2,\""+block_get+"\",\""+level_get+"\",\""+part_get+"\",\""+unit_get+"\",\""+name_get+"\")'><option>--Name--</option></select></div></div>";
    tab+="<div class='row'><label class='col-sm-2 control-label'>Guid</label><div class='col-sm-10'><input type='hidden' class='form-control' id='entityid_2' ><input type='text' class='form-control' id='guid_2' placeholder='Guid' readonly></div></div>";
    tab+="<div class='row' style='text-align: center;margin-bottom: 2px;margin-top: 2px;'><button type='button' class='btn btn-primary' onclick='transfer_save()'>Transfer</button></div>";
    $("#info").append(tab);
    var blockObj_1 = $("#block_1");
    var blockOpt_1 = $("#block_1 option");
    var blockObj_2 = $("#block_2");
    var blockOpt_2 = $("#block_2 option");
    var program_id = $('#program_id').val();
    $.ajax({
        type: "POST",
        url: "index.php?r=task/model/queryblocklist",
        data: {modellist:model_list,program_id:program_id},
        dataType: "json",
        success: function(data){ //console.log(data);
            if (!data) {
                return;
            }
            BlockInit(blockObj_1);
            BlockInit(blockObj_2);
            for (var o in data) {
                console.log(o);
                blockObj_1.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
                blockObj_2.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
            }
        },
    });
}

function blockchange(block_id,block_get,level_get) {
    if(block_id == 1){
        var levelObj = $("#level_1");
        var levelOpt = $("#level_1 option");
        var block = $("#block_1").val();
    }else{
        var levelObj = $("#level_2");
        var levelOpt = $("#level_2 option");
        var block = $("#block_2").val();
    }
    if(block_get != '*'){
        var block = block_get;
    }
    var program_id = $('#program_id').val();
    console.log(block_id);
    $.ajax({
        type: "POST",
        url: "index.php?r=task/model/querylevellist",
        data: {block:block,program_id:program_id},
        dataType: "json",
        success: function(data){ //console.log(data);
            if (!data) {
                return;
            }
            LevelInit(levelObj);
            for (var o in data) {//console.log(o);
                levelObj.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
            }
            if(level_get != '*'){
                levelObj.val(level_get);
            }
        },
    });
}

function levelchange(block_id,block_get,level_get,part_get) {
    if(block_id == 1){
        var partObj = $("#part_1");
        var partOpt = $("#part_1 option");
        var block = $("#block_1").val();
        var level = $("#level_1").val();
    }else{
        var partObj = $("#part_2");
        var partOpt = $("#part_2 option");
        var block = $("#block_2").val();
        var level = $("#level_2").val();
    }
    if(block_get != '*'){
        var block = block_get;
    }
    if(level_get != '*'){
        var level = level_get;
    }
    var program_id = $('#program_id').val();
    console.log(block_id);
    $.ajax({
        type: "POST",
        url: "index.php?r=task/model/querypartlist",
        data: {block:block,level:level,program_id:program_id},
        dataType: "json",
        success: function(data){ //console.log(data);
            if (!data) {
                return;
            }
            PartInit(partObj);
            for (var o in data) {//console.log(o);
                partObj.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
            }
            if(part_get != '*'){
                partObj.val(part_get);
            }
        },
    });
}

function partchange(block_id,block_get,level_get,part_get,unit_get) {
    if(block_id == 1){
        var unitObj = $("#unit_1");
        var unitOpt = $("#unit_1 option");
        var block = $("#block_1").val();
        var level = $("#level_1").val();
        var part = $("#part_1").val();
    }else{
        var unitObj = $("#unit_2");
        var unitOpt = $("#unit_2 option");
        var block = $("#block_2").val();
        var level = $("#level_2").val();
        var part = $("#part_2").val();
    }
    var program_id = $('#program_id').val();
    if(block_get != '*'){
        var block = block_get;
    }
    if(level_get != '*'){
        var level = level_get;
    }
    if(part_get != '*'){
        var part = part_get;
    }
    console.log(block_id);
    $.ajax({
        type: "POST",
        url: "index.php?r=task/model/queryunitlist",
        data: {block:block,level:level,part:part,program_id:program_id},
        dataType: "json",
        success: function(data){ //console.log(data);
            if (!data) {
                return;
            }
            UnitInit(unitObj);
            for (var o in data) {//console.log(o);
                unitObj.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
            }
            if(unit_get != '*'){
                unitObj.val(unit_get);
            }
        },
    });
}

function unitchange(block_id,block_get,level_get,part_get,unit_get,name_get) {
    if(block_id == 1){
        var nameObj = $("#name_1");
        var nameOpt = $("#name_1 option");
        var block = $("#block_1").val();
        var level = $("#level_1").val();
        var part = $("#part_1").val();
        var unit = $("#unit_1").val();
    }else{
        var nameObj = $("#name_2");
        var nameOpt = $("#name_2 option");
        var block = $("#block_2").val();
        var level = $("#level_2").val();
        var part = $("#part_2").val();
        var unit = $("#unit_2").val();
    }
    if(block_get != '*'){
        var block = block_get;
    }
    if(level_get != '*'){
        var level = level_get;
    }
    if(part_get != '*'){
        var part = part_get;
    }
    if(unit_get != '*'){
        var unit = unit_get;
    }
    var program_id = $('#program_id').val();
    console.log(block_id);
    $.ajax({
        type: "POST",
        url: "index.php?r=task/model/querynamelist",
        data: {block:block,level:level,part:part,unit:unit,program_id:program_id},
        dataType: "json",
        success: function(data){ //console.log(data);
            if (!data) {
                return;
            }
            NameInit(nameObj);
            for (var o in data) {//console.log(o);
                nameObj.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
            }
            if(name_get){
                nameObj.val(name_get);
            }
        },
    });
}

async function namechange(block_id,block_get,level_get,part_get,unit_get,name_get) {
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    if(block_id == 1){
        var block = $("#block_1").val();
        var level = $("#level_1").val();
        var part = $("#part_1").val();
        var unit = $("#unit_1").val();
        var name = $("#name_1").val();
    }else{
        var block = $("#block_2").val();
        var level = $("#level_2").val();
        var part = $("#part_2").val();
        var unit = $("#unit_2").val();
        var name = $("#name_2").val();
    }
    if(block_get != '*'){
        var block = block_get;
    }
    if(level_get != '*'){
        var level = level_get;
    }
    if(part_get != '*'){
        var part = part_get;
    }
    if(unit_get != '*'){
        var unit = unit_get;
    }
    if(name_get != '*'){
        var name = name_get;
    }
    var program_id = $('#program_id').val();
    console.log(block_id);
    $.ajax({
        type: "POST",
        url: "index.php?r=task/model/queryguid",
        data: {block:block,level:level,part:part,unit:unit,name:name,program_id:program_id},
        dataType: "json",
        success: function(data){
            console.log(data);
            if (!data) {
                return;
            }
            for (var o in data) {//console.log(o);
                console.log(data[o]);
                if(o == 0){
                    if(entity.length>0){
                        if(block_id == 1){
                            $('#guid_1').val(entity[0]);
                        }else{
                            $('#guid_2').val(entity[0]);
                        }
                        view.getWINDViewControl().highlightAssignedEntities(entity[0]);
                    }else{
                        if(block_id == 1){
                            $('#guid_1').val(data[o]);
                        }else{
                            $('#guid_2').val(data[o]);
                        }
                        view.getWINDViewControl().highlightAssignedEntities(data[o]);
                    }
                }
            }
        },
    });
}

async function transfer_save() {
    var guid_1 = $('#guid_1').val();
    var guid_2 = $('#guid_2').val();
    if(!guid_1 && !guid_2){
        alert('Please select Guid1 and Guid2');
        return;
    }
    var program_id = $('#program_id').val();
    var info_1 = await data.getWINDDataQuerier().getEntityParameterL(guid_1);
    var info_2 = await data.getWINDDataQuerier().getEntityParameterL(guid_2);
    var entity_id_1 = info_1.entityId;
    var entity_id_2 = info_2.entityId;
    $.ajax({
        type: "POST",
        url: "index.php?r=task/model/transfer",
        data: {guid_1:guid_1,guid_2:guid_2,entityid_1:entity_id_1,entityid_2:entity_id_2,program_id:program_id},
        dataType: "json",
        success: function(data){
            console.log(data);
            if (data) {
                alert('Transfer Success');
            }
        },
    });
}

async function transfer_get(block_id){
    var program_id = $('#program_id').val();
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    var length = entity.length;
    var entity_info = await data.getWINDDataQuerier().getEntityParameterL(entity[0]);
    var arr = $('#fselect').val();
    var model_list = arr.join(',');
    console.log(entity_info);
    if(block_id == 1){
        var blockObj = $("#block_1");
        var levelobj = $('#level_1');
        var partobj = $('#part_1');
        var unitobj = $('#unit_1');
        var nameobj = $('#name_1');
        var guidobj = $('#guid_1');
    }else{
        var blockObj = $("#block_2");
        var levelobj = $('#level_2');
        var partobj = $('#part_2');
        var unitobj = $('#unit_2');
        var nameobj = $('#name_2');
        var guidobj = $('#guid_2');
    }

    if(length != 1){
        alert('Please select only one component.');
        return;
    }
    $.ajax({
        url: "index.php?r=task/model/pbuinfo",
        type: "POST",
        data: {program_id: program_id,model_id:entity_info.modelId,uuid:entity_info.uuid,version:entity_info.version},
        dataType: "json",
        beforeSend: function () {

        },
        success: function(data_1){
            if(data_1.length>0){
                console.log(data_1);

                blockObj.val(data_1[0].block);
                var block = data_1[0].block;
                var level = data_1[0].level;
                var part = data_1[0].part;
                var unit_nos = data_1[0].unit_nos;
                var pbu_name = data_1[0].pbu_name;
                blockchange(block_id,block,level);
                levelchange(block_id,block,level,part);
                partchange(block_id,block,level,part,unit_nos);
                unitchange(block_id,block,level,part,unit_nos,pbu_name);
                namechange(block_id,block,level,part,unit_nos,pbu_name);
            }else{
                $.ajax({
                    type: "POST",
                    url: "index.php?r=task/model/queryblocklist",
                    data: {modellist:model_list,program_id:program_id},
                    dataType: "json",
                    success: function(data){ //console.log(data);
                        if (!data) {
                            return;
                        }
                        BlockInit(blockObj);
                        for (var o in data) {
                            console.log(o);
                            blockObj.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
                        }
                    },
                });
                LevelInit(levelobj);
                PartInit(partobj);
                UnitInit(unitobj);
                NameInit(nameobj);
                guidobj.empty();
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.status);
            //alert(XMLHttpRequest.readyState);
            //alert(textStatus);
        },
    });

}


async function level() {
    view.getWINDViewControl().createComponentTree([TreeRuleType.PROJECT_NAME, TreeRuleType.STOREY_NAME], atree);
    // view.getWINDViewControl().createComponentTree([TreeRuleType.DOMAIN_NAME, TreeRuleType.CATEGORY_NAME, TreeRuleType.TYPE_NAME], btree);
    console.log(atree);
    console.log(atree.children[0].parent);
    console.log(atree.children[0].children);
    var root_id = atree.children[0].id;
    var levelObj = $("#weatherType");
    LevelInit(levelObj);
    levelObj.append("<option value='"+atree.children[0].id+"'>"+atree.children[0].name+"</option>");
    $.each(atree.children[0].children, function (name, value) {
        console.log(value);
        levelObj.append("<option value='"+value.id+"'>"+value.name+"</option>");
    });
    $("#weatherType").selectpicker('refresh');
}

async function revit_tree(){
    $('#text_tag').val('Revit Info');
    // alert($('#new_sidebar').css("display"));
    // if($('#new_sidebar').css("display") == 'block'){
    //     document.getElementById("close_sidebar").click();
    // }
    if($('#new_sidebar').css("display") == 'none'){
        document.getElementById("open_sidebar").click();
    }
    var program_id = $('#program_id').val();
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    var length = entity.length;
    var map = {};
    console.log(entity);
    $("#info").empty();
    for ( var i = 0; i <entity.length; i++) {
        info = await data.getWINDDataQuerier().getEntityParameterL(entity[i]);
        var map = {}, big_arr = [];
        console.log(info);
        for(var key in info){
            if( key == 'properties'){
                var arr = info[key];
                console.log(arr);
                for(var i = 0; i < arr.length; i++){
                    var ai = arr[i];
                    if(!map[ai.group]){
                        big_arr.push({
                            group: ai.group,
                            children: [ai]
                        });
                        map[ai.group] = ai;
                    }else{
                        for(var j = 0; j < big_arr.length; j++){
                            var dj = big_arr[j];
                            if(dj.group == ai.group){
                                dj.children.push(ai);
                                break;
                            }
                        }
                    }
                }
            }
            if( key != 'properties'){
                var group = 'Base Info';
                var j_1 ={};
                j_1['key'] = key;
                j_1['value'] = info[key];
                if(!map[group]){
                    big_arr.push({
                        group: group,
                        children: [j_1]
                    });
                    map[group] = j_1;
                }else{
                    for(var j = 0; j < big_arr.length; j++){
                        var dj = big_arr[j];
                        if(dj.group == group){
                            dj.children.push(j_1);
                            break;
                        }
                    }
                }
            }
        }
    }
    console.log(big_arr);
    var index = 0;
    tab="<table id='revit_tab' align='left' frame='hsides' width='100%' class='table table-hover level-tab'>";
    tab+="<thead><tr><td  align='left'>Property Name</td><td  align='left'>Property Value</td></tr></thead>";
    for(x=0;x<big_arr.length;x++){
        index++;
        tab+="<thead><tr id='tr_"+index+"' data-widget='expandable-table' aria-expanded='false' onclick='info_show("+index+")'><td style='white-space: nowrap;' align='center' colspan='2'><i class='expandable-table-caret fas fa-caret-right fa-fw'></i>"+big_arr[x].group+"</td></tr></thead>";
        for(y=0;y<big_arr[x].children.length;y++){
            var children_info = big_arr[x].children[y];
            tab+="<tr class='tr_"+index+"' style='display:none;'><td  align='left'>"+children_info.key+"</td><td  align='left'>"+children_info.value+"</td></tr>";
        }
    }
    $("#info").append(tab);
}

async function info_show(index){
    if($('#tr_'+index).attr('aria-expanded') == 'true'){
        var trs = $("tr[class='tr_"+index+"']");
        for(i = 0; i < trs.length; i++){
            trs[i].style.display = "none"; //这里获取的trs[i]是DOM对象而不是jQuery对象，因此不能直接使用hide()方法
        }
    }else{
        var trs = $("tr[class='tr_"+index+"']");
        for(i = 0; i < trs.length; i++){
            trs[i].style.display = ""; //这里获取的trs[i]是DOM对象而不是jQuery对象，因此不能直接使用hide()方法
        }
    }
}

async function tree() {
    $('#text_tag').val('Level');
    if($('#new_sidebar').css("display") == 'none'){
        document.getElementById("open_sidebar").click();
    }
    view.getWINDViewControl().createComponentTree([TreeRuleType.PROJECT_NAME, TreeRuleType.STOREY_NAME], atree);
    console.log(atree);
    console.log(atree.children[0].parent);
    console.log(atree.children[0].children);
    var root_id = atree.children[0].id;
    $("#info").empty();
    tab="<div class='card card-info card-outline' >";
    tab+="<div class='card-body' style='overflow-x: auto'>";
    var index = 0;
    tab+="<table id='level_tab' align='center' frame='hsides' width='100%' class='table-bordered level-tab'>";
    tab+="<thead><tr><td align='center' width='10%'><input  type='checkbox' name='checkAll' /></td><td style='white-space: nowrap;' align='center'>All</td></tr></thead>";
    $.each(atree.children, function (tree_name, tree_arr) {
        index++;
        // tab+="<table id='level_tab_"+index+"' align='center' frame='hsides' width='100%' class='table-bordered level-tab'>";
        tab+="<thead><tr><th style='text-align: center;' colspan='2'>"+tree_arr.name+"</th></tr></thead>";
        tab+="<tbody>";
        $.each(tree_arr.children, function (name, value) {
            console.log(value);
            tab+="<tr><td align='center' width='10%'><input type='checkbox' name='checkItem' /></td><td style='white-space: nowrap;display: none' name='id' align='center' width='90%'>"+value.id+"</td><td style='white-space: nowrap;' align='center'>"+value.name+"</td></tr>";
        });
        tab+="</tbody>";
    });
    tab+="</table>";
    tab+="</div>";
    tab+="</div>";
    $("#info").append(tab);
    //默认全选
    // var $thr = $('#status_tab thead tr');
    // var $checkAll = $thr.find('input');
    // $checkAll.prop('checked',true);
    // var $tbr = $('#status_tab tbody tr');
    // $tbr.find('input').prop('checked', true);

    initLevelCheckbox(root_id,atree);

}

async function component_tree(){
    $('#text_tag').val('Component');
    if($('#new_sidebar').css("display") == 'none'){
        document.getElementById("open_sidebar").click();
    }
    $this = $('#info');
    view.getWINDViewControl().createComponentTree([TreeRuleType.DOMAIN_NAME, TreeRuleType.CATEGORY_NAME, TreeRuleType.TYPE_NAME], btree);
    console.log(btree);
    var btree_1 = btree.children;
    var j_1 = {};
    j_1['text'] = 'All';
    j_1['id'] = btree.id;
    j_1['root'] = 1;
    j_1['href'] = '#All';
    j_1['nodes'] = [];
    for(var k = 0; k < btree_1.length; k++){
        var j_2 = {};
        j_2['text'] = btree_1[k].name;
        j_2['id'] = btree_1[k].id;
        j_2['root'] = 2;
        j_2['href'] = '#'+btree_1[k].name;
        var btree_2 = btree_1[k].children;
        j_2['nodes'] = [];
        for(var x =0; x < btree_2.length; x++){
            var j_3 = {};
            j_3['text'] = btree_2[x].name;
            j_3['id'] = btree_2[x].id;
            j_3['root'] = 3;
            j_3['href'] = '#'+btree_2[x].name;
            var btree_3 = btree_2[x].children;
            j_3['nodes'] = [];
            for(var i =0; i < btree_3.length; i++){
                var j_4 = {};
                j_4['text'] = btree_3[i].name;
                j_4['id'] = btree_3[i].id;
                j_4['root'] = 4;
                j_4['href'] = '#'+btree_3[i].name;
                j_3['nodes'].push(j_4);
            }
            j_2['nodes'].push(j_3);
        }
        j_1['nodes'].push(j_2);
    }
    var j = [];
    j.push(j_1);
    console.log(j_1);
    var $checkableTree = $('#info').treeview({
        data: j,
        showIcon: false,
        showCheckbox: true,
        onNodeChecked: function(event, node) {
            if (node.nodes != null) {
                console.log(node);
                $.each(node.nodes, function(index, value) {
                    $this.treeview('checkNode', value.nodeId, {
                        silent : true
                    });
                    console.log(value);
                });
            } else {
                console.log(node);
                // 父节点
                var parentNode = $this.treeview('getParent', node.nodeId);
                console.log(parentNode);
                var isAllchecked = true; // 是否全部选中

                var siblings = $this.treeview('getSiblings', node.nodeId);
                for ( var i in siblings) {
                    // 有一个没选中，则不是全选
                    if (!siblings[i].state.checked) {
                        isAllchecked = false;
                        break;
                    }
                }

                // 全选，则打钩
                if (isAllchecked) {
                    $this.treeview('checkNode', parentNode.nodeId, {
                        silent : true
                    });
                } else {// 非全选，则变红
                    $this.treeview('selectNode', parentNode.nodeId, {
                        silent : true
                    });
                }
                status = view.getWINDViewControl().displayTreeNode(node.id, btree);
                console.log(node.id);
                console.log(status);
            }
        },
        onNodeUnchecked: function (event, node) {
            if (node.nodes != null) {
                // 这里需要控制，判断是否是因为子节点引起的父节点被取消选中
                // 如果是，则只管取消父节点就行了
                // 如果不是，则子节点需要被取消选中
                console.log(node.nodes);
                silentByChild = true;
                $.each(node.nodes, function(index, value) {
                    $this.treeview('uncheckNode', value.nodeId, {
                        silent : true
                    });
                    console.log(value);
                });
            } else {
                // 子节点被取消选中
                console.log(node);
                var parentNode = $this.treeview('getParent', node.nodeId);
                console.log(parentNode);
                var isAllUnchecked = true; // 是否全部取消选中

                // 子节点有一个选中，那么就不是全部取消选中
                var siblings = $this.treeview('getSiblings', node.nodeId);
                for ( var i in siblings) {
                    if (siblings[i].state.checked) {
                        isAllUnchecked = false;
                        break;
                    }
                }

                // 全部取消选中，那么省级节点恢复到默认状态
                if (isAllUnchecked) {
                    $this.treeview('unselectNode', parentNode.nodeId, {
                        silent : true,
                    });
                    $this.treeview('uncheckNode', parentNode.nodeId, {
                        silent : true,
                    });
                } else {
                    silentByChild = false;
                    $this.treeview('selectNode', parentNode.nodeId, {
                        silent : true,
                    });
                    $this.treeview('uncheckNode', parentNode.nodeId, {
                        silent : true,
                    });
                }
                status = view.getWINDViewControl().displayTreeNode(node.id, btree);
                console.log(node.id);
                console.log(status);
            }
            silentByChild = true;
        }
    });
}

$("#weatherType").change(function(){
    var arr = $("#weatherType").val();
    console.log(atree);
    model_id = sessionStorage.getItem("model_component_id");
    if(arr != 'null'){
        sessionStorage.setItem("model_component_id", model_id);
        var length = arr.length-1;
        var id = Number(arr[length]);
        var status = view.getWINDViewControl().displayTreeNode(id, atree);
        console.log(status);
    }
})

function initLevelCheckbox(root_id,atree) {
    // var $thr = $('#level_tab_'+index+' thead tr');
    var $thr = $('#level_tab thead tr');
    /*“全选/反选”复选框*/
    var $checkAll = $thr.find("input[name='checkAll']");
    $checkAll.prop('checked', true);
    var $tbr = $('#level_tab tbody tr');
    $checkAll.click(function (event) {
        /*将所有行的选中状态设成全选框的选中状态*/
        // $tbr.find('input').prop('checked', $(this).prop('checked'));
        /*并调整所有选中行的CSS样式*/
        if ($(this).prop('checked')) {
            $('#level_tab tbody tr').each(function () {
                var id = Number($(this).find("[name='id']").html());
                console.log($(this).find('input').prop('checked'));
                if(!$(this).find('input').prop('checked')){
                    $(this).find('input').prop('checked',true);
                    status = view.getWINDViewControl().displayTreeNode(id, atree);
                }
            });
        } else {
            $('#level_tab tbody tr').each(function () {
                var id = Number($(this).find("[name='id']").html());
                console.log($(this).find('input').prop('checked'));
                if($(this).find("[name='checkItem']").prop('checked')){
                    $(this).find('input').prop('checked',false);
                    status = view.getWINDViewControl().displayTreeNode(id, atree);
                }
            });
        }
        /*阻止向上冒泡，以防再次触发点击操作*/
        event.stopPropagation();
    });
    /*点击全选框所在单元格时也触发全选框的点击操作*/
    $thr.click(function () {
        $(this).find('input').click();
    });
    // var $tbr = $('#level_tab_'+index+' thead tr');
    $tbr.find('input').prop('checked', true);
    /*点击每一行的选中复选框时*/
    $tbr.find('input').click(function (event) {
        // alert('单选');
        /*调整选中行的CSS样式*/
        $(this).parent().parent().toggleClass('warning');
        /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
        $checkAll.prop('checked', $tbr.find('input:checked').length == $tbr.length ? true : false);
        var id = Number($(this).parent("td").parent("tr").find("[name='id']").html());
        if($(this).prop('checked')){
            status = view.getWINDViewControl().displayTreeNode(id, atree);
        }else{
            status = view.getWINDViewControl().displayTreeNode(id, atree);
        }
        /*阻止向上冒泡，以防再次触发点击操作*/
        event.stopPropagation();
    });
}

async function getAllComponentParamter() {
    $('#text_tag').val('Search');
    var arr = $('#fselect').val();
    if(arr.length != 1){
        alert('Please select one model');
        return false;
    }
    console.log(arr);
    var model_arr = arr[0].split('_');
    var model_id = model_arr[0];
    var version = model_arr[1];

    var obj = document.getElementById("type"); //定位id
    var index = obj.selectedIndex; // 选中索引
    var type = obj.options[index].value; // 选中值
    var big_obj = document.getElementById("big_type"); //构件&&构件组
    var big_index = big_obj.selectedIndex; // 选中索引
    var big_type = big_obj.options[big_index].value; // 选中值
    if(type == '0x00'){
        var uuid = $('#detail').val();
        entity_info = await data.getWINDDataQuerier().getEntityParameterL(uuid);
        console.log(entity_info);
        if(entity_info){
            console.log(entity_info);
            var detail = entity_info.entityId;
            type = '0x02';
        }else{
            return false;
        }
    }else{
        var detail = $('#detail').val();
    }

    addcloud();
    if(type !='' && detail != ''){
        $.ajax({
            url: "index.php?r=rf/rf/searchentity",
            type: "POST",
            data: {type: type,big_type: big_type,detail:detail,model_id:model_id},
            dataType: "json",
            beforeSend: function () {

            },
            success: function(data){
                view.getWINDViewControl().unhighlightAllEntities();
                $.each(data, function (name, value) {
                    view.getWINDViewControl().highlightAssignedEntities(value.uuid);
                })
                removecloud();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("状态码："+XMLHttpRequest.status);
                alert("状态:"+XMLHttpRequest.readyState);//当前状态,0-未初始化，1-正在载入，2-已经载入，3-数据进行交互，4-完成。
                alert("错误信息:"+XMLHttpRequest.statusText );
                alert("返回响应信息："+XMLHttpRequest.responseText );//这里是详细的信息
                alert("请求状态："+textStatus);
                alert(errorThrown);
            },
        });
    }
}

function zTreeBeforeClick(treeId, treeNode, clickFlag) {
    if(treeNode.type == '3'){
        console.log(treeNode)
        testDemo(treeNode);
    }
    if (treeNode.level == 0 ) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        zTree.expandNode(treeNode);
        return false;
    }
    return true;
}
function testDemo(treeNode) {
    var tab='<table class="table table-bordered">';
    tab+='<tr>';
    tab+="<td >Id</td><td >"+treeNode.entityId+"</td>";
    tab+='</tr>';
    tab+='<tr>';
    tab+="<td >Model</td><td style='word-break:break-all'>"+treeNode.model+"</td>";
    tab+='</tr>';
    tab+='<tr>';
    tab+="<td >Name</td><td >"+treeNode.name+"</td>";
    tab+='</tr>';
    tab+='<tr>';
    tab+="<td >Floor</td><td >"+treeNode.floor+"</td>";
    tab+='</tr>';
    tab+='<tr>';
    tab+="<td >Domain</td><td >"+WIND.getDomainName(treeNode.domain)+"</td>";
    tab+='</tr>';
    tab+='<tr>';
    tab+="<td >Category</td><td >"+WIND.getCategoryName(treeNode.category)+"</td>";
    tab+='</tr>';
    tab+="<tr><td colspan='2'><button type='button' class='btn btn-default' onclick='qrcode(\""+treeNode.modelId+"\",\""+treeNode.version+"\",\""+treeNode.version+"\",\""+treeNode.uuid+"\",\""+treeNode.entityId+"\",\""+treeNode.name+"\")'>二维码预览</button></td></tr>";
    // tab+="<tr><td colspan='2'><button type='button' class='btn btn-default' onclick='qrallcode(\""+treeNode.modelId+"\",\""+treeNode.version+"\")'>全部二维码预览</button></td></tr>";
    tab+="</table>";
    document.getElementById("info").innerHTML=tab;
    var arr = [];
    arr = treeNode.uuid;
    view.getWINDViewControl().unhighlightAllEntities()
    view.getWINDViewControl().highlightAssignedEntities(arr);
}
function addDiyDom(treeId, treeNode) {
    var spaceWidth = 5;
    var switchObj = $("#" + treeNode.tId + "_switch"),
        icoObj = $("#" + treeNode.tId + "_ico");
    switchObj.remove();
    icoObj.before(switchObj);

    if (treeNode.level > 1) {
        var spaceStr = "<span style='display: inline-block;width:" + (spaceWidth * treeNode.level)+ "px'></span>";
        switchObj.before(spaceStr);
    }
}
async function getAllStoreyParameter() {
    await data.getWINDDataQuerier().getAllStoreyParameterL();
}

async function getSelectEntities() {
    console.log(await view.getWINDViewRoaming().getSelectEntities());
    let entity = await view.getWINDViewRoaming().getSelectEntities();
}



async function selectqrcode() {
    $('#text_tag').val('QR-CODE');
    if($('#new_sidebar').css("display") == 'none'){
        document.getElementById("open_sidebar").click();
    }
    $('#info').empty();
    var program_id = $('#program_id').val();
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    var length = entity.length;
    // if(length > 10){
    //     alert('The number of choices must not be more than ten');
    //     return false;
    // }
    console.log(entity);
    var tab ='<div class="page-header"><h2>QR-Code</h2></div>';
    $("#info").append(tab);
    for ( var i = 0; i <entity.length; i++){
        entity_info = await data.getWINDDataQuerier().getEntityParameterL(entity[i]);
        model_id = entity_info.modelId;
        uuid = entity_info.uuid;
        entity_id = entity_info.entityId;
        version = entity_info.version;
        name = entity_info.name;
        floor = entity_info.floor;
        model = entity_info.model;
        type = entity_info.type;
        properties = entity_info.properties;
        pbu_info = model_id+'_'+version+'_'+uuid;
        for(var j =0; j<properties.length; j++){
            properties_info = properties[j];
            key = properties_info.key;
            group = properties_info.group;
            //Constraints
            if(group == 'Constraints'){
                if(key == 'Reference Level'){
                    floor = properties_info.value;
                }else if(key == 'Level'){
                    floor = properties_info.value;
                }else if(key == 'Base Constraint'){
                    floor = properties_info.value;
                }
            }
        }
        console.log(entity_info);
        // console.log(entity_info.properties);
        $.ajax({
            url: "index.php?r=task/model/qrbyprint",
            type: "POST",
            data: {program_id: program_id,uuid:uuid,model_id:model_id,entityId: entity_id,version:version,level:floor},
            dataType: "json",
            async: false,
            beforeSend: function () {

            },
            success: function(data){
                var content = data.content;
                console.log(data);
                var tag = [];
                tag["Uuid"] = 'uuid';
                tag["Block"] = 'block';
                tag["Level"] = 'level';
                tag["Part"] = 'part';
                tag["Part/Zone"] = 'part';
                tag["Serial Number"] = 'serial_number';
                tag["Unit Nos"] = 'unit_nos';
                tag["Unit"] = 'unit_nos';
                tag["Unit Type"] = 'unit_type';
                tag["Module Type"] = 'module_type';
                tag["PBU Type"] = 'pbu_type';
                tag["Element Type"] = 'element_type';
                tag["QR Code ID"] = 'element_name';
                tag["Element Name"] = 'element_name';
                tag["Level/Unit"] = 'level_unit';
                if(content.length > 0){
                    //#DAE3F3
                    tab="<div class='card card-info card-outline' >";
                    tab+="<div class='card-header' style='padding-bottom:0px;'><h3 class='box-title'>"+model+"</h3></div>";
                    tab+="<div class='card-body' style='overflow-x: auto'>";
                    tab+="<table align='center' frame='hsides' width='100%' border='1' cellpadding='4'>";
                    tab+="<tr><td align='left' height='20px' width='25%'>Block:</td><td align='center' width='42%'>"+data.block+"</td> <td rowspan='6' align='center' width='33%'><img src='"+data.filename+"' width='120' height='120'></td></tr>";
                    if(data.level){
                        tab+="<tr><td align='left' height='20px' width='25%'>Level:</td><td  align='center' width='42%'>"+data.level+"</td></td></tr>";
                    }else{
                        tab+="<tr><td align='left' height='20px' width='25%'>Level:</td><td  align='center' width='42%'>"+floor+"</td></td></tr>";
                    }
                    tab+="<tr><td align='left' height='20px' width='25%'>Part/Zone:</td><td align='center' width='42%'>"+data.part+"</td></td></tr>";
                    if(data.unit_type){
                        tab+="<tr><td align='left' height='20px' width='25%'>Unit:</td><td  align='center' width='42%'>"+data.unit_nos+"</td></td></tr>";
                    }else{
                        tab+="<tr><td align='left' height='20px' width='25%'>Unit:</td><td  align='center' width='42%'>NA</td></td></tr>";
                    }
                    if(data.element_type){
                        tab+="<tr><td align='left' height='20px' width='25%'>PBU Type:</td><td  align='center' width='42%'>"+data.element_type+"</td></td></tr>";
                    }else{
                        tab+="<tr><td align='left' height='20px' width='25%'>PBU Type:</td><td  align='center' width='42%'>"+type+"</td></td></tr>";
                    }
                    if(data.pbu_name){
                        tab+="<tr><td align='left' height='20px' width='25%'>QR Code ID:</td><td  align='center' width='42%'>"+data.pbu_name+"</td></td></tr>";
                    }else{
                        tab+="<tr><td align='left' height='20px' width='25%'>QR Code ID:</td><td  align='center' width='42%'>NA</td></td></tr>";
                    }
                    tab+="</table>";
                    tab+="<img src='img/RF.jpg' width='80' height='40' align='right'>";
                    tab+="</div>";
                    tab+="</div>";
                }else{
                    //#DAE3F3
                    tab="<div class='card card-info card-outline' >";
                    tab+="<div class='card-header' style='padding-bottom:0px;'><h3 class='box-title'>"+model+"</h3></div>";
                    tab+="<div class='card-body' style='overflow-x: auto'>";
                    tab+="<table align='center' frame='hsides' width='100%' border='1' cellpadding='4'>";
                    var length = content.length;
                    for(var i =0; i<content.length; i++){
                        var fixed_val = '';
                        var name = content[i].name;
                        var fixed = content[i].fixed;
                        var status = content[i].status;
                        if(status == '1'){
                            if(tag[fixed]){
                                var fixed_val = data[tag[fixed]];
                            }else{
                                for(let x  in entity_info){
                                    if(x == fixed){
                                        var fixed_val = entity_info[x];
                                    }
                                }
                                if(!fixed_val){
                                    for(var j =0; j<properties.length; j++){
                                        properties_info = properties[j];
                                        key = properties_info.key;
                                        if(key == fixed){
                                            if(key == 'Type'){
                                                console.log(entity_info);
                                            }
                                            var fixed_val = properties_info.value;
                                        }
                                    }
                                }
                            }
                        }else{
                            // tag[name]
                            var fixed_val = fixed;
                        }
                        if(fixed_val){
                            if(fixed_val.length > 30){
                                var fixed_val = fixed_val.substring(0,30)+'..';
                            }
                        }
                        if(i == 0){
                            tab+="<tr><td align='left' height='20px' width='25%'>"+name+"</td><td align='center' width='42%'>"+fixed_val+"</td> <td rowspan='"+length+"' align='center' width='33%'><img src='"+data.filename+"' width='120' height='120'></td></tr>";
                        }else{
                            tab+="<tr><td align='left' height='20px' width='25%'>"+name+"</td><td align='center' width='42%'>"+fixed_val+"</td></td></tr>";
                        }
                    }
                    tab+="</table>";
                    tab+="<img src='img/RF.jpg' width='80' height='40' align='right'>";
                    tab+="</div>";
                    tab+="</div>";
                }

                // document.getElementById("info").innerHTML= tab;
                $("#info").append(tab);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
}

async function export_self(entity_str) {
    var program_id = $('#program_id').val();
    addcloud();
    console.log(entity_str);
    jQuery.ajax({
        data: {entity_str:entity_str,program_id:program_id},
        type: 'post',
        url: './index.php?r=rf/rf/getmodeldata',
        dataType: 'json',
        success: function (data, textStatus) {
//                $('#qr_table').append("</br>Loading...");
            ajaxReadData(program_id,data.rowcnt, 0);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(XMLHttpRequest);
            alert(textStatus);
            alert(errorThrown);
        },
    });
}

async function pbuinfo() {
    $('#info').empty();
    var program_id = $('#program_id').val();
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    var length = entity.length;
    // if(length > 10){
    //     alert('The number of choices must not be more than ten');
    //     return false;
    // }
    console.log(entity);
    var tab ='<div class="page-header"><h2>Info</h2></div>';
    $("#info").append(tab);
    for ( var i = 0; i <entity.length; i++){
        entity_info = await data.getWINDDataQuerier().getEntityParameterL(entity[i]);
        model_id = entity_info.modelId;
        uuid = entity_info.uuid;
        entity_id = entity_info.entityId;
        version = entity_info.version;
        name = entity_info.name;
        floor = entity_info.floor;
        model = entity_info.model;
        type = entity_info.type;
        properties = entity_info.properties;
        for(var j =0; j<properties.length; j++){
            properties_info = properties[j];
            key = properties_info.key;
            group = properties_info.group;
            //Constraints
            if(group == 'Constraints'){
                if(key == 'Reference Level'){
                    floor = properties_info.value;
                }else if(key == 'Level'){
                    floor = properties_info.value;
                }else if(key == 'Base Constraint'){
                    floor = properties_info.value;
                }
            }
        }
        console.log(entity_info);
        // console.log(entity_info.properties);
        $.ajax({
            url: "index.php?r=task/model/pbuinfo",
            type: "POST",
            data: {program_id: program_id,uuid:uuid,model_id:model_id,entityId: entity_id,version:version,level:floor},
            dataType: "json",
            beforeSend: function () {

            },
            success: function(data){
                console.log(data);
                if(data.length>0){
                    //#DAE3F3
                    tab="<div class='card card-info card-outline' >";
                    tab+="<div class='card-header' style='padding-bottom:0px;'><h3 class='box-title'>"+model+"</h3></div>";
                    tab+="<div class='card-body' style='overflow-x: auto'>";
                    tab+="<table align='center' frame='hsides' width='100%' border='1' cellpadding='4'>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Properties</td><td align='center' width='70%'>Value</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Model Id:</td><td align='center' width='70%'>"+data[0].model_id+"</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Version:</td><td align='center' width='70%'>"+data[0].version+"</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Pbu Id:</td><td align='center' width='70%'>"+data[0].pbu_id+"</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Block:</td><td align='center' width='70%'>"+data[0].block+"</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Level:</td><td align='center' width='70%'>"+data[0].level+"</td></td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Unit Nos</td><td align='center' width='70%'>"+data[0].unit_nos+"</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Unit Type:</td><td align='center' width='70%'>"+data[0].unit_type+"</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Pbu Type:</td><td align='center' width='70%'>"+data[0].pbu_type+"</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Pbu Name:</td><td align='center' width='70%'>"+data[0].pbu_name+"</td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Serial Number:</td><td align='center' width='70%'>"+data[0].serial_number+"</td></td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Module Type:</td><td align='center' width='70%'>"+data[0].module_type+"</td></td></tr>";
                    tab+="<tr><td align='left' height='20px' width='30%'>Precast Plant:</td><td align='center' width='70%'>"+data[0].precast_plant+"</td></td></tr>";
                    var plan = data[0].plan;
                    var template_name = '';
                    console.log(plan);
                    $.each(plan, function (template_name, plan_list) {
                        tab+="<tr><td colspan='2' style='text-align: center'>"+template_name+"</td></tr>";
                        $.each(plan_list, function (index, list) {
                            console.log(list);
                            tab+="<tr><td align='left' height='20px' width='30%'>"+list.stage_name+" Start</td><td align='center' width='70%'>"+list.plan_start_date+"</td></td></tr>";
                            tab+="<tr><td align='left' height='20px' width='30%'>"+list.stage_name+" End</td><td align='center' width='70%'>"+list.plan_end_date+"</td></td></tr>";
                        })
                    })

                    // tab+="<tr><td align='left' height='20px' width='30%'>Start-Mould Fab:</td><td align='center' width='70%'>"+data[0].start_a+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Finish-Mould Fab:</td><td align='center' width='70%'>"+data[0].finish_a+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Start-Carcass Casting:</td><td align='center' width='70%'>"+data[0].start_b+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Finish-Carcass Casting:</td><td align='center' width='70%'>"+data[0].finish_b+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Start-PBU Delivery :</td><td align='center' width='70%'>"+data[0].start_c+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>NA:</td><td align='center' width='70%'>"+data[0].finish_c+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Start-Fit Out:</td><td align='center' width='70%'>"+data[0].start_d+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Finish-Fit Out:</td><td align='center' width='70%'>"+data[0].finish_d+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Start-Delivery TAC:</td><td align='center' width='70%'>"+data[0].start_e+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Installation:</td><td align='center' width='70%'>"+data[0].finish_e+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Start-RC Slab Construction A:</td><td align='center' width='70%'>"+data[0].start_f+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Finish-RC Slab Construction A:</td><td align='center' width='70%'>"+data[0].finish_f+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Start-RC Slab Construction B:</td><td align='center' width='70%'>"+data[0].start_g+"</td></td></tr>";
                    // tab+="<tr><td align='left' height='20px' width='30%'>Finish-RC Slab Construction :</td><td align='center' width='70%'>"+data[0].finish_g+"</td></td></tr>";
                    tab+="</table>";
                    tab+="</div>";
                    tab+="</div>";
                    console.log(tab);
                    // document.getElementById("info").innerHTML= tab;
                    $("#info").append(tab);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
}

async function selectqrchecklist() {
    $('#text_tag').val('Tasks');
    if($('#new_sidebar').css("display") == 'none'){
        document.getElementById("open_sidebar").click();
    }
    $('#info').empty();
    var program_id = $('#program_id').val();
    var template_id = $('#template_id').val();
    if(!template_id){
        alert('Please select template.');
        return false;
    }
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    var newArr = [];
    for(j = 0,len=entity.length; j < len; j++) {
        entity_info = await data.getWINDDataQuerier().getEntityParameterL(entity[j]);
        pbu_info = entity_info.modelId+'_'+entity_info.version+'_'+entity[j];
        newArr.push(pbu_info);
    }
    console.log(newArr);
    var length = newArr.length;
    if(length > 10){
        alert('The number of choices must not be more than ten');
        return false;
    }
    entity_str=newArr.join(',');
    console.log(entity);
    // var tab ='<div class="page-header"><h2>QA/QC</h2></div>';
    var tab ='';
    $.ajax({
        url: "index.php?r=task/model/qaqc",
        type: "POST",
        data: {uuid: entity_str,template_id:template_id,program_id:program_id},
        dataType: "json",
        beforeSend: function () {

        },
        success: function(data){
            $.each(data, function (name, value) {
                tab+="<div class='card card-info card-outline' style='background-color: #DAE3F3'>";
                tab+="<div class='card-body' style='overflow-x: auto'>";
                tab+="<table align='center' frame='hsides' width='100%'>";
                tab+="<tr><td ><h3>"+value.stage_name+"-"+value.task_name+"</h3></td><td style='white-space: nowrap;'><span class='badge "+value.status_css+" '>"+value.status_txt+"</span></td></tr>";
                tab+="<tr><td >Block: "+value.block+"</td><td style='white-space: nowrap;'></td></tr>";
                tab+="<tr><td >Level: "+value.level+"</td><td style='white-space: nowrap;'></td></tr>";
                tab+="<tr><td >Unit: "+value.unit+"</td><td style='white-space: nowrap;'></td></tr>";
                tab+="<tr><td >Name: "+value.name+"</td><td style='white-space: nowrap;'></td></tr>";
                tab+="<tr><td >Date: "+value.apply_time+"</td><td style='white-space: nowrap;'></td></tr>";
                if(value.data_id && value.check_id){
                    tab+="<tr><td ><h4>Checklist</h4></td><td style='white-space: nowrap;'></td></tr>";
                    tab+="<tr><td style='white-space: nowrap;'><a style='text-decoration:underline;color:#00ccff' onclick='downloadexcel(\""+value.check_id+"\",\""+value.data_id+"\")'>"+value.form_title+"</a></td></tr>";
                }
                tab+="</table>";
                tab+="</div>";
                tab+="</div>";

            });
            $("#info").append(tab);
            // console.log(tab);
            // // document.getElementById("info").innerHTML= tab;

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.status);
            //alert(XMLHttpRequest.readyState);
            //alert(textStatus);
        },
    });
}

var downloadexcel =  function(check_id,data_id){
    // window.location = "index.php?r=qa/qainspection/qaexport&check_id="+check_id+"&data_id="+data_id;
    window.open("index.php?r=qa/qainspection/qaexport&check_id="+check_id+"&data_id="+data_id,"_blank");
}

async  function pbuexcel (){
    var arr = $('#fselect').val();
    if(arr.length != 1){
        alert('Please select one model');
        return false;
    }
    console.log(arr);
    var model_arr = arr[0].split('_');
    var model_id = model_arr[0];
    var version = model_arr[1];
    var program_id = $('#program_id').val();
    window.location = "index.php?r=task/statistic/pbuexport&model_id="+model_id+"&version="+version+"&program_id="+program_id;
}

async function selectstatuslist() {
    $('#text_tag').val('Status');
    // closeModelDatas();
    view.getWINDViewControl().clearAllBatchEntities();
    $("#info").empty();
    let alpha = $('#alpha').val();
    if(alpha == ''){
        alpha = Number(111);
    }else{
        alpha = Number(alpha);
    }
    var arr = $('#fselect').val();
    var model_list = arr.join(',');

    var newArr = [];
    for(j = 0,len=arr.length; j < len; j++) {
        if(arr[j] != '1'){
            var model_arr = arr[j].split('_');
            var vote = {};
            vote['modelId'] = model_arr[0];
            vote['version'] = model_arr[1];
            newArr.push(vote);
        }
    }

    var template_id = $('#template_id').val();
    if(!template_id){
        alert('Please select template.');
        return false;
    }
    addcloud();
    var program_id = $('#program_id').val();
    console.log(newArr);

    // if (model_list) {
    //     await data.getWINDDataLoader().openModelDatas(newArr,true);//打开对应模型id的模型数据
    // }
    var component_list = await data.getWINDDataQuerier().getAllComponentParameterL();
    //getAllRelationParameterL
    var relation_list = await data.getWINDDataQuerier().getAllRelationParameterL();
    console.log(component_list);
    console.log(relation_list);
    batch_id = 199202;
    for(let key  in component_list){
        var uuid_list = new Array();
        var parameter = component_list[key].parameter;
        var i = 0;
        for(let index  in parameter){
            uuid_list[i] = parameter[index].uuid;
            i++;
        }
        console.log(uuid_list);
        status = view.getWINDViewControl().createBatchEntities(batch_id,uuid_list);
        console.log(status);
        if(status == 'true'){
            view.getWINDViewControl().coloringBatchEntities(batch_id, 126, 127, 126, alpha);
        }
        batch_id++;
    }
    if($('#new_sidebar').css("display") == 'none'){
        document.getElementById("open_sidebar").click();
    }
    var tab ='<div class="page-header"><h2>Status</h2></div>';
    $("#info").append(tab);
    $.ajax({
         data: {model_list: model_list,template_id:template_id,program_id:program_id},
         url: "index.php?r=task/task/querycntprogressmodel",
         type: "POST",
         dataType: "json",
        beforeSend: function () {
         },
         success: function (data) {
             if(alpha == ''){
                alpha = Number(111);
             }else{
                alpha = Number(alpha);
             }
             tab="<div class='card card-info card-outline' >";
             tab+="<div class='card-body' style='overflow-x: auto'>";
             tab+="<table id='status_tab' align='center' frame='hsides' width='100%' class='table-bordered'>";
             // tab+="<thead><tr><th><input type='checkbox' id='checkAll' name='checkAll' /></th><th align='center'>Stages</th><th align='center'>Color</th><th align='center'>Quantity</th></tr></thead>";
             tab+="<thead><tr><th align='center'>Stages</th><th align='center'>Color</th><th align='center'>Quantity</th></tr></thead>";
             tab+="<tbody>";
             $.each(data, function (name, value) {
                 var guid_list = value.guid_list;
                 var rand = getRandomString(8);
                 if(guid_list.length != 0){
                     batch_id = rand;
                     status = view.getWINDViewControl().createBatchEntities(batch_id,guid_list);
                     var rgb = hexToRgb(value.stage_color);
                     console.log(111111);
                     console.log(status);
                     if(status == 'true'){
                         view.getWINDViewControl().coloringBatchEntities(batch_id, rgb.r, rgb.g, rgb.b, alpha);
                     }
                 }
                 // tab+="<tr><td align='center'><input type='checkbox' name='checkItem' /></td><td style='white-space: nowrap;' name='stage_id' align='center'>"+value.stage_id+"</td><td style='white-space: nowrap;' name='batch_id' align='center'>"+rand+"</td><td style='white-space: nowrap;' align='center'>"+value.stage_name+"</td><td style='background-color:"+value.stage_color+"' align='center'></td><td style='white-space: nowrap;' align='center'>"+value.guid_cnt+"</td></tr>";
                 tab+="<tr><td style='white-space: nowrap;' align='center'>"+value.stage_name+"</td><td style='background-color:"+value.stage_color+"' align='center'></td><td style='white-space: nowrap;' align='center'>"+value.guid_cnt+"</td></tr>";
                 // document.getElementById("info").innerHTML= tab;
             });
             tab+="</tbody>";
             tab+="</table>";
             tab+="</div>";
             tab+="</div>";
             $("#info").append(tab);

             //默认全选
             var $thr = $('#status_tab thead tr');
             var $checkAll = $thr.find('input');
             $checkAll.prop('checked',true);
             var $tbr = $('#status_tab tbody tr');
             $tbr.find('input').prop('checked', true);

             // initTableCheckbox();
            removecloud();
         },
         error: function () {
             $('#msgbox').addClass('alert-danger fa-ban');
             $('#msginfo').html('系统错误');
             $('#msgbox').show();
         }
     });

}

async function colorComponent(stage_id,batch_id) {

    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    console.log(model);
    addcloud();
    var template_id = $('#template_id').val();
    if(!template_id){
        alert('Please select template.');
        return false;
    }
    var program_id = $('#program_id').val();

    var component_list = await data.getWINDDataQuerier().getAllComponentParameterL();
    console.log(component_list);

    $.ajax({
        data: {model_id: arr[0],stage_id:stage_id,program_id:program_id},
        url: "index.php?r=task/task/querystagemodel",
        type: "POST",
        dataType: "json",
        beforeSend: function () {
        },
        success: function (data) {
            if(alpha == ''){
                alpha = Number(111);
            }else{
                alpha = Number(alpha);
            }
            view.getWINDViewControl().removeBatchEntities(batch_id);
            guid_arr = [];
            $.each(data, function (name, value) {
                var guid_list = value.guid_list;
                for(let index  in guid_list){
                    guid_arr[index] = guid_list[index].guid;
                }
                if(guid_arr.length != 0){
                    status = view.getWINDViewControl().createBatchEntities(batch_id,guid_arr);
                    var rgb = hexToRgb(value.stage_color);
                    if(status == 'true'){
                        view.getWINDViewControl().coloringBatchEntities(batch_id, rgb.r, rgb.g, rgb.b, alpha);
                    }
                }
            });
            removecloud();
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });

}

async function uncolorComponent(stage_id,batch_id) {

    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    console.log(model);
    addcloud();
    var template_id = $('#template_id').val();
    if(!template_id){
        alert('Please select template.');
        return false;
    }
    var program_id = $('#program_id').val();

    $.ajax({
        data: {model_id: arr[0],stage_id:stage_id,program_id:program_id},
        url: "index.php?r=task/task/querystagemodel",
        type: "POST",
        dataType: "json",
        beforeSend: function () {
        },
        success: function (data) {
            if(alpha == ''){
                alpha = Number(111);
            }else{
                alpha = Number(alpha);
            }
            view.getWINDViewControl().removeBatchEntities(batch_id);
            guid_arr = [];
            $.each(data, function (name, value) {
                var guid_list = value.guid_list;
                for(let index  in guid_list){
                    guid_arr[index] = guid_list[index].guid;
                }
                if(guid_arr.length != 0){
                    status = view.getWINDViewControl().createBatchEntities(batch_id,guid_arr);
                }
                if(status == 'true'){
                    view.getWINDViewControl().coloringBatchEntities(batch_id, 126, 127, 126, alpha);
                }
            });
            removecloud();
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });

}

async function colorAllComponent() {

    closeModelDatas();
    view.getWINDViewControl().removeBatchEntities('334433');
    view.getWINDViewControl().removeBatchEntities('199202');
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    console.log(model);
    addcloud();
    var template_id = $('#template_id').val();
    if(!template_id){
        alert('Please select template.');
        return false;
    }
    var program_id = $('#program_id').val();
    if (model) {
        await data.getWINDDataLoader().openModelData(arr[0],arr[1],true);//打开对应模型id的模型数据
    }

    var program_id = $('#program_id').val();

    var component_list = await data.getWINDDataQuerier().getAllComponentParameterL();
    console.log(component_list);
    var uuid_list = new Array();
    for(let key  in component_list){
        var parameter = component_list[key].parameter;
        var i = 0;
        for(let index  in parameter){
            uuid_list[i] = parameter[index].uuid;
            i++;
        }
    }
    console.log(uuid_list);
    batch_id = '199202'
    status = view.getWINDViewControl().createBatchEntities(batch_id,uuid_list);
    if(status == 'true'){
        view.getWINDViewControl().coloringBatchEntities(batch_id, 126, 127, 126, alpha);
    }

    $.ajax({
        data: {model_id: arr[0],template_id:template_id,program_id:program_id},
        url: "index.php?r=task/task/querycntprogressmodel",
        type: "POST",
        dataType: "json",
        beforeSend: function () {
        },
        success: function (data) {
            if(alpha == ''){
                alpha = Number(111);
            }else{
                alpha = Number(alpha);
            }
            $.each(data, function (name, value) {
                var guid_list = value.guid_list;

                if(guid_list.length != 0){
                    batch_id = '334433';
                    status = view.getWINDViewControl().createBatchEntities(batch_id,guid_list);
                    var rgb = hexToRgb(value.stage_color);
                    if(status == 'true'){
                        view.getWINDViewControl().coloringBatchEntities(batch_id, rgb.r, rgb.g, rgb.b, alpha);
                    }
                }
            });
            removecloud();
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
}

async function uncolorAllComponent() {
    if(alpha == ''){
        alpha = Number(111);
    }else{
        alpha = Number(alpha);
    }
    closeModelDatas();
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    console.log(model);
    addcloud();
    var template_id = $('#template_id').val();
    if(!template_id){
        alert('Please select template.');
        return false;
    }
    var program_id = $('#program_id').val();
    if (model) {
        await data.getWINDDataLoader().openModelData(arr[0],arr[1],true);//打开对应模型id的模型数据
    }
    view.getWINDViewControl().removeBatchEntities('334433');
    view.getWINDViewControl().removeBatchEntities('199202');
    var program_id = $('#program_id').val();

    var component_list = await data.getWINDDataQuerier().getAllComponentParameterL();
    console.log(component_list);
    var uuid_list = new Array();
    for(let key  in component_list){
        var parameter = component_list[key].parameter;
        var i = 0;
        for(let index  in parameter){
            uuid_list[i] = parameter[index].uuid;
            i++;
        }
    }
    console.log(uuid_list);
    batch_id = '199202'
    status = view.getWINDViewControl().createBatchEntities(batch_id,uuid_list);
    if(status == 'true'){
        view.getWINDViewControl().coloringBatchEntities(batch_id, 126, 127, 126, alpha);
    }
    removecloud();
}

//十六进制转RGB颜色
function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

//随机数
function getRandomString(len) {
    len = len || 32;
    var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'; // 默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1
    var maxPos = $chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
        pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}

function initTableCheckbox() {
    var $thr = $('#status_tab thead tr');
    console.log($thr);
    /*“全选/反选”复选框*/
    var $checkAll = $thr.find('input');
    $checkAll.click(function (event) {
        // alert('全选');
        /*将所有行的选中状态设成全选框的选中状态*/
        $tbr.find('input').prop('checked', $(this).prop('checked'));
        /*并调整所有选中行的CSS样式*/
        if ($(this).prop('checked')) {
            colorAllComponent();
        } else {
            uncolorAllComponent();
        }
        /*阻止向上冒泡，以防再次触发点击操作*/
        event.stopPropagation();
    });
    /*点击全选框所在单元格时也触发全选框的点击操作*/
    $thr.click(function () {
        $(this).find('input').click();
    });
    var $tbr = $('#status_tab tbody tr');
    /*点击每一行的选中复选框时*/
    $tbr.find('input').click(function (event) {
        /*调整选中行的CSS样式*/
        $(this).parent().parent().toggleClass('warning');
        /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
        $checkAll.prop('checked', $tbr.find('input:checked').length == $tbr.length ? true : false);
        var stage_id = $(this).parent("td").parent("tr").find("[name='stage_id']").html();
        var batch_id = $(this).parent("td").parent("tr").find("[name='batch_id']").html();
        if($(this).prop('checked')){
            // alert('选中');
            colorComponent(stage_id,batch_id);
        }else{
            // alert('取消选中');
            uncolorComponent(stage_id,batch_id);
        }
        /*阻止向上冒泡，以防再次触发点击操作*/
        event.stopPropagation();
    });
    /*点击每一行时也触发该行的选中操作*/
    // $tbr.click(function () {
    //     $(this).find('input').click();
    //     if($(this).find('input:checked').prop('checked')){
    //         alert('选中');
    //         alert($(this).find("[name='stage_id']").html());
    //     }else{
    //         alert('取消选中');
    //         alert($(this).find("[name='stage_id']").html());
    //     }
    // });
}

//查询
function itemQuery(model_id,version) {
    $('#model_id').val(model_id);
    $('#version').val(version);
    var objs = document.getElementById("_query_form").elements;
    var i = 0;
    var cnt = objs.length;
    var obj;
    var url = '';
    for (i = 0; i < cnt; i++) {
        obj = objs.item(i);
        url += '&' + obj.name + '=' + obj.value;
    }
    example2.condition = url;
    example2.refresh();
}

//打印预览
function qrcode(model_id,version,uuid,entityId,type) {
    // alert(model_id);
    // alert(uuid);
    var program_id = $('#program_id').val();
    // window.location = "index.php?r=rf/rf/qrbyprint&model_id="+model_id+"&uuid="+uuid+"&entityId="+entityId+"&type="+type+"&program_id="+program_id;
    window.open("index.php?r=rf/rf/qrbyprint&model_id="+model_id+"&version="+version+"&uuid="+uuid+"&entityId="+entityId+"&type="+type+"&program_id="+program_id,"_blank");
}

//全部构件打印预览
async function qrallcode(model_id,version) {
    // alert(model_id);
    // alert(uuid);
    var program_id = $('#program_id').val();

    window.open("index.php?r=rf/rf/allqrbyprint&model_id="+model_id+"&version="+version+"&program_id="+program_id,"_blank");
}

var per_read_cnt = 20;
//全部构件打印预览
async function allcode() {
    selectqrcode();

    $('#text_tag').val('Qr Code');
    var program_id = $('#program_id').val();

    var entity = await view.getWINDViewRoaming().getSelectEntities();
    addcloud();
    var component_list = await data.getWINDDataQuerier().getAllComponentParameterL();
    var newArr = [];
    for(j = 0,len=entity.length; j < len; j++) {
        entity_info = await data.getWINDDataQuerier().getEntityParameterL(entity[j]);
        pbu_info = entity_info.modelId+'_'+entity_info.version+'_'+entity[j];
        newArr.push(pbu_info);
    }
    // console.log(component_list);
    // return;
    // var newArr = [];
    // for(j = 0,len=entity.length; j < len; j++) {
    //     entity_info = await data.getWINDDataQuerier().getEntityParameterL(entity[j]);
    //     pbu_info = entity_info.modelId+'_'+entity_info.version+'_'+entity[j];
    //     newArr.push(pbu_info);
    // }
    console.log(newArr);
    var length = newArr.length;

    if(length == 0){
        alert('Please select the components.');
        return false;
    }
    if(length > 1000){
        alert('The number of choices must not be more than a thousand');
        return false;
    }
    entity_str=newArr.join(',');
    console.log(entity_str);
    jQuery.ajax({
        data: {entity_str:entity_str,program_id:program_id},
        type: 'post',
        url: './index.php?r=rf/rf/getmodeldata',
        dataType: 'json',
        success: function (data, textStatus) {
//                $('#qr_table').append("</br>Loading...");
            ajaxReadData(program_id,data.rowcnt, 0);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(XMLHttpRequest);
            alert(textStatus);
            alert(errorThrown);
        },
    });
}

/*
 * 加载数据
 */
var ajaxReadData = function (program_id,rowcnt, startrow){//alert('aa');

    jQuery.ajax({
        data: {program_id:program_id, startrow: startrow, per_read_cnt:per_read_cnt},
        type: 'post',
        url: './index.php?r=rf/rf/readmodeldata',
        dataType: 'json',
        success: function (data, textStatus) {
            // for (var o in data) {
            //     $('#prompt').append("</br>Row "+o+" : "+data[o].msg);
            //     $('#qr_table').append("<tr><td colspan='2' align='center'><h1 style='text-align: center'>"+data[o].type+"</h1></td></tr>");
            //     $('#qr_table').append("<tr><td style='white-space: nowrap;'><span style='font-size: 15px;font-weight:bold;margin-right: 5px '>Model Id:</span><span>"+data[o].model_id+"</span></td><td rowspan='3' align='right'><img src='"+data[o].filename+"'></td> </tr>");
            //     $('#qr_table').append("<tr><td><span style='font-size: 15px;font-weight:bold;margin-right: 5px'>entityId:</span><span>"+data[o].entityId+"</span></td> </tr>");
            //     $('#qr_table').append("<tr><td><span style='font-size: 15px;font-weight:bold;margin-right: 5px'>uuid:</span><span>"+data[o].uuid+"</span></td> </tr>");
            // }
            if (rowcnt > startrow) {
                ajaxReadData(program_id, rowcnt, startrow+per_read_cnt);
            }else{
                clearCache(program_id,version,model_id);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            //alert(XMLHttpRequest);
            //alert(textStatus);
            //alert(errorThrown);
        },
    });
    return false;
}

/*
 * 清除缓存，下载压缩包
 */
var clearCache = function(program_id){//alert('aa');
    removecloud();
    window.location = "index.php?r=rf/rf/clearcache";
    // jQuery.ajax({
    //     data: {program_id:program_id, version:version, model_id:model_id},
    //     type: 'post',
    //     url: './index.php?r=rf/rf/clearcache',
    //     dataType: 'json',
    //     success: function (data, textStatus) {
    //         removecloud();
    //     },
    //     error: function(XMLHttpRequest, textStatus, errorThrown){
    //         //alert(XMLHttpRequest);
    //         //alert(textStatus);
    //         //alert(errorThrown);
    //     },
    // });
    // return false;
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
    // alert(type);
    // alert(result);
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

    let setLeftMouseOperationUI = document.getElementById("setLeftMouseOperation");
    setLeftMouseOperationUI.add(new Option('Tools'));
    setLeftMouseOperationUI.add(new Option('Select', 'pick'));//点选
    setLeftMouseOperationUI.add(new Option('Rotate', 'rotate'));//旋转
    setLeftMouseOperationUI.add(new Option('Move', 'pan'));//平移
    setLeftMouseOperationUI.addEventListener("change", function (event) {
        leftmouseOperationUpdate(event.target.value)
    }, false);

    let revertHomePositionUI = document.getElementById("revertHomePosition");
    revertHomePositionUI.addEventListener("click", revertHomePosition, false);
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
    $('#text_tag').val('Reset');
    view.getWINDViewControl().showEachComponents();
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

async function setcomponent() {
    var program_id = $('#program_id').val();

    var entity = await view.getWINDViewRoaming().getSelectParameters();
    var newArr = [];
    for(j = 0,len=entity.length; j < len; j++) {
        entity_info = entity[j];
        pbu_info = entity_info.modelId+'_'+entity_info.version+'_'+entity_info.uniqueid;
        newArr.push(pbu_info);
    }
    console.log(newArr);
    var length = newArr.length;
    if(length == 0){
        alert('Please select the components.');
        return false;
    }
    entity_str=newArr.join(',');

    jQuery.ajax({
        data: {entity_str:entity_str},
        type: 'post',
        url: './index.php?r=task/model/saveentity',
        dataType: 'json',
        success: function (data, textStatus) {
            var modal = new TBModal();
            modal.title = "<?php echo Yii::t('common', 'edit'); ?>";
            modal.url = "index.php?r=task/model/setentity&program_id="+program_id;
            modal.modal();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(XMLHttpRequest);
            alert(textStatus);
            alert(errorThrown);
        },
    });
}

var per_pbu_cnt = 80;
//模型构件导出
async function exportcomponent() {
    var export_id = $('#export_template').val();
    if(export_id == 0){
        alert('Please select Export Template');
        return false;
    }
    var template_id = $('#date_template').val();
    if(template_id == 0){
        alert('Please select Template');
        return false;
    }
    // let modellistUI = document.getElementById("modellist");
    // let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    // var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    // var arr = id_version.split('_');
    // var model_id = model._id;
    // var version = model._version;
    var program_id = $('#program_id').val();
    var entity = await view.getWINDViewRoaming().getSelectParameters();
    var newArr = [];
    var length = entity.length;
    console.log(length);
    if(length == 0){
        alert('Please select the components.');
        return false;
    }
    // if(length > 1000){
    //     alert('The number of choices must not be more than five hundred');
    //     return false;
    // }
    addcloud();
    for(j = 0,len=entity.length; j < len; j++) {
        entity_info = entity[j];
        pbu_info = entity_info.modelId+'_'+entity_info.version+'_'+entity_info.uniqueid;
        newArr.push(pbu_info);
    }
    entity_str=newArr.join(',');
    console.log(entity_str);
    // return false;
    jQuery.ajax({
        data: {entity_str:entity_str, program_id:program_id},
        type: 'post',
        url: './index.php?r=task/model/getpbuinfo',
        dataType: 'json',
        success: function (data, textStatus) {
//                $('#qr_table').append("</br>Loading...");
            savepbuinfo(program_id,data.rowcnt, 0);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(XMLHttpRequest);
            alert(textStatus);
            alert(errorThrown);
        },
    });
}

/*
 * 加载数据
 */
var savepbuinfo = function (program_id, rowcnt, startrow){//alert('aa');

    jQuery.ajax({
        data: {program_id:program_id,  startrow: startrow, per_read_cnt:per_pbu_cnt},
        type: 'post',
        url: './index.php?r=task/model/savepbuinfo',
        dataType: 'json',
        success: function (data, textStatus) {
            // for (var o in data) {
            //     $('#prompt').append("</br>Row "+o+" : "+data[o].msg);
            //     $('#qr_table').append("<tr><td colspan='2' align='center'><h1 style='text-align: center'>"+data[o].type+"</h1></td></tr>");
            //     $('#qr_table').append("<tr><td style='white-space: nowrap;'><span style='font-size: 15px;font-weight:bold;margin-right: 5px '>Model Id:</span><span>"+data[o].model_id+"</span></td><td rowspan='3' align='right'><img src='"+data[o].filename+"'></td> </tr>");
            //     $('#qr_table').append("<tr><td><span style='font-size: 15px;font-weight:bold;margin-right: 5px'>entityId:</span><span>"+data[o].entityId+"</span></td> </tr>");
            //     $('#qr_table').append("<tr><td><span style='font-size: 15px;font-weight:bold;margin-right: 5px'>uuid:</span><span>"+data[o].uuid+"</span></td> </tr>");
            // }
            startrow = startrow+per_pbu_cnt;
            if (rowcnt > startrow) {
                savepbuinfo(program_id,rowcnt, startrow);
            }else{
                exportpbu(program_id,rowcnt);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            //alert(XMLHttpRequest);
            //alert(textStatus);
            //alert(errorThrown);
        },
    });
    return false;
}

/*
 * 清除缓存，下载压缩包
 */
var exportpbu = function(program_id,rowcnt){
    var export_id = $('#export_template').val();
    // var template_id = $('#date_template').val();
    if(rowcnt<200){
        removecloud();
        window.location = "index.php?r=task/model/exportpbu&program_id="+program_id+"&export_id="+export_id+"&rowcnt="+rowcnt;
    }else{
        jQuery.ajax({
            data: {program_id:program_id,rowcnt:rowcnt,export_id:export_id},
            type: 'post',
            url: './index.php?r=task/model/exportpbu',
            dataType: 'json',
            success: function (data, textStatus) {
                removecloud();
                alert('Create export task!');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },
        });
    }
}

var exporttask = function(){
    var program_id = $('#program_id').val();
    $.ajax({
        data: {program_id:program_id},
        url: "index.php?r=task/model/exporttask",
        type: "POST",
        dataType: "json",
        beforeSend: function () {
        },
        success: function (data) {
            $("#info").empty();
            tab="<table id='export_task' align='center' frame='hsides' width='100%' class='table-bordered'>";
            tab+="<thead><tr><th style='text-align:center' >Time</th><th style='text-align:center'>Status</th><th style='text-align:center'>Action</th></tr></thead>";
            tab+="<tbody>";
            $.each(data, function (name, value) {
                if(value.status == '0'){
                    status = 'Processing';
                    tab+="<tr><td style='white-space: nowrap;' align='center'>"+value.record_time+"</td><td  align='center'>"+status+"</td><td style='white-space: nowrap;' align='center'></td></tr>";
                }else{
                    status = 'Done';
                    tab+="<tr><td style='white-space: nowrap;' align='center'>"+value.record_time+"</td><td  align='center'>"+status+"</td><td style='white-space: nowrap;' align='center'><button type='button' class='btn btn-default' onclick='exportdownload(\""+value.id+"\")'>Download</button></td></tr>";
                }
            });
            tab+="</tbody>";
            tab+="</table>";
            $("#info").append(tab);

        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
}
var exportdownload = function(id){
    window.location = "index.php?r=task/model/exportdownload&id="+id;
}

async function tabdemo() {
    $('#text_tag').val('Export');
    if($('#new_sidebar').css("display") == 'none'){
        document.getElementById("open_sidebar").click();
    }
    var program_id = $('#program_id').val();
    $('#exportTab a').click(function (e) {
        console.log($(this).context);
        e.preventDefault();//阻止a链接的跳转行为
        $(this).tab('show');//显示当前选中的链接及关联的content
        var tab_text = $(this).context.text;
    })
    $('#info').empty();
    var tab = '<div class="row"><div class="col-12" style="padding-left: 0px;padding-right: 0px;"><div class="card card-info card-outline card-outline-tabs" style="margin-bottom: 5px;"><div class="card-header p-0 border-bottom-0">\n' +
        '            <ul class="nav nav-tabs" role="tablist" id="exportTab">\n' +
        '                <li role="presentation" class="nav-item"><a class="nav-link active" href="#export_settings" role="tab" data-toggle="tab"> Export Settings</a></li>\n' +
        '                <li role="presentation" class="nav-item"><a class="nav-link" href="#export_tasks' +
        '" role="tab" data-toggle="tab"> Export Tasks</a></li>\n' +
        '            </ul></div>\n';
    $("#info").append(tab);
    var export_content = "<div class='tab-content'><div class='tab-pane active' id='export_settings'>";
    export_content+= "<div class='row' style='margin-bottom: 5px;'>";
    export_content+= "<select id='export_template' class='form-control input-sm' onchange='change_exporttemplate()' style='width: auto;'>";
    export_content+= "<option value='0'>--Export Template--</option>";
    $.ajax({
        data: {program_id:program_id},
        url: "index.php?r=task/import/queryexporttemplate",
        type: "POST",
        dataType: "json",
        async: false,
        beforeSend: function () {
        },
        success: function (data) {
            $.each(data, function (name, value) {
                export_content+="<option value='"+name+"'>"+value+"</td>";
            });
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
    export_content+= "</select>";
    export_content+= "<button class='btn btn-primary btn-sm' onclick='save_export()'  style='margin-left:5px;float: left'>Save</button><button  class='btn btn-primary btn-sm' id='del_export' onclick='del_export()' style='margin-left:5px;float: right;display: none'>Delete</button><button  class='btn btn-primary btn-sm' onclick='exportcomponent()' style='margin-left:5px;float: right'>Export</button></div>";
    export_content+= "<form id='export_form'><div class='row'><table id='set_table' align='left' frame='hsides' width='100%' class='table-bordered level-tab'><input type='hidden'  name='Program[template_id]' value=''><input type='hidden'  name='Program[program_id]' value='"+program_id+"'>";
    export_content+= "<thead><tr><td  align='center'>Column</td><td  align='center'>Property</td><td  align='center'>Export Value</td></tr></thead>";
    export_content+= "<tr><td  align='center'>A</td><td  align='center'>Model Id</td><td  align='center'><input type='hidden' id='val_A' name='Export[A][]' value=''>-</td></tr>";
    export_content+= "<tr><td  align='center'>B</td><td  align='center'>GUID</td><td  align='center'><input type='hidden' id='val_B' name='Export[B][]' value=''>-</td></tr>";
    export_content+= "<tr><td  align='center'>C</td><td  align='center'>Block</td><td  align='center'><input type='hidden' id='val_C' name='Export[C][]' value=''><a id='set_C' onclick='setParam(\"C\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>D</td><td  align='center'>Level</td><td  align='center'><input type='hidden' id='val_D' name='Export[D][]' value=''><a id='set_D' onclick='setParam(\"D\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>E</td><td  align='center'>Unit No.</td><td  align='center'><input type='hidden' id='val_E' name='Export[E][]' value=''><a id='set_E' onclick='setParam(\"E\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>F</td><td  align='center'>Zone</td><td  align='center'><input type='hidden' id='val_F' name='Export[F][]' value=''><a id='set_F' onclick='setParam(\"F\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>G</td><td  align='center'>Unit Type</td><td  align='center'><input type='hidden' id='val_G' name='Export[G][]' value=''><a id='set_G' onclick='setParam(\"G\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>H</td><td  align='center'>Serial No</td><td  align='center'><input type='hidden' id='val_H' name='Export[H][]' value=''><a id='set_H' onclick='setParam(\"H\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>I</td><td  align='center'>Element Type</td><td  align='center'><input type='hidden' id='val_I' name='Export[I][]' value=''><a id='set_I' onclick='setParam(\"I\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>J</td><td  align='center'>Element Name</td><td  align='center'><input type='hidden' id='val_J' name='Export[J][]' value=''><a id='set_J' onclick='setParam(\"J\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>K</td><td  align='center'>Orientation</td><td  align='center'><input type='hidden' id='val_K' name='Export[K][]' value=''><a id='set_K' onclick='setParam(\"K\")'>-</a></td></tr>";
    export_content+= "<tr><td  align='center'>L</td><td  align='center'>Other</td><td  align='center'><input type='hidden' id='val_L' name='Export[L][]' value=''><a id='set_L' onclick='setParam(\"L\")'>-</a></td></tr>";
    export_content+= "</table></div>";
    // export_content+= "<div class='row' id='stage_table' style='margin-top: 5px;margin-bottom: 5px;text-align: center'>";
    // export_content+= "<select><option value='"+value.id+"'>"+value.name+"</option></select>";
    // export_content+= "<select id='date_template' class='form-control' style='text-align: center;text-align-last: center;' onchange='change_template()'>";
    // export_content+= "<option value='0'>--Template--</option>";
    // $.ajax({
    //     data: {program_id:program_id},
    //     url: "index.php?r=task/import/querytemplate",
    //     type: "POST",
    //     dataType: "json",
    //     async: false,
    //     beforeSend: function () {
    //     },
    //     success: function (data) {
    //         $.each(data, function (name, value) {
    //             export_content+="<option value='"+name+"'>"+value+"</td>";
    //         });
    //     },
    //     error: function () {
    //         $('#msgbox').addClass('alert-danger fa-ban');
    //         $('#msginfo').html('系统错误');
    //         $('#msgbox').show();
    //     }
    // });
    // export_content+= "</select>";
    // export_content+= "</div>";
    export_content+= "<table id='plan_table' align='left' frame='hsides' width='100%' class='table-bordered level-tab'>";
    export_content+= "</table>";
    export_content+= "</form></div>";


    export_content+= "<div class='tab-pane' id='export_tasks'><div class='dataTables_length'>";
    export_content+="<table id='export_task' align='center' frame='hsides' width='100%' class='table-bordered'>";
    export_content+="<thead><tr><th style='text-align:center' >Time</th><th style='text-align:center'>Status</th><th style='text-align:center'>Action</th></tr></thead>";
    export_content+="<tbody>";
    $.ajax({
        data: {program_id:program_id},
        url: "index.php?r=task/model/exporttask",
        type: "POST",
        dataType: "json",
        async: false,
        beforeSend: function () {
        },
        success: function (data) {
            $.each(data, function (name, value) {
                if(value.status == '0'){
                    status = 'Processing';
                    export_content+="<tr><td style='white-space: nowrap;' align='center'>"+value.record_time+"</td><td  align='center'>"+status+"</td><td style='white-space: nowrap;' align='center'></td></tr>";
                }else{
                    status = 'Done';
                    export_content+="<tr><td style='white-space: nowrap;' align='center'>"+value.record_time+"</td><td  align='center'>"+status+"</td><td style='white-space: nowrap;' align='center'><button type='button' class='btn btn-primary btn-sm' onclick='exportdownload(\""+value.id+"\")'>Download</button></td></tr>";
                }
            });
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
    export_content+="</tbody>";
    export_content+="</table>";
    export_content+= "<div class='row' style='margin-top:5px;text-align: center;'><div class='col-12'><button class='btn btn-primary btn-sm' onclick='refresh_task()'>Refresh</button></div></div></div></div>";
    export_content+= "</div></div></div></div>";

    $("#info").append(export_content);
}

async function change_template(){
    var template_id = $('#date_template').val();
    $("#plan_table").empty();
    $.ajax({
        data: {template_id:template_id},
        url: "index.php?r=task/import/querystage",
        type: "POST",
        dataType: "json",
        async: false,
        beforeSend: function () {
        },
        success: function (data) {
            var index = 0;
            var a_index = 0;
            $.each(data, function (name, value) {
                if(index < 14){
                    var stage_index = String.fromCharCode('M'.charCodeAt(0) + index);
                    $("#plan_table").append("<tr><td  align='center'>"+stage_index+"</td><td  align='center'>"+value+"</td><td  align='center'>Start date</td></tr>");
                    index++;
                    var stage_index = String.fromCharCode('M'.charCodeAt(0) + index);
                    $("#plan_table").append("<tr><td  align='center'>"+stage_index+"</td><td  align='center'>"+value+"</td><td  align='center'>End date</td></tr>");
                    index++;
                }else{
                    var stage_index = 'A'+String.fromCharCode('A'.charCodeAt(0) + a_index);
                    $("#plan_table").append("<tr><td  align='center'>"+stage_index+"</td><td  align='center'>"+value+"</td><td  align='center'>Start date</td></tr>");
                    a_index++;
                    var stage_index = 'A'+String.fromCharCode('A'.charCodeAt(0) + a_index);
                    $("#plan_table").append("<tr><td  align='center'>"+stage_index+"</td><td  align='center'>"+value+"</td><td  align='center'>End date</td></tr>");
                    a_index++;
                }
            });
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
}

async function change_exporttemplate(){
    var export_id = $('#export_template').val();
    var program_id = $('#program_id').val();
    $("#set_table").empty();
    $.ajax({
        data: {export_id:export_id},
        url: "index.php?r=task/import/queryexportdetail",
        type: "POST",
        dataType: "json",
        async: false,
        beforeSend: function () {
        },
        success: function (data) {
            var index = 0;
            $("#set_table").append("<input type='hidden'  name='Program[template_id]' value='"+export_id+"'><input type='hidden'  name='Program[program_id]' value='"+program_id+"'>");
            $("#set_table").append("<thead><tr><td  align='center'>Column</td><td  align='center'>Property</td><td  align='center'>Export Value</td></tr></thead>");
            if(data.length == 0){
                document.getElementById("del_export").style.display="none";
                $("#set_table").append("<tr><td  align='center'>A</td><td  align='center'>Model Id</td><td  align='center'><input type='hidden' id='val_A' name='Export[A][]' value=''>-</td></tr>");
                $("#set_table").append("<tr><td  align='center'>B</td><td  align='center'>GUID</td><td  align='center'><input type='hidden' id='val_B' name='Export[B][]' value=''>-</td></tr>");
                $("#set_table").append("<tr><td  align='center'>C</td><td  align='center'>Block</td><td  align='center'><input type='hidden' id='val_C' name='Export[C][]' value=''><a id='set_C' onclick='setParam(\"C\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>D</td><td  align='center'>Level</td><td  align='center'><input type='hidden' id='val_D' name='Export[D][]' value=''><a id='set_D' onclick='setParam(\"D\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>E</td><td  align='center'>Unit No.</td><td  align='center'><input type='hidden' id='val_E' name='Export[E][]' value=''><a id='set_E' onclick='setParam(\"E\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>F</td><td  align='center'>Zone</td><td  align='center'><input type='hidden' id='val_F' name='Export[F][]' value=''><a id='set_F' onclick='setParam(\"F\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>G</td><td  align='center'>Unit Type</td><td  align='center'><input type='hidden' id='val_G' name='Export[G][]' value=''><a id='set_G' onclick='setParam(\"G\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>H</td><td  align='center'>Serial No</td><td  align='center'><input type='hidden' id='val_H' name='Export[H][]' value=''><a id='set_H' onclick='setParam(\"H\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>I</td><td  align='center'>Element Type</td><td  align='center'><input type='hidden' id='val_I' name='Export[I][]' value=''><a id='set_I' onclick='setParam(\"I\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>J</td><td  align='center'>Element Name</td><td  align='center'><input type='hidden' id='val_J' name='Export[J][]' value=''><a id='set_J' onclick='setParam(\"J\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>K</td><td  align='center'>Orientation</td><td  align='center'><input type='hidden' id='val_K' name='Export[K][]' value=''><a id='set_K' onclick='setParam(\"K\")'>-</a></td></tr>");
                $("#set_table").append("<tr><td  align='center'>L</td><td  align='center'>Other</td><td  align='center'><input type='hidden' id='val_L' name='Export[L][]' value=''><a id='set_L' onclick='setParam(\"L\")'>-</a></td></tr>");
            }else{
                document.getElementById("del_export").style.display="block";
                $.each(data, function (name, value) {
                    if(value.col == 'A'){
                        var name = 'Model Id';
                    }else if(value.col == 'B'){
                        var name = 'GUID';
                    }else if(value.col == 'C'){
                        var name = 'Block';
                    }else if(value.col == 'D'){
                        var name = 'Level';
                    }else if(value.col == 'E'){
                        var name = 'Unit No.';
                    }else if(value.col == 'F'){
                        var name = 'Zone';
                    }else if(value.col == 'G'){
                        var name = 'Unit Type';
                    }else if(value.col == 'H'){
                        var name = 'Serial No';
                    }else if(value.col == 'I'){
                        var name = 'Element Type';
                    }else if(value.col == 'J'){
                        var name = 'Element Name';
                    }else if(value.col == 'K'){
                        var name = 'Orientation';
                    }else if(value.col == 'L'){
                        var name = 'Other';
                    }
                    if(value.value == ''){
                        var val = '-';
                    }else{
                        var val = value.value;
                    }
                    if(index == 0){
                        $("#set_table").append("<tr><td  align='center'>"+value.col+"</td><td  align='center'>"+name+"</td><td  align='center'><input type='hidden' id='val_"+value.col+"' name='Export["+value.col+"][]' value='"+value.value+"'>"+val+"</td></tr>");
                    }else if(index == 1){
                        $("#set_table").append("<tr><td  align='center'>"+value.col+"</td><td  align='center'>"+name+"</td><td  align='center'><input type='hidden' id='val_"+value.col+"' name='Export["+value.col+"][]' value='"+value.value+"'>"+val+"</td></tr>");
                    }else{
                        $("#set_table").append("<tr><td  align='center'>"+value.col+"</td><td  align='center'>"+name+"</td><td  align='center'><input type='hidden' id='val_"+value.col+"' name='Export["+value.col+"][]' value='"+value.value+"'><a id='set_"+value.col+"' onclick='setParam(\""+value.col+"\")'>"+val+"</a></td></tr>");
                    }
                    index++;
                });
            }
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
}

async function refresh_task() {
    var program_id = $('#program_id').val();
    $("#export_task").empty();
    $("#export_task").append("<thead><tr><th style='text-align:center' >Time</th><th style='text-align:center'>Status</th><th style='text-align:center'>Action</th></tr></thead>");
    $("#export_task").append("<tbody>");
    $.ajax({
        data: {program_id:program_id},
        url: "index.php?r=task/model/exporttask",
        type: "POST",
        dataType: "json",
        async: false,
        beforeSend: function () {
        },
        success: function (data) {
            $.each(data, function (name, value) {
                if(value.status == '0'){
                    status = 'Processing';
                    $("#export_task").append("<tr><td style='white-space: nowrap;' align='center'>"+value.record_time+"</td><td  align='center'>"+status+"</td><td style='white-space: nowrap;' align='center'></td></tr>");
                }else{
                    status = 'Done';
                    $("#export_task").append("<tr><td style='white-space: nowrap;' align='center'>"+value.record_time+"</td><td  align='center'>"+status+"</td><td style='white-space: nowrap;' align='center'><button type='button' class='btn btn-default' onclick='exportdownload(\""+value.id+"\")'>Download</button></td></tr>");
                }
            });
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
    $("#export_task").append("</tbody>");
}

async function setParam(type) {
    var program_id = $('#program_id').val();

    var entity = await view.getWINDViewRoaming().getSelectParameters();
    if(entity.length != 1){
        alert('Please select one components.');
        return false;
    }
    entity_info = entity[0];
    console.log(entity_info);
    var model_id = entity_info.modelId;
    var version = entity_info.version;
    var uuid = entity_info.uniqueid;

    // window.location = "index.php?r=task/import/pbuinfo&type=" + type+"&model_id="+model_id+"&version="+version+"&uuid="+uuid+"&program_id="+program_id;
    var modal = new TBModal();
    modal.title = "Base Info";
    modal.url = "index.php?r=task/import/pbuinfo&type=" + type+"&model_id="+model_id+"&version="+version+"&uuid="+uuid+"&program_id="+program_id;
    modal.modal();
}

async function save_export() {

    $.ajax({
        data:$('#export_form').serialize(),
        url: "index.php?r=task/import/saveexport",
        type: "POST",
        dataType: "json",
        beforeSend: function () {

        },
        success: function (data) {
            alert('success');
            tabdemo();
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
}

async function del_export() {
    var template_id = $('#export_template').val();
    $.ajax({
        data:{template_id:template_id},
        url: "index.php?r=task/import/delexport",
        type: "POST",
        dataType: "json",
        beforeSend: function () {

        },
        success: function (data) {
            alert('success');
            tabdemo();
        },
        error: function () {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('系统错误');
            $('#msgbox').show();
        }
    });
}
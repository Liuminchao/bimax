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
            $.each(data, function (name, value) {
                let temp = {};
                temp._id = value['model_id'];
                temp._version = value['version'];
                temp._name = value['model_name'];
                modeldata.set(value['model_name'], temp);
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
            console.log(modeldata);
            let modellistUI = document.getElementById("modellist");
            let multipleUI = document.getElementById("fselect");
            modeldata.forEach((model, name) => {
                id_version = model._id+'_'+model._version;
                modellistUI.add(new Option(name,id_version));
                multipleUI.add(new Option(name,id_version));
                // modellistUI.add(new Option(name));
            });
            $("#modellist option").each(function(i){
                if(this.value == model_name){
                    this.selected = true;
                }
            });
            modellistUI.options[0].selected = true;//默认选中第一个
            $("#fselect option").each(function(i){
                if(this.value == model_name){
                    this.selected = true;
                }
            });
            multipleUI.options[0].selected = true;//默认选中第一个
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
let canvas = document.getElementById("View");
let view = new WINDView(canvas);
let atree = {};
let btree = {};
view.bindWINDData(data);//将View与一个Data绑定

//页面加载时初始化ui事件
window.addEventListener('load', onLoad, true);
async function onLoad() {
    //初始化UI
    await getModelList();
    initDataUI();
    // initViewUI();
    initViewRoamingUI();

    //添加视图回调
    view.addWINDViewCallback('callback', callback);
    // highlightAssignedEntities();
}
function getvalue() {
    var value = $('#fselect').val();
    alert(value);
}
function initDataUI() {
    let openModelDataUI = document.getElementById("openModelData");
    openModelDataUI.addEventListener("click", openModelData, false);

    let closeModelDatasUI = document.getElementById("closeModelDatas");
    closeModelDatasUI.addEventListener("click", closeModel, false);

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
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    console.log(model);
    addcloud();
    if (model) {
        var open_tag = $('#open_tag').val();
        if(open_tag == '1'){
            closeModelDatas();
            await data.getWINDDataLoader().openModelData(arr[0],arr[1],true);//打开对应模型id的模型数据
        }else if(open_tag == '2'){
            await data.getWINDDataLoader().openModelData(arr[0],arr[1],true);//打开对应模型id的模型数据
        }else if(open_tag == '3'){

            await data.getWINDDataLoader().openModelData(arr[0],arr[1],true);//打开对应模型id的模型数据
        }
        // let info = await data.getWINDDataQuerier().getAllComponentParameterL();
        // var map = {},
        // big_arr = [];
        // for(var i=0,l=info.length;i<l;i++){
        //     for(var key in info[i]){
        //         if( key == 'parameter'){
        //             var arr = info[i][key];
        //             for(var i = 0; i < arr.length; i++){
        //                 var ai = arr[i];
        //                 if(!map[ai.domain]){
        //                     big_arr.push({
        //                         domain: ai.domain,
        //                         data: [ai]
        //                     });
        //                     map[ai.domain] = ai;
        //                 }else{
        //                     for(var j = 0; j < big_arr.length; j++){
        //                         var dj = big_arr[j];
        //                         if(dj.domain == ai.domain){
        //                             dj.data.push(ai);
        //                             break;
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        // var new_data = [];
        //
        // for(var k = 0; k < big_arr.length; k++){
        //
        //     var map = {};
        //     var arr2 = big_arr[k]['data'];
        //
        //     var small_arr = [];
        //     for(var i = 0; i < arr2.length; i++){
        //         var ai = arr2[i];
        //         if(!map[ai.category]){
        //             small_arr.push({
        //                 category: ai.category,
        //                 data: [ai]
        //             });
        //             map[ai.category] = ai;
        //         }else{
        //             for(var j = 0; j < small_arr.length; j++){
        //                 var dj = small_arr[j];
        //                 if(dj.category == ai.category){
        //                     dj.data.push(ai);
        //                     break;
        //                 }
        //             }
        //         }
        //
        //     }
        //     // console.log(small_arr);
        //     new_data.push({
        //         domain: big_arr[k]['domain'],
        //         data: small_arr
        //     });
        //     // new_data[big_arr[k]['domain']] = small_arr;
        // }
        // console.log("----------------------");
        // console.log(new_data);
        removecloud();
        // await level();
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

//Level初始化
function LevelInit(level) {
    return level.html('<option value="">--Level--</option>');
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

async function tree() {
    view.getWINDViewControl().createComponentTree([TreeRuleType.PROJECT_NAME, TreeRuleType.STOREY_NAME], atree);
    // view.getWINDViewControl().createComponentTree([TreeRuleType.DOMAIN_NAME, TreeRuleType.CATEGORY_NAME, TreeRuleType.TYPE_NAME], btree);
    console.log(atree);
    console.log(atree.children[0].parent);
    console.log(atree.children[0].children);
    var root_id = atree.children[0].id;
    $("#info").empty();
    tab="<div class='box box-primary' >";
    tab+="<div class='box-body chart-responsive' style='padding-top: 0px;'>";
    tab+="<table id='level_tab' align='center' frame='hsides' width='100%' class='table-bordered'>";
    tab+="<thead><tr><th align='center'><input type='checkbox' id='checkAll' name='checkAll' /></th><th align='center'>"+atree.children[0].name+"</th></tr></thead>";
    tab+="<tbody>";
    $.each(atree.children[0].children, function (name, value) {
        console.log(value);
        tab+="<tr><td align='center'><input type='checkbox' name='checkItem' /></td><td style='white-space: nowrap;' name='id' align='center'>"+value.id+"</td><td style='white-space: nowrap;' align='center'>"+value.name+"</td></tr>";
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

    initLevelCheckbox(root_id,atree);
    // view.getWINDViewControl().displayTreeNode(atree.children[0].children[1].id, atree);
    // view.getWINDViewControl().hightlightTreeNode(atree.children[0].children[3].id, atree);

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
    var $thr = $('#level_tab thead tr');
    console.log(22222);
    console.log(atree);
    /*“全选/反选”复选框*/
    var $checkAll = $thr.find('input');
    $checkAll.click(function (event) {
        // alert('全选');
        /*将所有行的选中状态设成全选框的选中状态*/
        $tbr.find('input').prop('checked', $(this).prop('checked'));
        /*并调整所有选中行的CSS样式*/
        if ($(this).prop('checked')) {
            status = view.getWINDViewControl().displayTreeNode(root_id, atree);
        } else {
            status = view.getWINDViewControl().displayTreeNode(root_id, atree);
        }
        /*阻止向上冒泡，以防再次触发点击操作*/
        event.stopPropagation();
    });
    /*点击全选框所在单元格时也触发全选框的点击操作*/
    $thr.click(function () {
        $(this).find('input').click();
    });
    var $tbr = $('#level_tab tbody tr');
    /*点击每一行的选中复选框时*/
    $tbr.find('input').click(function (event) {
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

    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    let msg = JSON.stringify(await data.getWINDDataQuerier().getAllComponentParameterS());
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    console.log(model);
    // let info = await data.getWINDDataQuerier().getAllComponentParameterS(model._id,false,model._version);
    let info = await data.getWINDDataQuerier().getAllComponentParameterL();
    console.log(info);
    // var tab='<table class="table table-bordered">';
    var tr = [];
    console.log(entity);
    var obj = document.getElementById("type"); //定位id
    var index = obj.selectedIndex; // 选中索引
    var type = obj.options[index].value; // 选中值
    var big_obj = document.getElementById("big_type"); //构件&&构件组
    var big_index = big_obj.selectedIndex; // 选中索引
    var big_type = obj.options[big_index].value; // 选中值
    if(type == '0x00'){
        var uuid = $('#detail').val();
        entity_info = await data.getWINDDataQuerier().getEntityParameterL(uuid);
        console.log(entity_info);
        var detail = entity_info.entityId;
        type = '0x02';
    }else{
        var detail = $('#detail').val();
    }

    var model_id = model._id;
    var model_id = arr[0];
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
                    // tab+='<tr>';
                    // tab+="<td >Id</td><td >"+value.entityId+"</td>";
                    // tab+='</tr>';
                    // tab+='<tr>';
                    // tab+="<td >Model</td><td >"+value.model+"</td>";
                    // tab+='</tr>';
                    // tab+='<tr>';
                    // tab+="<td >Name</td><td >"+value.type+"</td>";
                    // tab+='</tr>';
                    // tab+='<tr>';
                    // tab+="<td >Floor</td><td >"+value.floor+"</td>";
                    // tab+='</tr>';
                    // tab+='<tr>';
                    // tab+="<td >Domain</td><td >"+WIND.getDomainName(value.domain)+"</td>";
                    // tab+='</tr>';
                    // tab+='<tr>';
                    // tab+="<td >Category</td><td >"+WIND.getCategoryName(value.category)+"</td>";
                    // tab+='</tr>';
                    // tab+="<tr><td colspan='2'><button type='button' class='btn btn-default' onclick='qrcode(\""+value.modelId+"\",\""+value.version+"\",\""+value.uuid+"\",\""+value.entityId+"\",\""+value.type+"\")'>二维码预览</button></td></tr>";
                    // tab+="<tr><td colspan='2'><button type='button' class='btn btn-default' onclick='qrallcode(\""+value.model_id+"\",\""+value.version+"\")'>全部二维码预览</button></td></tr>";
                    view.getWINDViewControl().highlightAssignedEntities(value.uuid);
                })
                removecloud();
                // tab+='</table>';
                // document.getElementById("info").innerHTML=tab;
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
    }else if(entity.length > 0){
        for ( var i = 0; i <entity.length; i++){
            entity_info = await data.getWINDDataQuerier().getEntityParameterS(entity[i]);
            console.log(entity_info);
            for(var k in entity_info)
            {
                if( k != 'properties'){
                    // alert(key+':'+info[i][key]);
                    if(k == 'modelId'){
                        var model_id = entity_info[k];
                    }
                    if(k == 'version'){
                        var version = entity_info[k];
                    }
                    if(k == 'uuid'){
                        var uuid = entity_info[k];
                    }
                    if(k == 'entityId'){
                        var entityId = entity_info[k];
                    }
                    if(k == 'type'){
                        var type = entity_info[k];
                    }
                }
            }
            tab+='<tr>';
            tab+="<td >Id</td><td >"+entity_info['entityId']+"</td>";
            tab+='</tr>';
            tab+='<tr>';
            tab+="<td >Model</td><td >"+entity_info['model']+"</td>";
            tab+='</tr>';
            tab+='<tr>';
            tab+="<td >Name</td><td >"+entity_info['type']+"</td>";
            tab+='</tr>';
            tab+='<tr>';
            tab+="<td >Floor</td><td >"+entity_info['floor']+"</td>";
            tab+='</tr>';
            tab+='<tr>';
            tab+="<td >Domain</td><td >"+entity_info['domain']+"</td>";
            tab+='</tr>';
            tab+='<tr>';
            tab+="<td >Category</td><td >"+entity_info['category']+"</td>";
            tab+='</tr>';
            tab+="<tr><td colspan='2'><button type='button' class='btn btn-default' onclick='qrcode(\""+model_id+"\",\""+version+"\",\""+uuid+"\",\""+entityId+"\",\""+type+"\")'>二维码预览</button></td></tr>";
            // tr[entity_info['domain']][entity_info['category']] = entity_info;
        }
        // tab+="<tr><td colspan='2'><button type='button' class='btn btn-default' onclick='qrallcode(\""+model_id+"\",\""+version+"\")'>全部二维码预览</button></td></tr>";
        tab+='</table>';
        document.getElementById("info").innerHTML=tab;
    }else{
        document.getElementById("info").innerHTML='';
        view.getWINDViewControl().unhighlightAllEntities();
        var map = {},
        big_arr = [];
        for(var i=0,l=info.length;i<l;i++){
            for(var key in info[i]){
                if( key == 'parameter'){
                    var arr = info[i][key];
                    for(var i = 0; i < arr.length; i++){
                        var ai = arr[i];
                        ai.type = 3;
                        if(!map[ai.domain]){
                            big_arr.push({
                                id: ai.domain,
                                name: WIND.getDomainName(ai.domain),
                                open: true,
                                type: 1,
                                children: [ai]
                            });
                            map[ai.domain] = ai;
                        }else{
                            for(var j = 0; j < big_arr.length; j++){
                                var dj = big_arr[j];
                                if(dj.id == ai.domain){
                                    dj.children.push(ai);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        var new_data = [];

        for(var k = 0; k < big_arr.length; k++){

            var map = {};
            var arr2 = big_arr[k]['children'];

            var small_arr = [];
            for(var i = 0; i < arr2.length; i++){
                var ai = arr2[i];
                if(!map[ai.category]){
                    small_arr.push({
                        id: ai.category,
                        name: WIND.getCategoryName(ai.category),
                        open: true,
                        type: 2,
                        children: [ai],
                    });
                    map[ai.category] = ai;
                }else{
                    for(var j = 0; j < small_arr.length; j++){
                        var dj = small_arr[j];
                        if(dj.id == ai.category){
                            dj.children.push(ai);
                            break;
                        }
                    }
                }

            }
            console.log(small_arr);
            new_data.push({
                name: big_arr[k]['name'],
                type: 1,
                children: small_arr
            });
            // new_data[big_arr[k]['domain']] = small_arr;
        }


        var setting = {
            view: {
                showLine: false,
                showIcon: false,
                dblClickExpand: false,
                addDiyDom: addDiyDom
            },
            callback: {
                beforeClick: zTreeBeforeClick
            }
        };
        console.log(new_data);

        var treeObj = $("#treeDemo");
        $.fn.zTree.init(treeObj, setting, new_data);
        zTree_Menu = $.fn.zTree.getZTreeObj("treeDemo");
        // curMenu = zTree_Menu.getNodes();
        // console.log(curMenu);
        // zTree_Menu.selectNode(curMenu);

        treeObj.hover(function () {
            if (!treeObj.hasClass("showIcon")) {
                treeObj.addClass("showIcon");
            }
        }, function() {
            treeObj.removeClass("showIcon");
        });

    }

    // itemQuery(model_id,version);
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
    $('#info').empty();
    var program_id = $('#program_id').val();
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    var length = entity.length;
    // if(length > 10){
    //     alert('The number of choices must not be more than ten');
    //     return false;
    // }
    console.log(entity);
    var tab ='<div class="page-header"><h2>QR-CODE</h2></div>';
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
            url: "index.php?r=task/model/qrbyprint",
            type: "POST",
            data: {program_id: program_id,uuid:uuid,model_id:model_id,entityId: entity_id,version:version,level:floor},
            dataType: "json",
            beforeSend: function () {

            },
            success: function(data){
                //#DAE3F3
                tab="<div class='box box-primary' >";
                tab+="<div class='box-header' style='padding-bottom:0px;'><h3 class='box-title'>"+model+"</h3></div>";
                tab+="<div class='box-body chart-responsive' style='padding-top: 0px;'>";
                tab+="<table align='center' frame='hsides' width='100%' border='1' cellpadding='4'>";
                tab+="<tr><td align='left' height='20px' width='25%'>Block:</td><td align='center' width='42%'>"+data.block+"</td> <td rowspan='6' align='center' width='33%'><img src='"+data.filename+"' width='120' height='120'></td></tr>";
                tab+="<tr><td align='left' height='20px' width='25%'>Part:</td><td align='center' width='42%'>"+data.part+"</td></td></tr>";
                if(data.level){
                    tab+="<tr><td align='left' height='20px' width='25%'>Level/Unit:</td><td  align='center' width='42%'>"+data.level+"</td></td></tr>";
                }else{
                    tab+="<tr><td align='left' height='20px' width='25%'>Level/Unit:</td><td  align='center' width='42%'>"+floor+"</td></td></tr>";
                }
                if(data.unit_type){
                    tab+="<tr><td align='left' height='20px' width='25%'>Unit Type:</td><td  align='center' width='42%'>"+data.unit_type+"</td></td></tr>";
                }else{
                    tab+="<tr><td align='left' height='20px' width='25%'>Unit Type:</td><td  align='center' width='42%'>"+floor+"</td></td></tr>";
                }
                if(data.element_name){
                    tab+="<tr><td align='left' height='20px' width='25%'>Element Name:</td><td  align='center' width='42%'>"+data.element_name+"</td></td></tr>";
                }else{
                    tab+="<tr><td align='left' height='20px' width='25%'>Element Name:</td><td  align='center' width='42%'>"+name+"</td></td></tr>";
                }
                if(data.element_type){
                    tab+="<tr><td align='left' height='20px' width='25%'>Element Type:</td><td  align='center' width='42%'>"+data.element_type+"</td></td></tr>";
                }else{
                    tab+="<tr><td align='left' height='20px' width='25%'>Element Type:</td><td  align='center' width='42%'>"+type+"</td></td></tr>";
                }
                tab+="</table>";
                tab+="<img src='img/RF.jpg' width='80' height='40' align='right'>";
                tab+="</div>";
                tab+="</div>";
                console.log(tab);
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

async function selectqrchecklist() {
    $('#info').empty();
    var program_id = $('#program_id').val();
    var template_id = $('#template_id').val();
    if(!template_id){
        alert('Please select template.');
        return false;
    }
    var entity = await view.getWINDViewRoaming().getSelectEntities();
    let modellistUI = document.getElementById("modellist");
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var length = entity.length;
    if(length > 10){
        alert('The number of choices must not be more than ten');
        return false;
    }
    console.log(entity);
    entity_id = '';
    var tab ='<div class="page-header"><h2>QA/QC</h2></div>';
    for ( var i = 0; i <entity.length; i++) {
        entity_id += entity[i] + ',';
    }
    entity_id=entity_id.substring(0,entity_id.length-1);

    $.ajax({
        url: "index.php?r=task/model/qaqc",
        type: "POST",
        data: {uuid: entity_id,model_id: arr[0],template_id:template_id,program_id:program_id},
        dataType: "json",
        beforeSend: function () {

        },
        success: function(data){
            $.each(data, function (name, value) {
                tab+="<div class='box box-primary' style='background-color: #DAE3F3'>";
                tab+="<div class='box-body chart-responsive' style='padding-top: 0px;'>";
                tab+="<table align='center' frame='hsides' width='100%'>";
                tab+="<tr><td ><h3>"+value.title+"</h3></td><td style='white-space: nowrap;'><span class='label "+value.status_css+" '>"+value.status_txt+"</span></td></tr>";
                tab+="<tr><td >Block: "+value.block+"</td><td style='white-space: nowrap;'></td></tr>";
                tab+="<tr><td >Location: "+value.location+"</td><td style='white-space: nowrap;'></td></tr>";
                tab+="<tr><td >Date: "+value.apply_time+"</td><td style='white-space: nowrap;'></td></tr>";
                tab+="<tr><td ><h4>Checklst</h4></td><td style='white-space: nowrap;'></td></tr>";
                tab+="<tr><td >"+value.form_title+"</td><td style='white-space: nowrap;'><button class='btn btn-default' type='button' onclick='downloadexcel(\""+value.check_id+"\",\""+value.data_id+"\")'>Preview</button></td></tr>";
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
    window.location = "index.php?r=qa/qainspection/qaexport&check_id="+check_id+"&data_id="+data_id;
}

async  function pbuexcel (){
    let modellistUI = document.getElementById("modellist");
    // let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    var model_id = arr[0];
    var version = arr[1];
    var program_id = $('#program_id').val();
    window.location = "index.php?r=task/statistic/pbuexport&model_id="+model_id+"&program_id="+program_id;
}

async function selectstatuslist() {
    closeModelDatas();
    $("#info").empty();
    let alpha = $('#alpha').val();
    if(alpha == ''){
        alpha = Number(111);
    }else{
        alpha = Number(alpha);
    }
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    console.log(model);
    var template_id = $('#template_id').val();
    if(!template_id){
        alert('Please select template.');
        return false;
    }
    addcloud();
    var program_id = $('#program_id').val();
    if (model) {
        await data.getWINDDataLoader().openModelData(arr[0],arr[1],true);//打开对应模型id的模型数据
    }
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
    var tab ='<div class="page-header"><h2>Status</h2></div>';
    $("#info").append(tab);
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
            tab="<div class='box box-primary' >";
            tab+="<div class='box-body chart-responsive' style='padding-top: 0px;'>";
            tab+="<table id='status_tab' align='center' frame='hsides' width='100%' class='table-bordered'>";
            tab+="<thead><tr><th><input type='checkbox' id='checkAll' name='checkAll' /></th><th align='center'>Stages</th><th align='center'>Color</th><th align='center'>Quantity</th></tr></thead>";
            tab+="<tbody>";
            $.each(data, function (name, value) {
                var guid_list = value.guid_list;
                var rand = getRandomString(8);
                if(guid_list.length != 0){
                    batch_id = rand;
                    status = view.getWINDViewControl().createBatchEntities(batch_id,guid_list);
                    var rgb = hexToRgb(value.stage_color);
                    if(status == 'true'){
                        view.getWINDViewControl().coloringBatchEntities(batch_id, rgb.r, rgb.g, rgb.b, alpha);
                    }
                }
                tab+="<tr><td align='center'><input type='checkbox' name='checkItem' /></td><td style='white-space: nowrap;' name='stage_id' align='center'>"+value.stage_id+"</td><td style='white-space: nowrap;' name='batch_id' align='center'>"+rand+"</td><td style='white-space: nowrap;' align='center'>"+value.stage_name+"</td><td style='background-color:"+value.stage_color+"' align='center'></td><td style='white-space: nowrap;' align='center'>"+value.guid_cnt+"</td></tr>";
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

            initTableCheckbox();
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
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    var model_id = model._id;
    var version = model._version;
    var program_id = $('#program_id').val();

    var entity = await view.getWINDViewRoaming().getSelectEntities();
    console.log(entity);
    var length = entity.length;

    if(length == 0){
        alert('Please select the components.');
        return false;
    }
    if(length > 1000){
        alert('The number of choices must not be more than fifty');
        return false;
    }
    entity_str=entity.join(',');
    console.log(entity_str);
    addcloud();
    jQuery.ajax({
        data: {entity_str:entity_str, version:arr[1], model_id:arr[0],program_id:program_id},
        type: 'post',
        url: './index.php?r=rf/rf/getmodeldata',
        dataType: 'json',
        success: function (data, textStatus) {
//                $('#qr_table').append("</br>Loading...");
            ajaxReadData(program_id,arr[1],arr[0], data.rowcnt, 0);
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
var ajaxReadData = function (program_id,version,model_id, rowcnt, startrow){//alert('aa');

    jQuery.ajax({
        data: {program_id:program_id, version:version, model_id:model_id, startrow: startrow, per_read_cnt:per_read_cnt},
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
                ajaxReadData(program_id,version,model_id, rowcnt, startrow+per_read_cnt);
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
var clearCache = function(program_id,version,model_id){//alert('aa');
    removecloud();
    window.location = "index.php?r=rf/rf/clearcache&model_id="+model_id;
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
    view.getWINDViewControl().showAllComponents();
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
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    var model_id = model._id;
    var version = model._version;

    var entity = await view.getWINDViewRoaming().getSelectEntities();
    console.log(entity);
    var length = entity.length;
    if(length == 0){
        alert('Please select the components.');
        return false;
    }
    entity_str=entity.join(',');

    jQuery.ajax({
        data: {entity_str:entity_str},
        type: 'post',
        url: './index.php?r=task/model/saveentity',
        dataType: 'json',
        success: function (data, textStatus) {
            var modal = new TBModal();
            modal.title = "<?php echo Yii::t('common', 'edit'); ?>";
            modal.url = "index.php?r=task/model/setentity&model_id="+arr[0]+"&version="+arr[1]+"&program_id="+program_id;
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
    let modellistUI = document.getElementById("modellist");
    let model = modeldata.get(modellistUI.options[modellistUI.selectedIndex].text);
    var id_version = modellistUI.options[modellistUI.selectedIndex].value;
    var arr = id_version.split('_');
    var model_id = model._id;
    var version = model._version;
    var program_id = $('#program_id').val();

    var entity = await view.getWINDViewRoaming().getSelectEntities();
    console.log(entity);
    var length = entity.length;

    if(length == 0){
        alert('Please select the components.');
        return false;
    }
    if(length > 1000){
        alert('The number of choices must not be more than fifty');
        return false;
    }
    entity_str=entity.join(',');
    console.log(entity_str);
    addcloud();
    jQuery.ajax({
        data: {entity_str:entity_str, version:arr[1], model_id:arr[0],program_id:program_id},
        type: 'post',
        url: './index.php?r=task/model/getpbuinfo',
        dataType: 'json',
        success: function (data, textStatus) {
//                $('#qr_table').append("</br>Loading...");
            savepbuinfo(program_id,version,model_id, data.rowcnt, 0);
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
var savepbuinfo = function (program_id,version,model_id, rowcnt, startrow){//alert('aa');

    jQuery.ajax({
        data: {program_id:program_id, version:version, model_id:model_id, startrow: startrow, per_read_cnt:per_pbu_cnt},
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
                savepbuinfo(program_id,version,model_id, rowcnt, startrow);
            }else{
                exportpbu(program_id,version,model_id);
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
var exportpbu = function(program_id,version,model_id){//alert('aa');
    removecloud();
    window.location = "index.php?r=task/model/exportpbu&model_id="+model_id+"&version="+version+"&program_id="+program_id;
}
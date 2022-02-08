/**
 * Created by minchao on 2017-01-23.
 */
//添加遮罩部分
function addcloud(length) {
    var bodyWidth = document.documentElement.clientWidth;
    var bodyHeight = Math.max(document.documentElement.clientHeight, document.body.scrollHeight);
    var bgObj = document.createElement("div" );
    bgObj.setAttribute( 'id', 'bgDiv' );
    bgObj.style.position = "absolute";
    bgObj.style.top = "0";
    bgObj.style.background = "#000000";
    bgObj.style.filter = "progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75" ;
    bgObj.style.opacity = "0.5";
    bgObj.style.left = "0";
    bgObj.style.width = "130px";
    bgObj.style.height = "100px";
    bgObj.style.zIndex = "10000"; //设置它的zindex属性，让这个div在z轴最大，用户点击页面任何东西都不会有反应|
    document.getElementById(length).appendChild(bgObj); //添加遮罩
    var loadingObj = document.createElement("div");
    loadingObj.setAttribute( 'id', 'loadingDiv' );
    loadingObj.style.position = "absolute";
    loadingObj.style.top = 100 / 2 - 32 + "px";
    loadingObj.style.left = 130 / 2 -32+ "px";
    loadingObj.style.background = "url(../ctmgr/img/loading.gif)" ;
    loadingObj.style.width = "60px";
    loadingObj.style.height = "60px";
    loadingObj.style.zIndex = "10000";
    //alert(length);
    document.getElementById(length).appendChild(loadingObj); //添加loading动画-
}
//移除加载动画
function removecloud() {
    $( "#loadingDiv").remove();
    $( "#bgDiv").remove();
}
//页面加载完毕，去除遮罩
function subSomething() {
    if (document.readyState == "complete" ) //当页面加载完毕移除页面遮罩，移除loading动画-
    {
        removecloud();
    }
}

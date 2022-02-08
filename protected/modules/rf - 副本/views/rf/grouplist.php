
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <title>可拖拽移动漂亮的zDialog弹出层代码</title>
    <style>
        body { background: #ffffff; color: #444; font-size:12px;}
        a { color: #07c; text-decoration: none; border: 0; background-color: transparent; }
        body, div, q, iframe, form, h5 { margin: 0; padding: 0; }
        img, fieldset { border: none 0; }
        body, td, textarea { word-break: break-all; word-wrap: break-word; line-height:1.6; }
        body, input, textarea, select, button { margin: 0; font-size: 14px; font-family: Tahoma, SimSun, sans-serif; }
        div, p, table, th, td { font-size:1em; font-family:inherit; line-height:inherit; }
        h5 { font-size:12px; }
        ol li,ul li{ margin-bottom:0.5em;}
        pre, code { font-family: "Courier New", Courier, monospace; word-wrap:break-word; line-height:1.4; font-size:12px;}
        pre{background:#f6f6f6; border:#eee solid 1px; margin:1em 0.5em; padding:0.5em 1em;}
        #content { padding-left:50px; padding-right:50px; }
        #content h2 { font-size:20px; color:#069; padding-top:8px; margin-bottom:8px; }
        #content h3 { margin:8px 0; font-size:14px; COLOR:#693; }
        #content h4 { margin:8px 0; font-size:16px; COLOR:#690; }
        #content div.item { margin-top:10px; margin-bottom:10px; border:#eee solid 4px; padding:10px; }
        hr { clear:both; margin:7px 0; +margin: 0;
            border:0 none; font-size: 1px; line-height:1px; color: #069; background-color:#069; height: 1px; }
        .infobar { background:#fff9e3; border:1px solid #fadc80; color:#743e04; }
        .buttonStyle{width:64px;height:22px;line-height:22px;color:#369;text-align:center;background:url(images/buticon.gif) no-repeat left top;border:0;font-size:12px;}
        .buttonStyle:hover{background:url(images/buticon.gif) no-repeat left -23px;}

    </style>
    <script type="text/javascript" src="js/zDrag.js"></script>
    <script type="text/javascript" src="js/zDialog.js"></script>
    <script type="text/javascript">
        function open8()
        {
            var diag = new Dialog();
            diag.Title = "返回值到调用页面";
            diag.URL = "test.html";
            diag.OKEvent = function(){
                $id('getval').value = diag.innerFrame.contentWindow.document.getElementById('a').value;
                $id('getval1').value = '2222';
                diag.close();
            };
            diag.show();
            var doc=diag.innerFrame.contentWindow.document;
            doc.open();
            doc.write("https://www.baidu.com") ;
            doc.close();
        }



        function open19()
        {
            var diag = new Dialog();
            diag.Title = "窗体内的按钮操作父Dialog";
            diag.URL = "test.html";
            diag.CancelEvent=function(){alert("我要关闭了");diag.close();};
            diag.show();
            var doc=diag.innerFrame.contentWindow.document;
            doc.open();
            doc.write('<html><body><input type="button" id="a" value="修改父Dialog尺寸" onclick="parentDialog.setSize(function(min,max){return Math.round(min+(Math.random()*(max-min)))}(300,800))" /> <input type="button" id="b" value="关闭父窗口" onclick="parentDialog.close()" /> <input type="button" id="b" value="点击窗口取消按钮" onclick="parentDialog.cancelButton.onclick()" /></body></html>') ;
            doc.close();
        }

    </script>
</head>
<body>
<div id="content">
    <h2>zDialog v2.0 - samples</h2>
    <hr size="2" />
    <br />
    <div style="border:1px dashed #ccc;padding:20px;">
        <h4>弹出框：</h4>
        <ol>
            <li>代替window.open、window.alert、window.confirm；提供良好的用户体验；</li>
            <li>水晶质感，设计细腻，外观漂亮；</li>
            <li>兼容ie6/7/8、firefox2/3、Opera；弹出框在ie6下不会被select控件穿透；</li>
            <li>无外部css文件，引用Dialog.js即可使用；</li>
            <li>对iframe下的应用作了充分考虑，适合复杂的系统应用；</li>
            <li>Dialog显示的内容（三种）：1、指向一个URL的iframe窗口；2、页面内隐藏的元素中的html内容；3、直接输出一段html内容；</li>
            <li>按ESC键可关闭弹出框；</li>
        </ol>
    </div>
    <br />

    <h3>8. 返回值到调用页面</h3>
    <div class="item">
        <input type="button" id="h" value="返回值到调用页面" onclick="open8()" />
        <input type="text" id="getval" value="窗口的值返回到这里" />
        <input type="text" id="getval1" value="窗口的值返回到这里" />
        <br />
        <pre>
</pre>
    </div>
</body>
</html>
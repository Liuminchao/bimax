<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sudunkuai
 * Date: 11-10-12
 * Time: 下午1:17
 * To change this template use File | Settings | File Templates.
 */
 
class AsynchTree extends CWidget {

    public $loadurl="./?r=struct/dept/jsonc";//异步加载地址
    public $parameter=array('org_id'=>'x0002','node_id'=>'x0002');
    public $title='node_name';
    public $value='node_id';
    public $id;
    public $cssUrl;
    public $tiHtmlOption;
    public $priTiELem;
    public $aftTiELem;
    public $root="根节点";//根节点
    public $animate=200;//动画
    public $nodeEvent=false;//节点整行单击事件
    public $beg;
    public $display;
    public $parinit;
    public $scriptParInit;
    public $idinit;
    public $nodeEventInit;
    public $scriptEventInit;
    public $startScript;
    
    public function  init(){
        $this->parameterInit();
        $this->beg();
        $this->regJsFile();
    }
    public function beg(){
        $str="<ul ".$this->idinit." class='treeFile lightTreeview' >";
        $str.="<li class='branch-last' >";
        $str.="<span class='flex-ico flex-close' onclick=flex(this,'".$this->loadurl.$this->parinit."');  ".$this->display." ></span>";
        $str.="<a class='treeview-folder treeview-folder-close' href='javascript:void(0)' ".$this->display." >".$this->priTiELem."<label ".$this->nodeEventInit." ".$this->tiHtmlOption." >".$this->root."</label>".$this->aftTiELem."</a>";
        $str.="</li>";
        echo $str;
    }
    public function parameterInit(){
        if($this->id)
        {
            $this->idinit="id='".$this->id."'";
        }
        if($this->parameter)
        {
            $this->scriptParInit="'".$this->loadurl;
            foreach($this->parameter as $key=>$value){
                $this->parinit.="&".$key."=".$value;
                $this->scriptParInit.="&".$key."='+child.".$key."+'";
            }
            $this->scriptParInit=substr($this->scriptParInit,0,-2);
        }
        if($this->nodeEvent)
        {
            $this->nodeEventInit=" onclick=lableflex(this,'".$this->loadurl.$this->parinit."'); ";
            $this->scriptEventInit=" 'onclick=lableflex(this,\\".$this->scriptParInit."+\"');\" ";
        }else{
            $this->scriptEventInit="\"\"";
        }
        if(!$this->root)
        {
            $this->display=" style='display:none;' ";
            $this->startScript="$('.flex-close').click();";
        }
    }
    public function regCssFile(){
//        if($this->cssUrl===null)
//			$this->cssUrl=CHtml::asset(Yii::getPathOfAlias('system.js.JQtree.treeCSS').'.css');
//		Yii::app()->getClientScript()->registerCssFile($this->cssUrl);
    }
    public function regJsFile(){
        echo <<< EOF
        <script type="text/javascript">
//缩放操作
function flex(obj,url) {
    toggleUl(obj);
    loadchild(obj,url);
}
function lableflex(obj,url) {
    var a=$(obj).parent();
    var sp=a.prev();
    toggleUl(sp);
    loadchild(sp,url);
}
var loadchild = function(obj,url) {
    var ico = $(obj);
    var father = ico.parent();
    var list = $('>ul,>ol', father);
    if (list.eq(0).html()) {
        return;
    }
    father.append("<ul><li id='load'>加载中..</li></ul>");
    $.ajax({
        url:url,
        dataType:"json",
        success:function(data) {
            if(data.success)
            {
                buildChildren(obj,data.children);
            }else{
                $('#load').html("没有子节点");
            }
        }
    })
}

var buildChildren=function(obj,children){
    var ico = $(obj);
    var father = ico.parent();
    var list = $('>ul,>ol', father);
    var ht="";
    var len=children.length;
    for(var i=0;i<len;i++)
    {
        var child=children[i];
        var liC="";
        var aC="";
        var sp="";
        var aS="";
        var cli="";
        if(len-1==i){
            liC="branch-last";
        }
        //alert(i);
        if(child.node_sort==2){
            aC = "treeview-file";
        }else{
            sp="<span class='flex-ico flex-close' onclick=flex(this,'";
            sp+={$this->scriptParInit};
            sp+="'); ></span>";
            aS={$this->scriptEventInit};
            aC = "treeview-folder treeview-folder-close";
        }
        ht+="<li class='"+liC+"' id='"+child.{$this->value}+"'>";
        ht+=sp;
        ht+="<a class='"+aC+"' href='javascript:void(0)'  >{$this->priTiELem}<label "+aS+" {$this->tiHtmlOption} >";
        ht+=child.{$this->title};
        ht+="</label>{$this->aftTiELem}</a></li>";
    }
    list.eq(0).html(ht);
}

var toggleUl = function(obj) {
    var ico = $(obj);
    var father = ico.parent();
    var list = $('>ul,>ol', father);
    var ln = ico.filter('.flex-none').parent();
    var ic = ico.not('.flex-none');
    var fl = $('>.treeview-folder', father);
    ln.toggleClass('node-last-close');
    ic.toggleClass('flex-close');
    fl.toggleClass('treeview-folder-close');
    list.toggle(200);
}
{$this->startScript}
</script>
EOF;
    }
}
<style type="text/css" media="screen">
    #flashContent {
        width: 100%;
        height: 100%;
    }
</style>
<script>
    function getUrl() {
        //http://127.0.0.1/fuplaod.php?mchtid=122222
        return "<?=$this->url?>";
    }
</script>
<div id="flashContent">
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="700" height="400" id="tbowfileupload"
            align="middle">
        <param name="movie" value="<?php echo $this->statics?>/tbowfileupload.swf"/>
        <param name="quality" value="high"/>
        <param name="bgcolor" value="#ffffff"/>
        <param name="play" value="true"/>
        <param name="loop" value="true"/>
        <param name="wmode" value="window"/>
        <param name="scale" value="showall"/>
        <param name="menu" value="true"/>
        <param name="devicefont" value="false"/>
        <param name="salign" value=""/>
        <param name="allowScriptAccess" value="sameDomain"/>
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="<?php echo $this->statics?>/tbowfileupload.swf" width="700" height="400">
            <param name="movie" value="<?php echo $this->statics?>/tbowfileupload.swf" />
            <param name="quality" value="high"/>
            <param name="bgcolor" value="#ffffff"/>
            <param name="play" value="true"/>
            <param name="loop" value="true"/>
            <param name="wmode" value="window"/>
            <param name="scale" value="showall"/>
            <param name="menu" value="true"/>
            <param name="devicefont" value="false"/>
            <param name="salign" value=""/>

            <param name="allowScriptAccess" value="sameDomain"/>
        </object>
        <!--<![endif]-->
    </object>
</div>
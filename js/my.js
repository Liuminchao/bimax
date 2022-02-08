

var displayLoadingLayer=function(){$("#loading").show();};
var hideLoadingLayer=function(){$("#loading").hide();};

function TBModal(){}
TBModal.prototype={
    title:'',
    url:'',
    width:'',
    loader:"<div class='ajax-loading-img'></div>",
    modal:function(){
        $('#modal-title').html(this.title);
        $('#compose-modal').modal('show');
        $("#content-body").html(this.loader);
        $("#content-body").load(this.url);
        // $("#content-body").html("<iframe width='100%' height='500px' frameborder='0' id='iframe' src='"+this.url+"'>loading...</iframe>");
        $("#compose-modal").children("div").css("width",this.width);
    },
    close:function(){
        $("#modal-close").click();
    }
};
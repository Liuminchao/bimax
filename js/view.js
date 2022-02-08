
//证件号显示格式
var play= function (v1) {
        //var str=v1.replace(/[^\d||-]/g, "");
        var str=v1.replace(/[^\S||-]/g, "");
        var maxlen = 9;
        if (str.length < maxlen) {
            maxlen = str.length;
        }
        var temp = "";
        for (var i = 0; i < maxlen; i++) {
            temp = temp + str.substring(i, i + 1);
            if (i==0||(i + 1)==5) {
                temp = temp + " ";
                //alert(111);
            }
        }
        return temp;
    }
//手机号码显示格式    
var see= function (b1) {
        var str=b1.replace(/[^\d]/g, "");
        var maxlen = 11;
        if (str.length < maxlen) {
            maxlen = str.length;
        }
        var temp = "";
        for (var i = 0; i < maxlen; i++) {
            temp = temp + str.substring(i, i + 1);
            if (i != 0 && (i + 1) % 4 == 0 ) {
                temp = temp + " ";
            }
        }
        return temp;
    }
var datetocn= function (a1) {
    
        var w = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
        var year = a1.substring(0, 4);
        var month = a1.substring(5 , 6);
        var day = a1.substring(8, 10);
        if(month==0){
            var strmonth = a1.substring(6,7);
        }else{
            var strmonth = a1.substring(5,7);
        }
        for(var i=1;i<=w.length;i++){
            if(i==strmonth){
                var smonth = w[i-1];
            }
        }
        if(day == ''){
            date = " "+year+smonth;
        }else{
            date = day+" "+smonth+" "+year;
        }
        return date;
}    
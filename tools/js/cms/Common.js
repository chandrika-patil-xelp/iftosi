var common=new Common();
function Common(){
    var _this=this;
    this.eSubmit = function (evt, btnId) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode === 13) {
            $('#' + btnId).click();
        }
    };
    this.isNumberKey = function (evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
		else if (charCode == 13) {
            return false;
        }
        return true;
    };
	this.avoidEnter = function (evt) {
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode == 13) {
            return false;
        }
	};
    this.isDecimalKey = function (evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
            return true;
        }
		else if (charCode == 13) {
            return false;
        }
        else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    };

    this.onlyAlphabets = function (evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode < 47) {
            return true;
        } else
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return true;
        }
        return false;
    };
    this.getLandlineNo=function(){
        var landlineNos="";
        $('.txtCCode').each(function(){
            var val=$(this).val();
            if(val!==""){
                var ln=$(this).siblings('.lnNo').val();
                if(landlineNos == '')
                {
                    landlineNos += val + ln;
                }
                else
                {
                    landlineNos += "|~|" + val + ln;
                }
            }
        });
       return landlineNos;
    };


    this.checkLandline=function(stdid,landlineid){
        var stdcode=$('#'+stdid).val();
        var lnum=$('#'+landlineid).val();
        var num=stdcode+lnum;
        var len=num.length;
        if((num.charAt(0)=='0')&& (len==11) || (num.charAt(0)!='0')&& (len==10)){
            return true;
        }else{
            alert('Please enter correct lanline number.')
            return false;
        }
    }


    _this.nCount=1;
    this.addNumber = function() {
        if(_this.nCount==1){
            var flag=this.checkLandline('lnCode','landline');
            if(flag){
                $('#altNo' + _this.nCount).removeClass('dn');
                _this.nCount++;
            }
        }else{
            var flag=this.checkLandline('lnCode'+(_this.nCount-1),'landline'+(_this.nCount-1));
            if(flag){
                $('#altNo' + _this.nCount).removeClass('dn');
                _this.nCount++;
            }
        }
    };

    this.delNumber = function(id) {
        $('#' + id).addClass('dn');
        $('#' + id + ' input').val('');
        _this.nCount--;
    };

    
    _this.nmbCount=1;
    this.addMobileNumber = function() {
        if(_this.nmbCount==2){
            $('.falmb .addBtn').addClass('dn');
        }
        if(_this.nmbCount==1){
            var flag=this.checkMobile('conMobile');
            if(flag){
                $('#altmbNo' + _this.nmbCount).removeClass('dn');
                _this.nmbCount++;
            }
        }else{
            var flag=this.checkMobile('altmbNo'+(_this.nmbCount-1)+"_Mobile");
            if(flag){
                $('#altmbNo' + _this.nmbCount).removeClass('dn');
                if(_this.nmbCount!==2)
                _this.nmbCount++;
                
            }
        }
    };
    
    this.delmbNumber = function(id) {
        _this.nmbCount--;
        $('.falmb .addBtn').removeClass('dn');
        $('#' + id).addClass('dn');
        $('#' + id + ' input').val('');
    };
    
    this.checkMobile= function(id) {return true;
        var num=$('#'+id).val();
        var len=num.length;
        if((num.charAt(0)=='9')&& (len==10) || (num.charAt(0)=='8')&& (len==10) || (num.charAt(0)=='7')&& (len==10)){
            return true;
        }else{
            alert('Please enter correct mobile number.')
            return false;
        }
        
    };
    
    this.changeTab = function(obj){
        var id=$(obj).attr('id');
        console.log(id);
        $('#'+id).removeClass('op04');
        switch(id){
            case 'step1':                
                $('#tabData2,#tabData3').addClass('dn');
                $('#step2,#step3').addClass('op04');
                $('#tabData1').removeClass('dn');
                $('#disStep').css({width:33+"%"});
                break;
            case 'step2':
                $('#tabData1,#tabData3').addClass('dn');
                $('#tabData2').removeClass('dn');
                $('#step1,#step3').addClass('op04');
                $('#disStep').css({width:66+"%"});
                break;
            case 'step3':
                $('#tabData1,#tabData2').addClass('dn');
                $('#tabData3').removeClass('dn');
                $('#step1,#step2').addClass('op04');
                $('#disStep').css({width:100+"%"});
                break;
            default :
                break;
        }
        
    };
}
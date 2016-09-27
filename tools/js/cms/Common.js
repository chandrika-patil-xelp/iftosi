var common = new Common();
common.checkLogin();
function Common() {
    var _this = this;
    this.APIWebPath = function () {
        return APIDOMAIN;
    };
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
        if (charCode == 46 && (charCode > 48 || charCode < 57)) {
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
    this.isDecimalNumber = function (evt, val) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46 && (charCode > 48 || charCode < 57)) {
            if(val.split('.').length==2) {
                return false;
            } else {
                return true;
            }
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
    this.getLandlineNo = function () {
        var landlineNos = "";
        $('.txtCCode').each(function () {
            var val = $(this).val();
            if (val !== "") {
                var ln = $(this).siblings('.lnNo').val();
                if (landlineNos == '')
                {
                    landlineNos += val + '-' + ln;
                }
                else
                {
                    landlineNos += "|~|" + val + '-' + ln;
                }
            }
        });
        return landlineNos;
    };

    this.number_format = function (number, decimals, dec_point, thousands_sep)
    {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec);
          return '' + Math.round(n * k) / k;
        };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
    return s.join(dec);
    };

    this.checkLandline = function (stdid, landlineid) {
        var stdcode = $('#' + stdid).val();
        var lnum = $('#' + landlineid).val();
        var num = stdcode + lnum;
        var len = num.length;
        if(stdcode == undefined || stdcode == null || stdcode == '' || typeof stdcode == 'undefined')
        {
			this.toast(0,'Landline number is mandatory');
			return false;
		}
		else if(lnum == undefined || lnum == null || lnum == '' || typeof lnum == 'undefined')
        {
			this.toast(0,'Landline number is mandatory');
			return false;
		}
        else
        {
			if ((num.charAt(0) == '0') && (len == 11) || (num.charAt(0) != '0') && (len == 10)) {
				return true;
			} else {
				this.toast(0,'Please enter correct lanline number.');
				return false;
			}
		}
    }


    _this.nCount = parseInt(llCount);
    _this.divNo = (llCount!=0) ? llCount:1;
    var lastReDiv=0;
    this.addNumber = function () {
        var str = '<div id="altNo_' + _this.divNo + '" class="divCon fLeft c666">';
        str += '<div class="titleDiv fmRoboto fLeft font14">Alternate Number</div>';
        str += '<div class="mobileCont fLeft">';
        str += '<input type="text" id="lnCode' + _this.divNo + '" autocomplete="false" class="fLeft txtCCode" placeholder=" Code" maxlength="5">';
        str += '<input type="text" id="landline' + _this.divNo + '" autocomplete="false" value="" onkeypress="return common.isNumberKey(event)" maxlength="8" placeholder=" Contact No. " class="txtInput c666 fLeft fmRoboto font14 lnNo">';
        str += '<div class="fRight delBtn pointer" onclick="common.delNumber(' + _this.divNo + ')"></div></div></div>';
        var validNo=true;
        $('.txtCCode').each(function () {
            var flag = _this.checkLandline($(this).attr('id'), $(this).siblings('.lnNo').attr('id'));
            if(!flag) {
                validNo = false;
            }
        });
        if(!validNo) {
            return false;
        }
        if (_this.divNo <= 4) {
            $('#altNo').append(str);
            _this.nCount++;
            _this.divNo++;
        } else {
            _this.toast(0, 'You can add only 4 Alternate Landline Numbers')
        }
    }

    this.delNumber = function (id) {
        $('#altNo_' + id).remove();
        if(_this.nCount>=0) {
            _this.nCount--;
        }
    };

    _this.nmbCount = parseInt(mNCount);
    this.addMobileNumber = function () {
        if (_this.nmbCount == 0) {
            _this.nmbCount++;
        }
//        if (_this.nmbCount == 2) {
//            $('.falmb .addBtn').addClass('dn');
//        }

        if(this.checkMobile('conMobile')) {

            if($('#altmbNo1').hasClass('dn')) {
                if($('#altmbNo2_Mobile').val()!='') {
                    var flag = this.checkMobile('altmbNo2_Mobile');
                    if(flag) {
                        _this.nmbCount=2;
                        $('#altmbNo1').removeClass('dn');
                    }
                }
                else {
                    _this.nmbCount=1;
                    $('#altmbNo1').removeClass('dn');
                }
            }else if($('#altmbNo2').hasClass('dn')) {
                var flag = this.checkMobile('altmbNo1_Mobile');
                if(flag) {
                    _this.nmbCount=2;
                    $('#altmbNo2').removeClass('dn');
                }
            }
        }
        if (_this.nmbCount == 2) {
            $('.falmb .addBtn').addClass('dn');
        }
//
//        if (_this.nmbCount == 1) {
//            var flag = this.checkMobile('conMobile');
//            if (flag) {
//                $('#altmbNo' + _this.nmbCount).removeClass('dn');
//                _this.nmbCount++;
//            }
//        } else {
//            var flag = this.checkMobile('altmbNo' + (_this.nmbCount - 1) + "_Mobile");
//            if (flag) {
//                $('#altmbNo' + _this.nmbCount).removeClass('dn');
//                if (_this.nmbCount !== 2)
//                    _this.nmbCount++;
//            } else {
//                $('.falmb .addBtn').removeClass('dn');
//            }
//        }
    };

    this.delmbNumber = function (id) {
        _this.nmbCount--;
        $('.falmb .addBtn').removeClass('dn');
        $('#' + id).addClass('dn');
        $('#' + id + ' input').val('');
    };

    this.checkMobile = function (id) {
        var num = $('#' + id).val();
		var mobreg=/^[789]\d{9}$/;
        var len = num.length;
       // if ((num.charAt(0) == '9') && (len == 10) || (num.charAt(0) == '8') && (len == 10) || (num.charAt(0) == '7') && (len == 10)) {
           if((mobreg.test(num)==true) && (len == 10)) 
           {
            var conMobile = $('#conMobile').val();
            var altmbNo1_Mobile = $('#altmbNo1_Mobile').val();
            var altmbNo2_Mobile = $('#altmbNo2_Mobile').val();
            if (altmbNo1_Mobile!='' || altmbNo2_Mobile!='') {
                if(conMobile==altmbNo1_Mobile) {
                    this.toast(0, 'Please enter unique mobile number.')
                    return false;
                } else if(altmbNo1_Mobile==altmbNo2_Mobile) {
                    this.toast(0, 'Please enter unique mobile number.')
                    return false;
                } else if(conMobile==altmbNo2_Mobile) {
                    this.toast(0, 'Please enter unique mobile number.')
                    return false;
                }
            }
            return true;
        } else {
            this.toast(0, 'Please enter correct mobile number.')
            return false;
        }

    };
    this.validateEmail = function (id) {
        var email = $('#' + id).val();
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    };

    this.validateUrl = function (id) {
        var web = $('#' + id).val();
        var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
        var regex = new RegExp(expression);
        if (web.match(regex))
        {
            return true;
        } else {
            this.toast(0,"Please enter valid URL");
            return false;
        }
    };

    this.changeTab = function (obj) {
        var id = $(obj).attr('id');
        $('#' + id).removeClass('op04');
        switch (id) {
            case 'step1':
                $('#tabData2,#tabData3').addClass('dn');
                //$('#step2,#step3').addClass('op04');
                $('#tabData1').removeClass('dn');
                $('#disStep').css({width: 33 + "%"});
                break;
            case 'step2':
                $('#tabData1,#tabData3').addClass('dn');
                $('#tabData2').removeClass('dn');
                //$('#step1,#step3').addClass('op04');
                $('#disStep').css({width: 66 + "%"});
                break;
            case 'step3':
                $('#tabData1,#tabData2').addClass('dn');
                $('#tabData3').removeClass('dn');
                //$('#step1,#step2').addClass('op04');
                $('#disStep').css({width: 100 + "%"});
                break;
            default :
                break;
        }

    };

    this.formatDate = function (date, type) {
        var sDate = date.split('-');
        var year = sDate[0];
        var day = sDate[2];
        var month = sDate[1] - 1;
        var monthList = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        var dateFormat = '';
        if (type == 1) {  // 01/01/2015
            dateFormat = day + '/' + month + '/' + year;
        } else if (type == 2) {  // 01 Jan, 2015
            dateFormat = day + ' ' + monthList[month] + ', ' + year;
        } else if (type == 2) {  // 01 Jan, 2015
            dateFormat = day + ' ' + monthList[month] + ', ' + year;
        }
        return dateFormat;
    };

    this.toast = function (mType, msg) {
      common.msg(mType, msg);

        $('.close').click();
        return false;
        $.toast.config.width = 400;
        $.toast.config.closeForStickyOnly = false;
        if (mType == 0)
        {
            $.toastr(msg, {duration: 5000, type: "danger"});
        }
        else if (mType == 1)
        {
            $.toastr(msg, {duration: 5000, type: "success"});
        }
        else if (mType == 2)
        {
            $.toastr(msg, {duration: 5000, type: "stock"});
        }
        else if (mType == 3)
        {
            $.toastr(msg, {duration: 5000, type: "sold"});
        }
        else if (mType == 4)
        {
            $.toastr(msg, {duration: 5000, type: "instock"});
        }
        setTimeout(function () {
            $('.close').click();
        }, 5000);
    }

    this.checkLogin = function () {
        var isLoggedIn = customStorage.readFromStorage('isLoggedIn');
        var mob = customStorage.readFromStorage('tf_mobile');
        var nm = customStorage.readFromStorage('username');
        var is_vendor = customStorage.readFromStorage('is_vendor');

		if(is_vendor !== '' && (is_vendor == -1 || is_vendor == '-1'))
		{
			is_vendor = 0;
		}

        if (isLoggedIn === 'true' && is_vendor == 1) {}
        else if (isLoggedIn === 'true' && is_vendor == 2) {}
		else
		{
            window.location.href = DOMAIN;
        }
    };

    this.doLogout = function () {
        customStorage.removeAll();
        window.location.href = window.location;
    };

     this.IND_money_format = function(money)
    {
        var m = '';
        money = money.toString().split("").reverse();
        var len = money.length;
        for(var i=0;i<len;i++)
        {
        if(( i == 3 || (i > 3 && ( i - 1) % 2 == 0) ) && i !== len)
        {
        	m += ',';
        }
        m += money[i];
	  }

	return m.split("").reverse().join("");
    };

    this.caratCheck = function (purity)
    {
            var carats = new Array();
            carats['24'] = "995";
            carats['18'] = "750";
            carats['22'] = "916";
            carats['14'] = "585";
            return carats[purity];
    };

    this.msg = function(t, e) {
      toastr.remove();
            if(t==0 || t==2)
                t='danger';

            if(t==3)
                t='info';

            if(t==4)
                t='success';

            $("danger" === t ? function() {
                toastr.error(e)
            } : "info" === t ? function() {
                toastr.info(e)
            } : "success" === t ? function() {
                toastr.success(e)
            } : function() {
                //toastr.warning(e)
            });
    }, toastr.options = {
                    closeButton: !0,
                    debug: !1,
                    newestOnTop: !0,
                    progressBar: 1,
                    positionClass: "toast-top-right",
                    preventDuplicates: true,
                    showDuration: "100",
                    hideDuration: "800",
                    timeOut: "1000",
                    extendedTimeOut: "1000",
                    showEasing: "",
                    hideEasing: "",
                    showMethod: "slideDown",
                    hideMethod: "slideUp"
                }, this.decodeMsg = function(t) {
                    return decodeURIComponent(t.replace(/\+/g, " "))
                }, this.encodeMsg = function(t) {
                    return encodeURIComponent(t)
                }, this.uid = function() {
                    var t = webstore.get("uid");
                    return t
                }, this.appendDiv = function(t, e, n) {
                    if ("" != n) {
                        var o = document.getElementById(t);
                        o.innerHTML = e ? n : o.innerHTML + n
                    }
                }, this.replaceURLWithHTMLLinks = function(t) {
                    var e = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi;
                    return t.replace(e, "<a href='$1' target='_blank'>$1</a>")
                }, this.getParameterByName = function(t) {
                    t = t.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                    var e = new RegExp("[\\?&]" + t + "=([^&#]*)"),
                        n = e.exec(location.search);
                    return null == n ? "" : decodeURIComponent(n[1].replace(/\+/g, " "))
                }, this.redirect = function(t) {
                    window.location = t
                }, this.capitaliseFirstLetter = function(t) {
                    var e = t.value;
                    "" != e && (t.value = e.charAt(0).toUpperCase() + e.slice(1))
                }, this.capitalize = function(t) {
                    var e = t.value.toLowerCase();
                    "" != e && (t.value = e.replace(/\w\S*/g, function(t) {
                        return t.charAt(0).toUpperCase() + t.substr(1).toLowerCase()
                    }))
                }, this.castLowerCase = function(t) {
                    document.getElementById(t).value = document.getElementById(t).value.toLowerCase()
                };





}

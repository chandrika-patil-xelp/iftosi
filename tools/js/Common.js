var common = new Common();
common.checkLogin();

function Common() {
    var _this = this;

    this.validateEmail = function (email) {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!email.match(mailformat)) {
            return 1;
        }
        return 0;
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

    this.validateEmail = function (id) {
        var email = $('#' + id).val();
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    };

    this.checkMobile = function (id) {
        var num = $('#' + id).val();
        var len = num.length;
        if ((num.charAt(0) == '9') && (len == 10) || (num.charAt(0) == '8') && (len == 10) || (num.charAt(0) == '7') && (len == 10)) {
            return true;
        } else {
            this.toast(0,'Please Enter Correct Mobile Number.')
            return false;
        }
    };
    function isValidMKey(evt, id) {
        var val = $('#' + id).val();
        var len = val.length;
        var match = /^[7-9]/g;
        var val = val.match(match);
        if (val == null) {
            $('#' + id).val('');
        }
        if (len == 10) {
            $('#tf_email').focus();
        }
    }
    this.checkLogin = function () {
        var isLoggedIn = customStorage.readFromStorage('isLoggedIn');
        var mob = customStorage.readFromStorage('tf_mobile');
        var uid = customStorage.readFromStorage('userid');
        var nm = customStorage.readFromStorage('username');
        var is_vendor = customStorage.readFromStorage('is_vendor');
        var type = customStorage.readFromStorage('busiType');
        if(is_vendor == 1)
        {
            if((type != null) && (type != undefined))
            {
                type=type.charAt(0);
                switch(type)
                {
                    case '1':var catid=10000;
                    break;
                    case '2':var catid=10002;
                    break;        
                    case '3':var catid=10003;
                    break;
                    default: break;
                }
            }
        }
        var userMenuStr='';
        if (isLoggedIn === 'true') {
            $('.signInUpTab').html('Hello ' + nm).addClass('loggedIn');
            $('#userMenu').removeClass('dn');
            if (is_vendor == 0)
        {
                //userMenuStr += '<li class="transition100">Profile</li>';
                //userMenuStr += '<li class="transition100">Orders</li>';
                userMenuStr += '<li class="transition100" onclick="window.location.assign(\''+DOMAIN+'index.php?case=wishlist&uid='+uid+'\');">Wishlist (25)</li>';
        } else {
                //userMenuStr += '<li class="transition100" onclick="window.location.assign(\''+DOMAIN+'index.php?case=vendor_dashboard\');">Dashboard</li>';
                userMenuStr += '<li class="transition100" onclick="window.location.assign(\''+DOMAIN+'index.php?case=vendor_landing&catid='+catid+'\');">Products</li>';
                userMenuStr += '<li class="transition100" onclick="window.location.assign(\''+DOMAIN+'index.php?case=vendor_enquiries\');">Enquiry</li>';
                //userMenuStr += '<li class="transition100" onclick="window.location.assign(\''+DOMAIN+'index.php?case=vendor_setting\');">Setting</li>';
        }
            userMenuStr += '<li class="transition100" onclick="common.doLogout();">Log Out</li>';
            $('#hdropList').html(userMenuStr);
        }
    };

    this.doLogout = function () {
        customStorage.removeFromStorage('isLoggedIn');
        customStorage.addToStorage('isLoggedIn', false);
        window.location.href = DOMAIN + "index.php";
    };
    this.closeLoginForm = function () {
        $('#loginDiv').velocity({scale: 0}, {delay: 0, ease: 'swing'});
        $('#overlay1').velocity({opacity: 0}, {delay: 100, ease: 'swing'});
        setTimeout(function () {
            $('#overlay1,#loginDiv').addClass('dn');
            $("#loginDiv,#overlay1").remove();
        }, 1010);
    }
    this.showLoginForm = function () {
        
        var str = '<div id="overlay1" class="overlay transition300" style="opacity: 0;" onclick="common.closeLoginForm();"></div>';
        str += '<div id="loginDiv" class="loginDiv transition300" style="transform: scale(0);">';
        str += '<div class="lgTitle fLeft fmOpenR">One account. All about Diamonds</div>';
        str += '<div class="inputCont fLeft fmOpenR">';
        str += '<input type="tel" id="pr_mobile" name="pr_mobile" autocomplete="off" maxlength="10" class="txtInput cOrange fmOpenR font14 mobileIcon">';
        str += '<label for="pr_mobile" class="inp-label transition100">MOBILE</label>';
        str += '<div id="pr_mobile_inpText" class="inpText fRight transition300">enter<br>mobile number</div>';
        str += '</div>  ';
        str += ' <div class="inputCont fLeft fmOpenR">';
        str += '<input type="password" id="pr_pass" name="pr_pass" autocomplete="off" maxlength="10" class="txtInput cOrange fmOpenR font14 passwordIcon">';
        str += '<label for="pr_pass" class="inp-label transition100">PASSWORD</label>';
        str += '<div id="pr_pass_inpText" class="inpText fRight transition300">enter your<br>password</div>';
        str += '</div>  ';
        str += '<div class="cancelLgBtn fLeft fmOpenR" id="lgCancel" onclick="common.closeLoginForm();"> CANCEL</div>';
        str += '<div class="loginBtn fLeft fmOpenR" id="lgSubmit" onclick="common.submitLoginForm();">LOGIN</div>';
        str += '<div class="signuplink fmOpenB fLeft">';
        str += '<center><a href="'+ DOMAIN +'index.php?case=signup"><span>Sign Up with Us</span></a></center>';
        str += '</div>';
        str += '</div>';
        $('body').append(str);
        $('input[type=tel], input[type=password]').bind('focus',function() {
            $(this).siblings('label, i').addClass('labelActive');
            $(this).addClass('brOrange');//.removeClass('brRed');
            $('.bsText').addClass('op0');
        });

        $('input[type=tel], input[type=password]').bind('blur',function() {
            if ($(this).val().length === 0 && $(this).attr('placeholder') === undefined) {
                $(this).siblings('label, i').removeClass('labelActive');
                $(this).removeClass('brOrange brGreen');//.addClass('brRed');
            } else {
                $(this).removeClass(' ').addClass('brGreen');
            }
        });

        $('#loginDiv').velocity({scale: 0}, {delay: 0, duration: 0});
        $('#overlay1').velocity({opacity: 0}, {delay: 0, duration: 0});
        var mobile = customStorage.readFromStorage('mobile');
        var name = customStorage.readFromStorage('name');
        var email = customStorage.readFromStorage('email');

        if (mobile == '' || mobile == null || mobile == undefined)
        {
            $('#overlay1,#loginDiv').removeClass('dn');
            setTimeout(function () {
                $('#overlay1').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
                $('#loginDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
            }, 10);
        }
        else
        {

        }
    }
    this.submitLoginForm = function () {
        var pr_mobile = $('#pr_mobile').val();
        var pr_pass = $('#pr_pass').val();
        if (pr_mobile == '') {
            customStorage.toast(0, 'Mobile Number Should Not Be Empty');
            $('#pr_mobile').focus();
            return;
        } else if (pr_pass == '') {
            customStorage.toast(0, 'Login Password Should Not Be Empty');
            $('#pr_pass').focus();
            return;
        } else {
            $.ajax({url: DOMAIN + "apis/index.php?action=logUser&mobile=" + pr_mobile + "&password=" + pr_pass, success: function (result) {
                var obj = jQuery.parseJSON(result);
                var errCode = obj['error']['code'];
                if (errCode == 0) {
                    var userid = obj['results']['uid'];
                    var username = obj['results']['username'];
                    var is_vendor = obj['results']['utype'];
                    customStorage.addToStorage('isLoggedIn', true);
                    customStorage.addToStorage('l', pr_mobile);
                    customStorage.addToStorage('p', pr_pass);
                    customStorage.addToStorage('userid', userid);
                    customStorage.addToStorage('username', username);
                    customStorage.addToStorage('is_vendor', is_vendor);
                    if (is_vendor == 1) {
                        var busiType = obj['results']['busiType'];
                        var busitype = customStorage.addToStorage('busiType', busiType);
                        if(busitype !== null || busitype !== undefined || busitype !== '')
                        {
                        var catid = busiType.charAt(0) - 1;
                        window.location.assign(DOMAIN + 'index.php?case=vendor_landing&catid=1000' + catid);
                        }
                        else
                        {
                            window.location.assign(DOMAIN + 'index.php?case=vendor_Form&uid='+userid);
                            console.log('vendor Form');
                        }
                    } else {
                        customStorage.addToStorage('busiType', '');
                        _this.checkLogin();
                        _this.closeLoginForm();
                    }
                } else {
                    customStorage.toast(0, 'Invalid Login Credentials');
                }
            }});
        }
    }
}
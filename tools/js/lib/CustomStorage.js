var customStorage = new CustomStorage();
var date = null;

function CustomStorage() {
    var _this = this;
    this.addToStorage = function (id, val)
    {
        if (val)
        {
            if (typeof (Storage) !== "undefined") {
                localStorage.setItem(id, val);
            } else {
                date = new Date();
                date.setYear(date.getFullYear() + 1);
                document.cookie = id + '=' + val + ';expires=' + date + ';path=/;'
            }
        }
    }

    this.readFromStorage = function (id)
    {
        if (typeof (Storage) !== "undefined")
            return localStorage.getItem(id);
        else
            return _this.getCookie(id);
    }

    this.removeFromStorage = function (id)
    {
        date = new Date();
        date.setYear(date.getFullYear() - 1);
        if (typeof (Storage) !== "undefined")
            localStorage.removeItem(id);
        else
            document.cookie = id + '=;expires=' + date + ';path=/;'
    }

    this.getCookie = function (cn) {
        if (document.cookie.length > 0) {
            var c_start = document.cookie.indexOf(cn + "=");
            if (c_start != -1) {
                c_start = c_start + cn.length + 1;
                var c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1)
                    c_end = document.cookie.length;
                var cvalue = _this.cookie_unescape(document.cookie.substring(c_start, c_end));
                return unescape(cvalue);
            }
        }
        return "";
    }
    this.cookie_unescape = function (str)
    {
        str = "" + str;
        while (true)
        {
            var i = str.indexOf('+');
            if (i < 0)
                break;
            str = str.substring(0, i) + '%20' +
                    str.substring(i + 1, str.length);
        }
        return unescape(str);
    }

    this.toast = function (mType, msg) {
        $('.close').click();
        $.toast.config.width = 400;
        $.toast.config.closeForStickyOnly = false;
        if (mType == 0) {
            $.toast(msg, {duration: 5000, type: "danger"});
        } else if (mType == 1) {
            $.toast(msg, {duration: 5000, type: "success"});
        }
        setTimeout(function () {
            $('.close').click();
        }, 5000);
    }

}

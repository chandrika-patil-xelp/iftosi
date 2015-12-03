
function Search() {
    var _this = this;
    _this["path"] = "";
    _this["autoSuggestDivId"];
    _this["tfSearchId"];
    _this["callBackAutoSuggest"];
    _this["appId"];
    _this["minimumSearchChar"] = 2;
    _this["scripSearch"];
    _this["suggestLen"] = 0;
    _this["suggestRow"] = -1;
    _this["ltSuggestRow"] = -1;
    _this["suggestionList"];
    _this["resultArray"] = new Array();
    _this["searchType"] = "location";
    _this["defaultPos"] = 0;
    _this["setData"] = false;
    _this["rowLimit"] = 4;
    this["setAppId"] = function (strId) {
        _this["appId"] = strId
    };
    this["setMinimumSearchChar"] = function (minSearchChar) {
        _this["minimumSearchChar"] = minSearchChar
    };
    this["setPath"] = function (strPath) {
        _this["path"] = strPath
    };
    this["setId"] = function (autoSuggestDiv, tfSearch, searchCallBack, searchDivOutput) {
        _this["autoSuggestDivId"] = autoSuggestDiv;
        _this["tfSearchId"] = tfSearch;
        _this["callBackAutoSuggest"] = searchCallBack;
        _this["scripSearch"] = searchDivOutput;
    };
    this["setSearchResult"] = function (id) {
        _this["setData"] = true;
        if (_this["arrayResult"]) {
            _this["lastChar"] = "";
            _this["callBackAutoSuggest"](_this["suggestionList"][id]);
        } else {
            var obj = jQuery["parseJSON"](_this["suggestionList"]);
            _this["lastChar"] = "";
            _this["callBackAutoSuggest"](obj[id]);
        }
        ;
    };
    this["getKeyVal"] = function (evt) {
        var keyVal;
        if (evt) {
            keyVal = evt["keyCode"]
        } else {
            keyVal = window["event"]["keyCode"]
        }
        ;
        return keyVal;
    };
    this["highlightSuggest"] = function (evt, str) {
        var keyVal = _this["getKeyVal"](evt);
        if (keyVal != 38 && keyVal != 40 && keyVal != 13) {
            _this["suggestRow"] = -1;
            _this["ltSuggestRow"] = -1;
        } else {
            var searchFlag = false;
            if (_this["arrayResult"]) {
                searchFlag = true
            } else {
                if (str["length"] > 0) {
                    searchFlag = true
                }
            }
            ;
            if (searchFlag) {
                if (keyVal == 40) {
                    if (_this["suggestRow"] >= _this["suggestLen"]) {
                        _this["suggestRow"] = -1
                    }
                    ;
                    _this["suggestRow"]++;
                    _this["handler"](_this["suggestRow"]);
                } else {
                    if (keyVal == 38) {
                        if (_this["suggestRow"] <= 0) {
                            _this["suggestRow"] = 1
                        }
                        ;
                        _this["suggestRow"]--;
                        _this["handler"](_this["suggestRow"]);
                    } else {
                        if (keyVal == 13) {
                            if (_this["suggestRow"] < 0) {
                                _this["suggestRow"] = 0
                            }
                            ;
                            _this["setSearchResult"](_this["suggestRow"]);
                            return false;
                        }
                    }
                }
                ;
                if (_this["ltSuggestRow"] > -1) {
                    _this["suggestResultOut"](_this["ltSuggestRow"])
                }
                ;
                _this["suggestResultOver"](_this["suggestRow"]);
                _this["ltSuggestRow"] = _this["suggestRow"];
                return false;
            }
            ;
        }
        ;
        return true;
    };
    this["handler"] = function (rowNo) {
        if (_this["suggestLen"] > _this["rowLimit"]) {
            rowNo++;
            if (_this["suggestLen"] < rowNo) {
                $("#" + _this["autoSuggestDivId"] + " .smallField")["scrollTop"](0);
                _this["suggestRow"] = 0;
            } else {
                if (rowNo >= _this["rowLimit"]) {
                    var diff = (rowNo - _this["rowLimit"]);
                    var scroll = 30 * diff;
                    $("#" + _this["autoSuggestDivId"] + " .smallField")["scrollTop"](scroll);
                }
            }
            ;
        }
    };
    _this["lastSuggestRow"] = "";
    this["suggestResultOver"] = function (rowNo) {
        var rowId = "suggest" + rowNo;
        $("#" + rowId)["addClass"]("autoSuggestRowSelect");
        _this["lastSuggestRow"] = rowId;
    };
    this["suggestResultOut"] = function (rowNo) {
        if (_this["lastSuggestRow"] != "") {
            $("#" + _this["lastSuggestRow"])["removeClass"]("autoSuggestRowSelect")
        }
    };
    this["reverse"] = false;
    this["setAutoSuggestDivPos"] = function () {
    };
    _this["arrayResult"] = false;
    _this["lastChar"] = "";
    this["showHint"] = function (str, evt) {
        if (_this["arrayResult"]) {
            if (_this["highlightSuggest"](evt, str)) {
                _this["scripSearch"](str)
            }
        } else {
            if (_this["highlightSuggest"](evt, str)) {
                str = str["replace"](" ", "");
                if ((str != _this["lastChar"]) && (str != "")) {
                    _this["lastChar"] = str;
                    var spath = _this["path"] + global["encodeMsg"](str);
                    global["getData"](spath, _this["scripSearch"]);
                } else {
                    document["getElementById"](_this["autoSuggestDivId"])["innerHTML"] = ""
                }
                ;
            }
        }
    };
    this["searchFocusLost"] = function () {
        if (!_this["setData"]) {
            _this["setSearchResult"](_this["defaultPos"])
        }
    };
    this["arraySuggest"] = function (str) {
        var stPos = new Array();
        if (str != "") {
            str = str["toLowerCase"]();
            var len = _this["resultArray"]["length"];
            var i = 0;
            var strLen = str["length"];
            while (i < len) {
                var ld = (_this["resultArray"][i]["substring"](0, strLen))["toLowerCase"]();
                console["log"]("arraySuggest " + ld + " str " + str);
                if (ld == str) {
                    stPos["push"](i)
                }
                ;
                i++;
            }
            ;
        }
        ;
        return stPos;
    };
    this["appendSuggestion"] = function (key, res, obj) {
        var len = obj["length"];
        var i = 0;
        res = res["toUpperCase"]();
        var slen = res["length"];
        var tmp = new Array();
        while (i < len) {
            var t = (obj[i][key])["split"](" ");
            var len1 = t["length"];
            var j = 0;
            while (j < len1) {
                var st = (t[j]["substring"](0, slen))["toUpperCase"]();
                if (res == st) {
                    tmp["push"](obj[i]);
                    break;
                }
                ;
                j++;
            }
            ;
            i++;
        }
        ;
        return tmp;
    };
    this["finalSuggestion"] = function (st1, st2) {
        var stLen1 = st1["length"];
        var stLen2 = st2["length"];
        if (stLen1 == 0) {
            if (stLen2 != 0) {
                return st2
            } else {
                return false
            }
        } else {
            if (stLen2 == 0) {
                if (stLen1 != 0) {
                    return st1
                } else {
                    return false
                }
            } else {
                var i = 0;
                var f = new Array();
                f = f["concat"](st1);
                while (i < stLen2) {
                    var j = 0;
                    var flag = true;
                    while (j < stLen1) {
                        if (st2[i]["c"] == st1[j]["c"]) {
                            flag = false;
                            break;
                        }
                        ;
                        j++;
                    }
                    ;
                    if (flag) {
                        f["push"](st2[i])
                    }
                    ;
                    i++;
                }
                ;
                return f;
            }
        }
        ;
    };
}

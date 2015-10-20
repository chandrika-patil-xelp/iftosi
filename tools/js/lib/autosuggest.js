/*

	Autosuggest Component By Xelpmoc and Team.
	
	Parameters
	str: String for search
	txt: Text Box ID to be with # attached
	suggest: Suggestion Box ID to be With # attached
	filepath: File Path
	params: Get Parameters as a String
	vcheck: Do you want data to be retrived when the search string is empty (if yes pass true else pass false)
	
	Callback Function
	arrangeData(data): Callback function with data passed to it. Should be written at your end.

*/

var SUGGESTObject = null;
var auto = true;
var timeoutURL= "";
var timeoutparameter= "";
var timeoutdivHolder= "";
var timeauto= "";
var autodata = '';
var JSONcache = new Array();

Autosuggest = function(str,txt,suggest,filepath,params,vcheck,nextxt,hiddenid) {
	
	if (handleKeys(event,txt,suggest,nextxt,'',hiddenid,params)==true)
	{
		return false;
	}
	SUGGESTION(filepath,params,suggest,txt,vcheck,nextxt);
}

SUGGESTION = function(URL,parameter,divHolder,txt,vcheck,nextxt) {
	
	if(!vcheck)
	{
		if($(txt).val() == '')
		{
			$(divHolder).html('');
			return false;
		}
	}

	if (SUGGESTObject != null)	SUGGESTObject.abort();
	if(auto == true) {
			clearTimeout(timeauto);
			timeauto = setTimeout('timout()',250);
			timeoutURL=URL;
			timeoutparameter=parameter;
			timeoutdivHolder=divHolder;
			timeouttxt=txt;
			timeoutvcheck=vcheck;
			timeoutvnxt=nextxt;
			return false;
		}
	auto = true;
	
	if(JSONcache[parameter])
	{
		var Jdata = JSONcache[parameter];
		if(Jdata && Jdata.results)
		{
			autodata = arrangeData(Jdata,txt,divHolder,nextxt);
			$(divHolder).html(autodata);
			$(divHolder).removeClass('dn');
		}
		else
		{
			$(divHolder).html(autodata);
			$(divHolder).removeClass('dn');
		}
	}
	else
	{
		SUGGESTObject = $.getJSON(URL, parameter, function(data) {
			
			if(data && data.results)
			{
				autodata = arrangeData(data,txt,divHolder,nextxt);
				//JSONcache[parameter] = data;
				$(divHolder).html(autodata);
				$(divHolder).removeClass('dn');
			}
			else
			{
				$(divHolder).html('');
				$(divHolder).áddClass('dn');
			}
		});
	}
}

function timout() {
	auto=false;
	SUGGESTION(timeoutURL,timeoutparameter,timeoutdivHolder,timeouttxt,timeoutvcheck,timeoutvnxt);
	timeauto = null;
}

function setData(cid,cval,id,divHolder,nextxt,hiddenid)
{
	common.addToStorage(id.replace('#',''),cval);
	$(id).val(cval);
	if(typeof(hiddenid) !== "undefined") {
		$(hiddenid).val(cid);
		common.addToStorage(hiddenid.replace('#',''),cid);
	}	
	setTimeout(function (){
		focusfn();
	},20);
	setTimeout(function (){
		$(divHolder).addClass('dn');
		$(divHolder).html();
	},20);
	makeCall(id,cid);
	auto = false;
	cleardata();
}

function cleardata()
{
	var SUGGESTObject = null;
	var auto = true;
	var timeoutURL= "";
	var timeoutparameter= "";
	var timeoutdivHolder= "";
	var timeauto= "";
	var autodata = '';
}

function focusfn()
{
	$(timeoutvnxt).focus();
}

var handleKeys = function(evt,txt,divHolder,nextxt,divSelection,hiddenid,params) {
	if (!divSelection) divSelection = $(divHolder + " ul li.hover");
	var keyCode = evt.which;
	
	if(keyCode == 13) {
		$(divHolder).addClass('dn');
		$(divHolder+' ul li').each(function(index,data) {
				var dtxt = $(divSelection).text();
				var id = $(divSelection).attr('id');
				setData(id,dtxt,txt,divHolder,nextxt,hiddenid);
				return true;
		});
		return true;
	}
	if (keyCode == 38 || keyCode == 40) {
		
		var li_Index="-1";
		$(divHolder+' ul li').each(function(index,data) {
			if($(data).hasClass("hover")==true) {
				li_Index = index;
			}
		});
		if(keyCode == 38 && li_Index == "-1")
			li_Index = $(divHolder+' ul li').length;
		if (keyCode == 38) {
			if (li_Index==0)
				li_Index = $(divHolder+' ul li').length-1;
			else
				li_Index--;
		} else {
			if (($(divHolder+' ul li').length-1)==li_Index)
				li_Index = 0;
			else
				li_Index++;
		}
		$(divHolder+' ul li').each(function(index,data) {
			if (index  == li_Index) {
				$(this).addClass("hover");
			}
			else {
				$(this).removeClass("hover");
			}
		});
		return true;
	 }
	return false;
}

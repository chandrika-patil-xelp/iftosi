$(document).ready(function () {
    var uid = customStorage.readFromStorage('userid');
    var is_vendor = customStorage.readFromStorage('is_vendor');
	var isLoggedIn = customStorage.readFromStorage('isLoggedIn');

	if(isLoggedIn !== undefined && isLoggedIn !== null && isLoggedIn !== '' || typeof isLoggedIn !== 'undefined' && (isLoggedIn == 'true' || isLoggedIn == true))
	{
		if(is_vendor == 2)
		{
			$('#productstab').bind('click',function(){
			window.location.href=DOMAIN+'index.php?case=product_list&uid='+uid;
			});
			$('#vendorlist').bind('click',function(){
			window.location.href=DOMAIN+'index.php?case=vendorList&uid='+uid;
			});
		}
		else
		{
			window.location.href = DOMAIN;
		}
	}
});
var uid = customStorage.readFromStorage('userid');
var loadEnq = true;
//var pgno = 1;

loadEnqs(1);
function loadEnqs(pgno) {
    	if(!pgno)
		pgno = 1;
    $.ajax({url: common.APIWebPath() + "index.php?action=viewLog&vid=" + uid + "&page=" + pgno + "&limit=50", success: function (result) {
            loadEnqCallback(result,pgno);
    }});
}
function loadEnqCallback(res,pgno)
{
    var obj = jQuery.parseJSON(res);
    if (obj.results !== undefined && obj.results !== 'undefined' && obj.results !== null && obj.results !== 'null' && obj.results !== '')
    {
        if (obj.total_enqs !== undefined && obj.total_enqs !== 'undefined' && obj.total_enqs !== null && obj.total_enqs !== 'null' && obj.total_enqs !== '')
        {
             if (obj.total_pages !== undefined && obj.total_pages !== 'undefined' && obj.total_pages !== null && obj.total_pages !== 'null' && obj.total_pages !== '')
            {   
                
                var total = (obj.total_enqs == 1 ? obj.total_enqs + ' Enquiry Listed' : obj.total_enqs + ' Enquiries Listed');
                $('#totalEnqs').text(total);
                
                var total_pages = obj.total_pages;
        
                var str = '';
                if(total_pages !== 0 && total_pages !== '0')
                {
                    if(total_pages == pgno)
                    {
                        loadEnq = false;
                    }
                    var enqs = obj.total_enqs;
                    if(enqs == false)
                    {
                        enqs = 0;
                    }
                    else
                    {
                        enqs = enqs;
                    }
                    var len = enqs;
                    var i = 0;
                    while (i < len)
                    {
                        str += generateEnqList(obj.results[i]);
                        i++;
                    }
                    var html = pagination(obj,pgno);
                    pgno++;
                }
                else
                {
                    str += '<p class="noRecords"><span>Sorry! No Enquiries Found!</span></p>';
                    loadEnq = false;
                }
            }
        }
        $('#enqList').html(str);
        $('#enqList').append(html);
        $('.pgComm').click( function()
        {
            $('.pgComm').removeClass('pgActive');
            $(this).addClass('pgActive');
            loadEnqs($(this).text());
            $('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
        });
    }
    else
    {
        loadEnq = false;
    }
}

function pagination(data,pgno)
{
	/* For Pagintion */
	pgno = pgno*1;
	var html = '';
	var tc = (data.results.total_products ? data.results.total_products : data.results.total_enqs);
	var lastpg = Math.ceil(tc/50);
	var adjacents = 2;
	
	if(lastpg > 1)
	{
		html += '<div class="fLeft pagination fmOpenR">';
			html += '<center>';
				html += '<div class="pPrev poR ripplelink">Previous</div>';
				if(lastpg < 7 + (adjacents * 2))
				{
					for(var i = 1; i <= lastpg; i++)
					{
						if(i == pgno)
						{
							html += '<div class="pgComm poR ripplelink pgActive">'+i+'</div>';
						}
						else
						{
							html += '<div class="pgComm poR ripplelink">'+i+'</div>';
						}
					}
				}
				else if(lastpg > 5 + (adjacents * 2))
				{
					if(pgno < 1 + (adjacents * 2))
					{
						for (var i = 1; i < 4 + (adjacents * 2); i++)
						{
							if(i == pgno)
							{
								html += '<div class="pgComm poR ripplelink pgActive">'+i+'</div>';
							}
							else
							{
								html += '<div class="pgComm poR ripplelink">'+i+'</div>';
							}
						}
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgComm poR ripplelink">'+lastpg+'</div>';
					}
					else if(lastpg - (adjacents * 2) > pgno && pgno > (adjacents * 2))
					{
						html += '<div class="pgComm poR ripplelink">1</div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						for (var i = pgno - adjacents; i <= pgno + adjacents; i++)
						{
							if(i == pgno)
							{
								html += '<div class="pgComm poR ripplelink pgActive">'+i+'</div>';
							}
							else
							{
								html += '<div class="pgComm poR ripplelink">'+i+'</div>';
							}
						}
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgComm poR ripplelink">'+lastpg+'</div>';
					}
					else
					{
						html += '<div class="pgComm poR ripplelink">1</div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						for (var i = lastpg - (2 + (adjacents * 2)); i <= lastpg; i++)
						{
							if(i == pgno)
							{
								html += '<div class="pgComm poR ripplelink pgActive">'+i+'</div>';
							}
							else
							{
								html += '<div class="pgComm poR ripplelink">'+i+'</div>';
							}
						}
					}
				}
				html += '<div class="pNext poR ripplelink">Next</div>';
			html += '</center>';
		html += '</div>';
	}
	return html;
}


function generateEnqList(obj)
{
    if(obj !== undefined && obj !== 'undefined' && obj !== null && obj !== 'null')
    {
        
        var date = obj.enquiry.update_time.split(' ');
        var pro_dtls=obj.pro_dtls;
        var search = obj.search;
        var enquiry= obj.enquiry;  
        var pro_name = pro_dtls.product_name;
        var barcode = pro_dtls.barcode;
        var uname = enquiry.user_name;
        var umail = enquiry.user_email;
        var umob = enquiry.user_mobile;
        var certified = search.certified;
        var clarity = search.clarity;
        var shape = search.shape;
        var subCatName = obj.categories.cat_name;
        var categoryid = obj.categories.mCatid;
        var pcatid     = obj.categories.pcatid;
        
        if(pro_name == null) {
            pro_name = '';
        }

        if(barcode == null || barcode == '' || barcode == 'null') {
            barcode = 'N-A';
        }
        if(uname == undefined || uname == 'undefined' || uname == null || uname == '' || uname == 'null' || uname === '0') {
            uname = 'N/A';
        }
        if(uname == undefined || uname == 'undefined' || umail == null || umail == '' || umail === '0' ) {
            umail = 'N/A';
        }
        if(umob == undefined || umob == 'undefined' || umob == null || umob == '' || umob === '0' || umob === 0 ) {
            umob = 'N/A';
        }
        if(certified == undefined || certified == 'undefined' || certified == null || certified == '' || certified === '0' || certified === 0 ) {
            certified = 'N/A';
        }
        if(shape == undefined || shape == 'undefined' || shape == null || shape == '' || shape === '0' || shape === 0 ) {
            shape = 'N/A';
        }
        if(clarity == undefined || clarity == 'undefined' || clarity == null || clarity == '' || clarity === '0' || clarity === 0 ) {
            clarity = 'N/A';
        }
        
        var str='';
        str += '<li>';
        str += '<div class="date fLeft"> ';
        str += '<span class="upSpan">' + date[0] + '</span>';
        str += '<span class="lwSpan">' + date[1] + '</span>';
        str += '</div>';
        str += '<div class="name fLeft">' + uname + '</div>';
        str += '<div class="email fLeft txtOverflow" title='+umail+'>'+ umail + '</div>';
        str += '<div class="type fLeft">'+ subCatName;
        
        //str += '<span class="lwSpan">' + obj.categories.cat_name + '</span>';
        str += '</div>';
        str += '<div class="barcode fLeft">';
        str += '<span class="upSpan">' + barcode + '</span>';
            if(pcatid == 10000)
                {
                 
                    var tempUrl = '';
                    if(search.shape !== null && search.shape !== undefined){
                        if(tempUrl !== ''){
                            tempUrl += '-'+search.shape; 
                        }
                        else{
                            tempUrl += search.shape;
                        }
                    }
                    if(search.color !== null && search.color !== undefined){
                        if(tempUrl !== ''){
                        tempUrl += '-colour-'+search.color; 
                        }
                        else{
                        tempUrl += 'colour-'+search.color;
                        }
                    }
                    if(search.clarity !== null && search.clarity !== undefined){
                        if(tempUrl !== ''){
                        tempUrl += '-clarity-'+search.clarity; 
                        }
                        else{
                        tempUrl += 'clarity-'+search.clarity;
                        }
                    }
                    if(search.certified !== null && search.certified !== undefined){
                        if(tempUrl !== ''){
                        tempUrl += '-certified-'+search.certified; 
                        }
                        else{
                        tempUrl += 'certified-'+search.certified;
                        }
                    }
                    if(tempUrl !== '')
                    {
                        str += '<span class="lwSpan"><a href="'+DOMAIN+ tempUrl+'/did-'+search.product_id+'" target="_blank">View Details</a></span>';
                    }
                    else
                    {
                        str += '<span class="lwSpan"><a href="'+ DOMAIN + search.shape +'/did-'+search.product_id+'" target="_blank">View Details</a></span>';
                    }
                }
                else if(pcatid == 10002)
                {
                    
                    var tempUrl = '';
                    if(search.metal !== null && search.metal !== undefined){
                        if(tempUrl !== ''){
                            tempUrl += '-'+search.metal; 
                        }
                        else{
                            tempUrl += search.metal;
                        }
                    }
                    if(search.type !== null && search.type !== undefined){
                        if(tempUrl !== ''){
                            tempUrl += '-'+search.type; 
                        }
                        else{
                            tempUrl += search.type;
                        }
                    }
                    if(search.gold_purity !== '' && search.gold_purity !== 'null' && search.gold_purity !== null && search.gold_purity !== undefined && search.gold_purity !== 'undefined'){
                        if(tempUrl !== ''){
                            var goldpty = search.gold_purity.split('.');
                            tempUrl += '-'+goldpty[0]+'-Karat'; 
                        }
                        else{
                            var goldpty = search.gold_purity.split('.');
                            tempUrl += goldpty[0]+'-Karat';
                        }
                    }
                    if(search.gold_weight !== null && search.gold_weight !== undefined && search.gold_weight !== ''){
                        if(tempUrl !== ''){
                            var goldwt = search.gold_weight.split('.');
                            goldwt = goldwt[0].split(',');
                            tempUrl += '-'+ goldwt+'-Grams'; 
                        }
                        else{
                            var goldwt = search.gold_weight.split('.');
                            goldwt = goldwt[0].split(',');
                            tempUrl += goldwt+'-Grams';
                        }
                    }
                    if(tempUrl !== '')
                    {
                        str += '<span class="lwSpan"><a href="'+DOMAIN+ tempUrl+'/bid-'+search.product_id+'" target="_blank">View Details</a></span>';
                    }
                    else
                    {
                        str += '<span class="lwSpan"><a href="'+ DOMAIN + search.type +'/bid-'+search.product_id+'" target="_blank">View Details</a></span>';
                    }
                }
                else if(pcatid == 10001)
                {
                    
                    var tempUrl = '';
                    if(search.metal !== null && search.metal !== undefined){
                        if(tempUrl !== ''){
                            tempUrl += '-'+search.metal; 
                        }
                        else{
                            tempUrl += search.metal;
                        }
                    }
                    if(subCatName !== null && subCatName !== undefined && subCatName !== '' && subCatName !== 'undefined'){
                        if(tempUrl !== ''){
                            tempUrl += '-'+encodeURIComponent(subCatName); 
                        }
                        else{
                            tempUrl += encodeURIComponent(subCatName);
                        }
                    }
                    if(search.gold_purity !== '' && search.gold_purity !== 'null' && search.gold_purity !== null && search.gold_purity !== undefined && search.gold_purity !== 'undefined'){
                        if(tempUrl !== ''){
                            tempUrl += '-'+search.gold_purity+'-Karat'; 
                        }
                        else{
                            tempUrl += search.gold_purity+'-Karat';
                        }
                    }
                    if(search.certified !== null && search.certified !== undefined && search.certified !== ''){
                        if(tempUrl !== ''){
                            tempUrl += '-certificate-'+search.certified; 
                        }
                        else{
                            tempUrl += 'certificate-'+search.certified;
                        }
                    }
                    if(tempUrl !== '')
                    {
                        str += '<span class="lwSpan"><a href="'+DOMAIN+ tempUrl+'/jid-'+search.product_id+'" target="_blank">View Details</a></span>';
                    }
                    else
                    {
                        str += '<span class="lwSpan"><a href="'+ DOMAIN + search.shape +'/jid-'+search.product_id+'" target="_blank">View Details</a></span>';
                    }
                }
        str += '</div>';
                str += '<div class="price fLeft bNone fmOpenB">&#8377;' + search.price + '</div>';
        str += '</li>';
        return str;
    }
    else
    {
     str = '';
     return str;
    }
        
}
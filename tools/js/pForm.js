function changeGemstoneType(obj,id)
{
	var gemType = $(obj).val();

	if(gemType !== undefined && gemType !== null && gemType !== '' && typeof gemType !== 'undefined')
	{
		if(gemType == 'other')
		{
			$('.otherGemstoneProp_'+id).removeClass('dn');
		}
		else
		{
			$('.otherGemstoneProp_'+id).addClass('dn');
		}
		$('.gemstoneProp_'+id).removeClass('dn');
                
                $('#addGemsType').remove();
                $('#gemsTypeCont').append('<div onclick="addGemsType()" id="addGemsType" class="submitBtn fmOpenR ripplelink poR fRight addMore">Add Gemstone Type</div><div style="clear: both;"></div>');
	}
	else
	{
		$('.gemstoneProp_'+id).addClass('dn');
		$('.otherGemstoneProp_'+id).addClass('dn');
                if(id!=1) {
                    $('#gemsTypeCont_'+id).parent().addClass('dn');
                }
                $('.gemstoneProp_'+id+' input').removeAttr('checked');
	}
        $('.gemstoneProp').addClass('dn');
        $('#gemsDiv select').each(function () {
            if($(this).val()!='') {
                $('.gemstoneProp').removeClass('dn');
            }
        });
}
var submiter = false;

function submitForm(formid)
{
    if(submiter !== true)
    {
        submiter = true;
        var optionValue=new Array();
        values= new Array();
        var shapearr = '';
        var attr = new Array();
            var reslt = new Array();
        if(typeof $('.shapeSelected').attr('id') !== 'undefined'){
            values[0]='shape|@|'+$('.shapeSelected').attr('id');
        }

        if(formid=='dAddForm'){
        y=$('#dAddForm').serializeArray();
        }
        /*else if(formid=='jwAddForm'){
            y=$('#jwAddForm').serializeArray();
        }*/
        else if(formid=='bAddForm'){
            y=$('#bAddForm').serializeArray();
        }
        $('input[type=checkbox]:checked').each(function(){
           optionValue.push(($(this).attr('value'))); 
        });

       // var i=1;
        $(y).each(function(i,val){
            if(val.value)
            {  
                if(val[(y[i].name)]!=='subcat_type')
                {
                   val[(y[i].name)]=(y[i].value);
                   values[i+1] = val.name+'|@|'+val.value;
                }
            }
        });
        var cnt = values.length;
        values[cnt+1]='subcatid'+'|@|'+optionValue.toString();

        data = values.join('|~|');
            //return false;

        if((pid !== null)&&(pid !== undefined)&&(pid !== '') && pid !== 'undefined' && typeof pid !== 'undefined')
        {
            var params = 'action=addNewproduct&category_id='+catid+'&dt='+data+'&prdid='+pid+'&vid='+uid;
        }
        else
        {
            var params = 'action=addNewproduct&category_id='+catid+'&dt='+data+'&vid='+uid;
        }
        var URL   = DOMAIN+"apis/index.php?";

            if(formid=='dAddForm')
            {
                    $.ajax({
                            url: URL + params,
                            type: 'POST',
                            //data: params,
                            async: false,
                            success: function (data) {
                                    if(data !== undefined && data !== null && data !== '' && typeof data !== 'undefined')
                                    {
                                            data = eval('(' + data + ')');
                                            var tmp_pid = data.results.pid;
                                            if(data.error !== undefined && data.error !== null && data.error !== '' && typeof data.error !== 'undefined')
                                            {
                                                    if(data.error.code !== undefined && data.error.code !== null && data.error.code !== '' && typeof data.error.code !== 'undefined')
                                                    {
                                                            if(data.error.code == 0 || data.error.code == '0')
                                                            {
                                                                    if(certificate_url === undefined || certificate_url === null || certificate_url === '')
                                                                    {
                                                                            var FD = new FormData();
                                                                            var certFile = $('#certfile')[0]['files'][0];

                                                                            FD.append('file', certFile);

                                                                            $.ajax({
                                                                                    url: APIDOMAIN + 'index.php?action=uploadCertificate&prdid='+tmp_pid,
                                                                                    data: FD,
                                                                                    processData: false,
                                                                                    contentType: false,
                                                                                    type: 'POST',
                                                                                    success: function(data){
                                                                                            if(data !== undefined && data !== null && data !== '' && typeof data !== 'undefined')
                                                                                            {
                                                                                                    data = eval('(' + data + ')');
                                                                                                    if(data.error !== undefined && data.error !== null && data.error !== '' && typeof data.error !== 'undefined')
                                                                                                    {
                                                                                                            if(data.error.code !== undefined && data.error.code !== null && data.error.code !== '' && typeof data.error.code !== 'undefined')
                                                                                                            {
                                                                                                                    if(data.error.code == 0 || data.error.code == '0')
                                                                                                                    {
                                                                                                                            window.location.href = IMGUPLOAD+'pid-'+tmp_pid+'&c='+catid;
                                                                                                                    }
                                                                                                                    else
                                                                                                                    {
                                                                                                                            common.toast(0, 'Error adding / updating certificate');
                                                                                                                            return false;
                                                                                                                    }
                                                                                                            }
                                                                                                            else
                                                                                                            {
                                                                                                                    common.toast(0, 'Error adding / updating certificate');
                                                                                                                    return false;
                                                                                                            }
                                                                                                    }
                                                                                                    else
                                                                                                    {
                                                                                                            common.toast(0, 'Error adding / updating certificate');
                                                                                                            return false;
                                                                                                    }
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                    common.toast(0, 'Error adding / updating certificate');
                                                                                                    return false;
                                                                                            }
                                                                                    }
                                                                            });
                                                                    }
                                                                    else
                                                                    {
                                                                            window.location.href = IMGUPLOAD+'pid-'+tmp_pid+'&c='+catid;
                                                                    }
                                                            }
                                                            else
                                                            {
                                                                    common.toast(0, 'Error adding / updating information');
                                                                    return false;
                                                            }
                                                    }
                                                    else
                                                    {
                                                            common.toast(0, 'Error adding / updating information');
                                                            return false;
                                                    }
                                            }
                                            else
                                            {
                                                    common.toast(0, 'Error adding / updating information');
                                                    return false;
                                            }
                                    }
                                    else
                                    {
                                            common.toast(0, 'Error adding / updating information');
                                            return false;
                                    }
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                    });
            }
            else
            {

                    $.getJSON(URL, params, function(data) {
                            window.location.href = IMGUPLOAD+'pid-'+data.results.pid+'&c='+catid;
                    });
            }
}
    else
    {
        submiter = false;
        common.toast(0, 'Submit button can be pressed only once');
    }
}
function validateJForm()
{
    if(submiter !== true)
    {
        submiter = true;
	var shape=$('.jshapeComm');
	var isPlain =  $('input[name=Plain]:checked').val();
	var certificate =  $('input[name=Certficate]:checked').val();
	var metal = $("input[name='metal']:checked").val();
	var diamondShape = $('.shapeComm');
	var color =  $('input[name=color]:checked').val();
	var combination =  $('input[name=combination]:checked').val();
	var clarity = $("input[name='clarity']:checked").val();
	var dweight = $('#diamondweight').val().trim();
	var no_diamonds=$('#no_diamonds').val().trim();
//	var gemstone_type=$('#gemstone_type').val();
//	var gemcolour = $("input[name='gemstone_color']:checked").val();
	var gemweight=$('#gemweight').val().trim();
	var num_gemstones = $('#num_gemstones').val().trim();
	var purity = $('#goldpurity').val().trim();
	var goldweight=$('#goldweight').val().trim();
	var barcode=$('#barcode').val().trim();
	var prdprice=$('#prdprice').val().trim();
	var othercert=$('#other_cerificate').val().trim();
	var other_gem_type = $('#other_gem_type').val().trim();
        var puritypatt = /(^|[^-\d])(14|18|22|24)\b/;
	var subcat = '';
	var isValid = true;

	if(gemstone_type !== undefined && gemstone_type !== null && gemstone_type !== '' && typeof gemstone_type !== 'undefined' && gemstone_type === 'other')
	{
		gemstone_type = other_gem_type;
	}

	$("input[name='subcat_type']:checked").each(function() {
		if(subcat !== '')
		{
			subcat += "," + $(this).val();
		}
		else
		{
			subcat = $(this).val();
		}
	})
	// Validations Start

	if(!shape.hasClass('shapeSelected'))
	{
		str ='Please select category';
                submiter = false;
		isValid = false;
	}

	if(isValid && subcat == '')
	{
		str ='Please select sub category';
                submiter = false;
                isValid = false;
	}

	if(isValid && (certificate === undefined || certificate === null || certificate === ''))
	{
		str ='Please select certificate';
                submiter = false;
                isValid = false;
	}
	else if (isValid)
	{
		if(isValid && certificate.toLowerCase() === 'other')
		{
			if(isValid && (othercert === undefined || othercert === null || othercert === ''))
			{
				isValid = false;
                                submiter = false;
                                str = 'Please enter certificate';
			}
		}
	}

	if(isValid && (metal === undefined || metal === null || metal === ''))
	{
		str = 'Please select metal type';
                submiter = false;
                isValid = false;
	}
	if(isValid && (combination === undefined || combination === null || combination === ''))
	{
		str = 'Please select combination';
                submiter = false;
                isValid = false;
	}

        var vclarity=new Array();
        var vcolor=new Array();
	if(isValid && diamondShape.hasClass('shapeSelected'))
	{
                var DivLen = ($('#diamondShapeCont #diamondShapeDiv').length)+1;
                for(var i=1; i<DivLen; i++) {
                    if($('#diamondShapeCont_'+i+' .shapeComm').hasClass('shapeSelected')) {
                        var color =  $('input[name=color_'+i+']:checked').val();
                        var clarity = $('input[name=clarity_'+i+']:checked').val();
                        vcolor.push(color);
                        vclarity.push(clarity);
                        if(color === undefined || color === null || color === '')
                        {
                                str = 'Please select diamond color';
                                submiter = false;
                                isValid = false;
                        }

                        if(isValid && (clarity === undefined || clarity === null || clarity === ''))
                        {
                                str = 'Please enter diamond quality';
                                submiter = false;
                                isValid = false;
                        }
                    }
		}

		if(isValid && (dweight === undefined || dweight === null || dweight === ''))
		{
			str = 'Please enter diamond weight in carats';
			submiter = false;
                        isValid = false;
			$('#diamondwdiamondShapeCont #diamondShapeDiveight').focus();
		}

		if(isValid && (no_diamonds === undefined || no_diamonds === null || no_diamonds === ''))
		{
			str = 'Please select number of diamonds';
			submiter = false;
                        isValid = false;
			$('#no_diamonds').focus();
		}
	}
	else
	{
		diamondShape = color = clarity = dweight = no_diamonds = '';
	}

        var vgemstone_type=new Array();
        var vgemcolour=new Array();
        var DivLen = ($('#gemsTypeCont #gemsDiv').length)+1;
        for(var i=1; i<DivLen; i++) {
            
            var gemstone_type=$('#gemsTypeCont_'+i+' #gemstone_type').val();
            
            var other_gem_type = $('.otherGemstoneProp_'+i+' #other_gem_type').val().trim();
            
            if(gemstone_type !== undefined && gemstone_type !== null && gemstone_type !== '' && typeof gemstone_type !== 'undefined' && gemstone_type === 'other')
            {
                    gemstone_type = other_gem_type;
            }
            

            var gemcolour = $("input[name='gemstone_color_"+i+"']:checked").val();
            
            if(isValid && (gemstone_type !== undefined && gemstone_type !== null && gemstone_type !== ''))
            {
                    vgemstone_type.push(gemstone_type);
                    vgemcolour.push(gemcolour);
                    if(isValid && (gemcolour === undefined || gemcolour === null || gemcolour === ''))
                    {
                            str = 'Please select gemstone color';
                            submiter = false;
                            isValid = false;
                    }
            }
            else
            {
                    gemstone_type = gemcolour = gemweight = num_gemstones = '';
            }
	}
        
	if(isValid && (vgemstone_type.length!=0)) {
            
            var gemweight=$('#gemweight').val().trim();
            var num_gemstones = $('#num_gemstones').val().trim();
            if(isValid && (gemweight === undefined || gemweight === null || gemweight === ''))
            {
                    str = 'Please enter gemstone weight';
                    submiter = false;
                    isValid = false;
                    $('#gemweight').focus();
            }

            if(isValid && (num_gemstones === undefined || num_gemstones === null || num_gemstones === ''))
            {
                    str = 'Please enter number of gemstones';
                    submiter = false;
                    isValid = false;
                    $('#num_gemstones').focus();
            }
        }
        
	if(isValid && (purity === undefined || purity === null || purity === '' || isNaN(purity)))
	{
		str = 'Please enter purity';
		submiter = false;
                isValid = false;
		$('#goldpurity').focus();
	}
        
        if(isValid && !purity.match(puritypatt))
        {
            common.toast(0,'Please choose the purity among 14 / 18 / 22 / 24');
            submiter = false;
            isValid = false;
            $('#goldpurity').focus();
        }

	if(isValid && (goldweight === undefined || goldweight === null || goldweight === '' || isNaN(goldweight)))
	{
		str = 'Please enter weight';
		submiter = false;
                isValid = false;
		$('#goldweight').focus();
	}

	if(isValid && (barcode === undefined || barcode === null || barcode === ''))
	{
		str = 'Please enter design number';
		submiter = false;
                isValid = false;
		$('#barcode').focus();
	}

	if(isValid && (prdprice === undefined || prdprice === null || prdprice === '' || isNaN(prdprice)))
	{
		str = 'Please enter product price';
		submiter = false;
                isValid = false;
		$('#prdprice').focus();
	}

	// Validations End

	if(isValid === false)
	{
		common.toast(0, str);
		return false;
	}
	else
	{
		// Submit form
		var URL = DOMAIN+"/apis/index.php?action=addNewproduct";
		var params
		if((pid !== null)&&(pid !== undefined)&&(pid !== '') && pid !== 'undefined' && typeof pid !== 'undefined')
		{
			var params = 'category_id='+catid+'&prdid='+pid+'&vid='+uid;
		}
		else
		{
			var params = 'category_id='+catid+'&vid='+uid;
		}

		var dt = '';

		var shapeVal = '';
		
		shape.each(function() {
			if($(this).hasClass('shapeSelected'))
			{
				shapeVal = $(this).attr('id');
			}
		});

		var diamondShapeVal = new Array();

		if(diamondShape !== undefined && diamondShape !== null && diamondShape !== '' && diamondShape.hasClass('shapeSelected'))
		{
                    var i=0;
			diamondShape.each(function() {
				if($(this).hasClass('shapeSelected'))
				{   
                                    var diaVal=$(this).attr('id').split('_');
					diamondShapeVal[i] = diaVal[0];
                                        i++;
				}
			});
		}
                diamondShapeVal=diamondShapeVal.join('|!|');
                vcolor=vcolor.join('|!|');
                vclarity=vclarity.join('|!|');
                vgemstone_type=vgemstone_type.join('|!|');
                vgemcolour=vgemcolour.join('|!|');
		var values = new Array();
		values[0] = "shape|@|"+encodeURIComponent(shapeVal);
		values[1] = "subcatid|@|"+encodeURIComponent(subcat);
		values[2] = "Certficate|@|"+encodeURIComponent(certificate);
		values[3] = "metal|@|"+encodeURIComponent(metal);
		values[4] = "diamondShape|@|"+encodeURIComponent(diamondShapeVal);
		values[5] = "color|@|"+encodeURIComponent(vcolor);
		values[6] = "clarity|@|"+encodeURIComponent(vclarity);
		values[7] = "diamonds_weight|@|"+encodeURIComponent(dweight);
		values[8] = "no_diamonds|@|"+encodeURIComponent(no_diamonds);
		values[9] = "gemstone_type|@|"+encodeURIComponent(vgemstone_type);
		values[10] = "gemstone_color|@|"+encodeURIComponent(vgemcolour);
		values[11] = "gemstone_weight|@|"+encodeURIComponent(gemweight);
		values[12] = "num_gemstones|@|"+encodeURIComponent(num_gemstones);
		values[13] = "gold_purity|@|"+encodeURIComponent(purity);
		values[14] = "gold_weight|@|"+encodeURIComponent(goldweight);
		values[15] = "barcode|@|"+encodeURIComponent(barcode);
		values[16] = "price|@|"+encodeURIComponent(prdprice);
		values[17] = "isPlain|@|"+encodeURIComponent(isPlain);
		values[18] = "combination|@|"+encodeURIComponent(combination);
		dt = values.join('|~|');

		params += "&dt="+dt;

		$.getJSON(URL, params, function(data) {
			if(data !== undefined && data !== null && data !== '' && typeof data !== 'undefined')
			{
				if(data.error !== undefined && data.error !== null && data.error !== '' && typeof data.error !== 'undefined')
				{
					if(data.error.code !== undefined && data.error.code !== null && data.error.code !== '' && typeof data.error.code !== 'undefined' && data.error.code == 0)
					{
						submiter = false;
                                                window.location.href = IMGUPLOAD+'pid-'+data.results.pid+'&c='+catid;
					}
					else
					{
						common.toast(0, 'Error adding / updating information');
                                                submiter = false;
					}
				}
				else
				{
					common.toast(0, 'Error adding / updating information');
                                        submiter = false;
				}
			}
			else
			{
				common.toast(0, 'Error adding / updating information');
                                submiter = false;
			}
		});
	}
    }
    else
    {
        submiter = false;
        common.toast(0, 'Submit button can be pressed only once');
    }
}


function calculatePrice()
{
   var baseprice=$('#baseprice').val().trim();
   var discount=$('#discount').val().trim();
   
   
   if(discount == null || discount == '' || discount == 'undefined' || discount == undefined){ 
   
        $('#prdprice').val(baseprice);
   }
   else 
   {
        if((isNaN(baseprice)) == false  || (isNaN(baseprice)) == false || baseprice == undefined  || baseprice == 'undefined' || discount == undefiend || discount == 'undefined')
        {
           var offerPrice =(parseFloat(baseprice)*(discount/100));
           var total = (baseprice-offerPrice).toFixed(0);
           if(total <= 0)
           {
               common.toast(0,'Price can not be in negative value');
               $('#prdprice').val('');
               $('#discount').val('');
           }
           else
           {
                $('#prdprice').val(total);
            }
        }
        
   }    
}

function calculateB2BPrice()
{
   var baseprice=$('#baseprice').val().trim();
   var discount=$('#discountb2b').val().trim();
   
   
   if(discount == null || discount == '' || discount == 'undefined' || discount == undefined){ 
   
        $('#prdb2bprice').val(baseprice);
   }
   else 
   {
        if((isNaN(baseprice)) == false  || (isNaN(baseprice)) == false || baseprice == undefined  || baseprice == 'undefined' || discount == undefiend || discount == 'undefined')
        {
           var offerPrice =(parseFloat(baseprice)*(discount/100));
           var total = (baseprice-offerPrice).toFixed(0);
           if(total <= 0)
           {
               common.toast(0,'B2B Price can not be in negative value');
               $('#prdb2bprice').val('');
               $('#discountb2b').val('');
           }
           else
           {
                $('#prdb2bprice').val(total);
            }
        }
        
   }    
}


function validateNum(){
               var regex = /^\\d{2}(\\.\\d)?$/;
               var cert = $('#cert').val().trim();
                if(cert.match(regex) == null || cert == undefined) {
                    common.toast(0,'valid');
                } else {
                    common.toast(0,'valid');
                }
            }
 function validateDForm(){
            var shape=$('.shapeComm');
            var metal = $("input[name='metal']:checked").val();
            var color = $("input[name='color']:checked").val();
            var cut = $("input[name='cut']:checked").val();
            var symmetry = $("input[name='symmetry']:checked").val();
            var polish = $("input[name='polish']:checked").val();
            var flourecence = $("input[name='flourecence']:checked").val();
            var certificate = $("input[name='Certficate']:checked").val();
            var clarity = $("input[name='clarity']:checked").val();
            var certno = $('#certno').val().trim();
            var carat = $('#caratweight').val().trim();
            var measure1 = $('#measure1').val().trim();
            var measure2 = $('#measure2').val().trim();
            var measure3 = $('#measure3').val().trim();
            var table = $('#table').val().trim();
            var crown = $('#crownangle').val().trim();
            var girdle = $('#girdle').val().trim();
            var barcode=$('#barcode').val().trim();
            var lotnumber=$('#lotnumber').val().trim();
            var lotreference=$('#lotreference').val().trim();
            var baseprice=$('#baseprice').val().trim();
            var discount=$('#discount').val().trim();
            var discountb2b=$('#discountb2b').val().trim();
            var prdprice=$('#prdprice').val().trim();
            var str = '';
            carat = parseFloat(carat);
            crown = parseFloat(crown);
			var isValid = true;

            if(isValid && !shape.hasClass('shapeSelected')) {
                str ='Shape is not Selected';
				isValid = fale;
            }

			if(isValid && ((color=='')||(color==null)||(color==undefined))) {
                str ='Color field is Empty';
				isValid = false;
            }

            if(isValid && ((clarity=='')||(clarity==null)||(clarity==undefined))) {
                str ='Clarity field is Empty';
				isValid = false;
            }

			if(isValid && ((cut=='')||(cut==null)||(cut==undefined))) {
                str ='Cut field is Empty';
				isValid = false;
            }

			if(isValid && ((symmetry=='')||(symmetry==null)||(symmetry==undefined))) {
                str ='Symmetry field is Empty';
				isValid = false;
            }

			if(isValid && ((polish=='')||(polish==null)||(polish==undefined))) {
                str ='Polish field is Empty';
				isValid = false;
            }

			if(isValid && ((flourecence=='')||(flourecence==null)||(flourecence==undefined))) {
                str ='Flourecence field is Empty';
				isValid = false;
            }

			if(isValid && ((certificate=='')||(certificate==null)||(certificate==undefined))) {
                str ='Certificate field is Empty';
				isValid = false;
            }

			if(isValid && (certno=='')) {
                str ='Certificate Number field is Empty';
				isValid = false;
            }

			if(isValid && (certificate_url === undefined || certificate_url === null || certificate_url === ''))
			{
				var tmp_certificate_url = $('#filePath').html();
				if(isValid && (tmp_certificate_url === undefined || tmp_certificate_url === null || tmp_certificate_url === ''))
				{
					str ='Please upload product certificate';
					isValid = false;
				}
			}

            if(isValid && (carat == '' || isNaN(carat))) {
                str ='Carat field is Empty';
                 $('#caratweight').focus();
				 isValid = false;
            }

			if(isValid && (measure1 == '' || isNaN(measure1))) {
                str ='Measurement column1 is Required';
                $('#measure1').focus();
				isValid = false;
            }

			if(isValid && (measure2 == '' || isNaN(measure2))) {
                str ='Measurement column2 is Required';
                $('#measure2').focus();
				isValid = false;
            }

			if(isValid && (measure3 == '' || isNaN(measure3))) {
                str ='Measurement column3 is Required';
                $('#measure3').focus();
				isValid = false;
            }

			if(isValid && (table == '' || isNaN(table))) {
                str ='Table field is Required';
                $('#table').focus();
				isValid = false;
            }

			if(isValid && (crown == '' || isNaN(crown))) {
                str ='Crown field is Required';
                $('#crownangle').focus();
				isValid = false;
            }

			if(isValid && (girdle == '' || isNaN(girdle))) {
                str ='Girdle field is Required';
                $('#girdle').focus();
				isValid = false;
            }

			if(isValid && barcode=='') {
                str ='Barcode field is Required';
                $('#barcode').focus();
				isValid = false;
            }

			if(isValid && (lotnumber == '' || isNaN(lotnumber))) {
				str ='Lot number field is Required';
				$('#lotnumber').focus();
				isValid = false;
            }

			if(isValid && lotreference=='') {
				str ='Lot reference field is Required';
                $('#lotreference').focus();
				isValid = false;
            }

			if(isValid && (baseprice == '' || isNaN(baseprice))) {
				str ='Baseprice field is Required';
                $('#baseprice').focus();
				isValid = false;
            }
            if(isValid && (discountb2b == '' || isNaN(discountb2b)))
            {
                str ='B2B Discount field is not in expected form';
                $('#discountb2b').focus();
                isValid = false;
            }
            if(isValid && (discountb2b < discount))
            {
                str ='% Back To Vendor cannot be less than discount being provided to % Back To Customer';
                $('#discountb2b').focus();
                isValid = false;
            }

            if(isValid && (prdprice == '' || isNaN(prdprice)))
            {
                str ='Price field is Required';
                $('#prdprice').focus();
                isValid = false;
            }

            if(isValid == false && str != '')
            {
                common.toast(0,str);
            }
            else
			{
                submitForm('dAddForm');
                return  true;
            }
            return false;
}

 function validateBForm(){
            var design =  $('input[name=design]:checked').val();
            var purity = $('#goldpurity').val().trim();
            var barcode=$('#barcode').val().trim();
            var spurity = $('#silverpurity').val().trim();
            var goldweight = $('#goldweight').val().trim();
            var sweight= $('#silverweight').val().trim();
            var shape = $('.jshapeComm');
            var a = $('.jshapeComm.shapeSelected').attr('id');
            var str = '';
            var patt1 = /(^|[^-\d])(1|2|5|10|20|50|100|200|500|1000)\b/;
            var patt2 = /(^|[^-\d])(999|995)\b/;
            var otherdesign = $('#otherdesign').val().trim();
            var isValid = true;
            if(!shape.hasClass('shapeSelected')) {
                str ='category is not Selected';
                isValid = false;
            }
            if(isValid && (design=='' || design==null || design==undefined)) {
                str ='Design field is Empty';
                isValid = false;
            }
            if(isValid && design === 'Other')
            {
                if(isValid && (otherdesign == undefined || otherdesign == 'undefined' || otherdesign == null || otherdesign == 'null' || otherdesign == '' )){
                    str ='Please mention the Bullion Design in other field';
                    isValid = false;
                    $('#otherdesign').focus();
                }
            }
//            else if(isValid && (barcode !== '' && barcode == undefined && barcode == null && barcode == 'null' && typeof(barcode) == undefined )) {
//                str ='Design Number field is Required';
//                var isValid = false;
//            }
            if(a === 'gCoins' || a === 'gBars')
            {
                if(isValid && (purity == '' || isNaN(purity) || !purity.match(patt2))){
                    str ='Gold Purity field is Invalid';
                    isValid = false;
                }
                else if(isValid &&(goldweight == undefined || goldweight == '' || /*!goldweight.match(patt1) ||*/ isNaN(purity) || goldweight == null)) {
                    str ='Gold weight field is Invalid';
                    isValid = false;
                }
            }
            if(a=='sBars' || a=='sCoins')
            {
                if(isValid && (spurity == '' || isNaN(spurity) || !spurity.match(patt2))) {
                    str ='Silver Purity field is Invalid';
                    isValid = false;
                }
                else if(isValid && (sweight == '' || isNaN(sweight) || sweight == null || sweight == undefined)) {
                    str ='Silver Weight field is invalid';
                    isValid = false;
                }
            }
            if(str != '')
            {
                common.toast(0,str);
            }
            if(isValid == true)
            {
                submitForm('bAddForm');
                return  true;
            }
            return false;
}
function backbtn()
{
	var qryParams = window.location.href.split('&');
	var catidVal = httpUrl = '';
	for(var i = 0; i < qryParams.length; i++)
	{
		if(qryParams[i].indexOf('catid') !== -1)
		{
			catidVal = qryParams[i].split('=');
			if(catidVal[1] !== undefined && catidVal[1] !== null && catidVal[1] !== '' && typeof catidVal[1] !== 'undefined')
			{
				catidVal = catidVal[1];
				httpUrl = DOMAIN + 'index.php?case=vendor_landing&catid='+catidVal;
				break;
			}
		}
	}
	window.location.href = httpUrl;
	/*if(history.length <= 0)
	{
		window.history.back();
		return true;
	}
	else if(history.length || history.length == 'undefiend' || history.length == undefiend || history.length == null || history.length == 'null' || history.length >= 0)
	{
		window.close();
		window.location.reload();
	}*/
}
function showJewelleryImps(tmpId) {
    
                $('.subCatType').addClass('dn');
                $('#' + tmpId + 'Type').removeClass('dn');
                $('#allcontent').removeClass('dn');
            if ((tmpId == 'gbars') || (tmpId == 'gcoins'))
            {
                $('.allprop').addClass('dn');
                $('.goldprop').removeClass('dn');
                $('#silverpurity').val('');
                $('#silverweight').val('');
                if (tmpId == 'gbars') {
                    $('#goldweight').attr('placeholder','eg. Kgs Or Gms');
                } else {
                    $('#goldweight').attr('placeholder','eg. Gms');
                }
            }
            else if ((tmpId == 'sbars') || (tmpId == 'scoins'))
            {
                $('.allprop').addClass('dn');
                $('.silverprop').removeClass('dn');
                $('#goldpurity').val('');
                $('#goldweight').val('');
                if (tmpId == 'sbars') {
                    $('#silverweight').attr('placeholder','eg. Kgs Or Gms');
                } else {
                    $('#silverweight').attr('placeholder','eg. Gms');
                }
            }
}

function ValidateFile() {
    var allowedFiles = ["pdf","png","jpeg"];
    var fileUpload = document.getElementById("certfile").value;
    var fileExt = fileUpload.split('.').pop();
    if (allowedFiles.indexOf(fileExt)!=-1) {
        return true;
    }
    return false;
}

// Multiple Diamond shape

function addShapeType() {
    var DivLen = ($('#diamondShapeCont #diamondShapeDiv').length)+1;
    var str ='<div id="diamondShapeDiv"><div id="diamondShapeCont_'+DivLen+'" class="divCon fLeft dAuto" style="margin-top:0px;">';
        str +='<div class="shapesCont">';
            str +='<div class="wrapperMax">';
                str +='<div class="allShapes">';
                        str +='<center>';
                        var shapeTypesArr=JSON.parse(shapeTypes);
                        for(var i=0; i<shapeTypesArr.length; i++) {
                            str +='<div class="shapeComm transition300 ripplelink '+shapeTypesArr[i]+' " id="'+shapeTypesArr[i]+'_'+DivLen+'" onclick="checkDiamondShape(this,'+DivLen+');"> </div>';
                        }
                        str +='</center></div></div></div></div>';

                str +='<div class="divCon fLeft dAuto diamondProp dn diamondProp_'+DivLen+'"><div class="titleDiv txtCap fLeft">Diamond Color *</div>';
                str +='<div class="radioCont fLeft"> ';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="D" id="color_D_'+DivLen+'" type="radio"><label for="color_D_'+DivLen+'"> D</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="E" id="color_E_'+DivLen+'" type="radio"><label for="color_E_'+DivLen+'">E</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="F" id="color_F_'+DivLen+'" type="radio"><label for="color_F_'+DivLen+'">F</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="G" id="color_G_'+DivLen+'" type="radio"><label for="color_G_'+DivLen+'">G</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="H" id="color_H_'+DivLen+'" type="radio"><label for="color_H_'+DivLen+'">H</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="I" id="color_I_'+DivLen+'" type="radio"><label for="color_I_'+DivLen+'">I</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="J" id="color_J_'+DivLen+'" type="radio"><label for="color_J_'+DivLen+'">J</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="K" id="color_K_'+DivLen+'" type="radio"><label for="color_K_'+DivLen+'">K</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="L" id="color_L_'+DivLen+'" type="radio"><label for="color_L_'+DivLen+'">L</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="M" id="color_M_'+DivLen+'" type="radio"><label for="color_M_'+DivLen+'">M</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="N" id="color_N_'+DivLen+'" type="radio"><label for="color_N_'+DivLen+'">N</label></div>';
                    str +='<div class="checkDiv fLeft"><input name="color_'+DivLen+'" class="filled-in" value="O" id="color_O_'+DivLen+'" type="radio"><label for="color_O_'+DivLen+'">O</label></div>';
            str +='</div></div>';
            str +='<div class="divCon  fLeft jw3 diamondProp dn diamondProp_'+DivLen+'">';
                str +='<div class="titleDiv txtCap fLeft">Diamond Quality *</div>';
                str +='<div class="radioCont fLeft"> ';
                    str +='<div class="checkDiv fLeft"><input type="radio" id="clarity_IF_'+DivLen+'" value="IF" class="filled-in" name="clarity_'+DivLen+'"><label for="clarity_IF_'+DivLen+'">IF</label></div>';
                    str +='<div class="checkDiv fLeft"><input type="radio" id="clarity_VVS1_'+DivLen+'" value="VVS1" class="filled-in" name="clarity_'+DivLen+'"><label for="clarity_VVS1_'+DivLen+'">VVS1</label></div>';
                    str +='<div class="checkDiv fLeft"><input type="radio" id="clarity_VVS2_'+DivLen+'" value="VVS2" class="filled-in" name="clarity_'+DivLen+'"><label for="clarity_VVS2_'+DivLen+'">VVS2</label></div>';
                    str +='<div class="checkDiv fLeft"><input type="radio" id="clarity_VS1_'+DivLen+'" value="VS1" class="filled-in" name="clarity_'+DivLen+'"><label for="clarity_VS1_'+DivLen+'">VS1</label></div>';
                    str +='<div class="checkDiv fLeft"><input type="radio" id="clarity_VS2_'+DivLen+'" value="VS2" class="filled-in" name="clarity_'+DivLen+'"><label for="clarity_VS2_'+DivLen+'">VS2</label></div>';
                    str +='<div class="checkDiv fLeft"><input type="radio" id="clarity_SI1_'+DivLen+'" value="SI1" class="filled-in" name="clarity_'+DivLen+'"><label for="clarity_SI1_'+DivLen+'">SI1</label></div>';
                    str +='<div class="checkDiv fLeft"><input type="radio" id="clarity_SI2_'+DivLen+'" value="SI2" class="filled-in" name="clarity_'+DivLen+'"><label for="clarity_SI2_'+DivLen+'">SI2</label></div>';
                    str +='<div class="checkDiv fLeft"><input type="radio" id="clarity_I1_'+DivLen+'" value="I1" class="filled-in" name="clarity_'+DivLen+'"><label for="clarity_I1_'+DivLen+'">I1</label></div>';
                str +='</div></div></div>';
    $('#diamondShapeCont').append(str);
    $('#addDiamondType').remove();
}
function checkDiamondShape(evt,id) {

    if($(evt).hasClass('shapeSelected'))
    {
        $(evt).toggleClass('shapeSelected');
        if(id!=1) {
            $('#diamondShapeCont_'+id).parent().addClass('dn');
        }
        $('.diamondProp_'+id).addClass('dn');
        $('.diamondProp_'+id+' input').removeAttr('checked');
        
        
        if(!$(evt).hasClass('shapeSelected') && id==1)
        {
            $('#addDiamondType').remove();
        }
    } else {
        $(evt).removeClass('shapeSelected');
        var uthis = $(evt);
        var wholeDiv='#diamondShapeCont_'+id+' .shapeComm';
        $(wholeDiv).each(function () {
            if ($(wholeDiv).hasClass('shapeSelected') && $(wholeDiv).attr('id') != uthis.attr('id'))
                $(wholeDiv).removeClass('shapeSelected');
        });
        $(evt).toggleClass('shapeSelected');
        if($(evt).hasClass('shapeSelected'))
        {
            $('#addDiamondType').remove();
            $('#diamondShapeCont').append('<div onclick="addShapeType()" id="addDiamondType" class="submitBtn fmOpenR ripplelink poR fRight addMore">Add Diamond Type</div><div style="clear: both;"></div>');
            $('.diamondProp_'+id).removeClass('dn');
        }
        else
        {
            $('.diamondProp_'+id).addClass('dn');
        }
    }

    $('.divCon2.diamondProp').addClass('dn');
    $('.shapeComm').each(function () {
        if ($(this).hasClass('shapeSelected')) {
            $('.divCon2.diamondProp').removeClass('dn');
        }
    });
}


function addGemsType() {
    var DivLen = ($('#gemsTypeCont #gemsDiv').length)+1;
    var str ='<div id="gemsDiv"><div id="gemsTypeCont_'+DivLen+'"><div class="divCon fLeft">';
        str +='<div class="titleDiv txtCap fLeft">Gemstone Type</div>';
        str +='<select class="txtInput fLeft fmOpenR font14 c666" onchange="changeGemstoneType(this,'+DivLen+');" id="gemstone_type">';
        str +='<option value="">Select Gemstone Type</option>';
        var gemsTypesArr=JSON.parse(gemsTypes);
        for(var i=0; i<gemsTypesArr.length; i++) {
            str +='<option value="'+gemsTypesArr[i].c+'">'+gemsTypesArr[i].n+'</option>';
        }
        str +='<option value="other">Others</option>';
        str +='</select>';
    str +='</div>';

    str +='<div class="divCon fLeft dn otherGemstoneProp_'+DivLen+'">';
    str +='<div class="titleDiv txtCap fLeft">Other Gemstone Type</div>';
    str +='<input type="text" class="txtInput fLeft fmOpenR font14 c666" value="" placeholder=" eg. ABC" autocomplete="false" id="other_gem_type" name="other_gem_type"></div>';

    str +='<div class="divCon fLeft jw3 dn gemstoneProp_'+DivLen+'">';
    str +='<div class="titleDiv txtCap fLeft">Gemstone Colour *</div>';
    str +='<div class="radioCont fLeft">';

        var gemsValuesArr=JSON.parse(gemsValues);
        for(var i=0; i<gemsValuesArr.length; i++) {
            str +='<div class="checkDiv fLeft"><input type="radio" id="'+gemsValuesArr[i]+'_'+DivLen+'" value="'+gemsValuesArr[i]+'" class="filled-in" name="gemstone_color_'+DivLen+'"><label for="'+gemsValuesArr[i]+'_'+DivLen+'" class=" txtCap">'+gemsValuesArr[i]+'</label></div>';
        }

    str +='</div></div></div>';
    $('#addGemsType').remove();
    $('#gemsTypeCont').append(str);
}

$(document).ready(function()
{
    if($('input[name=Plain]:checked').val() == 'Plain')
    {
        checkIfGold();
    }
    else if($('input[name=Plain]:checked').val() == 'Mix')
    {
        checkIfNotGold();
    }
});

function checkIfGold()
{
    if($('input[name=Plain]:checked').val() == 'Plain')
    {
        $("input[name='Certficate']").attr('disabled',true);
        $('#Certficate_BIS').attr('disabled',false).prop('checked', true);
        $('#Pendants_10026').attr('disabled', true).attr('checked', false);
        $('#Bangles_10034').attr('disabled', true).attr('checked', false);
        $('#Earrings_10020').attr('disabled', true).attr('checked', false);
        $('#Rings_10012').attr('disabled', true).attr('checked', false);
        
        $("input[name='metal']").attr('disabled',true).attr('checked',false);
        $("input[name='combination']").prop('disabled',true).attr('checked',false);
        $('#plain_gold').prop('disabled', false).prop('checked',true);
        $('#Certficate_other').attr('disabled',false);
        $('#gold').attr('disabled', false).prop('checked',true);
        $('.dShapeTitle').addClass('dn');
        $('#diamondShapeCont').addClass('dn');
    }
}

function checkIfNotGold()
{
    $("input[name='Certficate']").attr('disabled',false);
    
    $('#Pendants_10026').attr('disabled', false);
    $('#Bangles_10034').attr('disabled', false);
    $('#Earrings_10020').attr('disabled', false);
    $('#Rings_10012').attr('disabled', false);
    
    $("input[name='metal']").prop('disabled',false);
    $("input[name='combination']").prop('disabled',false);
    $('.dShapeTitle').removeClass('dn');
    $('#diamondShapeCont').removeClass('dn');
    $('#prdprice').prop('readonly',false);
}


function isPlainJewellery()
{
        if($('input[name=Plain]:checked').val() == 'Plain')
        {
            $("input[name='Certficate']").prop('disabled',true);
            $('#Certficate_BIS').prop('disabled',false).prop('checked', true);

            $('#Pendants_10026').attr('disabled', true).attr('checked', false);
            $('#Bangles_10034').attr('disabled', true).attr('checked', false);;
            $('#Earrings_10020').attr('disabled', true).attr('checked', false);;
            $('#Rings_10012').attr('disabled', true).attr('checked', false);;

            $("input[name='metal']").prop('disabled',true).attr('checked',false);

            $("input[name='combination']").prop('disabled',true).attr('checked',true);
            $('#plain_gold').prop('disabled', false).prop('checked',true);

            $('#Certficate_other').attr('disabled',false);
            $('#gold').prop('disabled', false).prop('checked',true);
            $('.dShapeTitle').addClass('dn');
            $('.diamondProp').addClass('dn');
            $('#diamondShapeCont').addClass('dn');

            $('#prdprice').attr('readonly',true);
        }
        else
        {
            $("input[name='Certficate']").attr('disabled',false);

            $('#Pendants_10026').attr('disabled', false);
            $('#Bangles_10034').attr('disabled', false);
            $('#Earrings_10020').attr('disabled', false);
            $('#Rings_10012').attr('disabled', false);

            $("input[name='metal']").prop('disabled',false);
            $('#gold').prop('checked',false);

            $("input[name='combination']").prop('disabled',false);
            $('#plain_gold').prop('checked',false);

            $('#Certficate_BIS').attr('checked', false);
            $('.dShapeTitle').removeClass('dn');
            $('.diamondProp').addClass('dn');
            $('#diamondShapeCont').removeClass('dn');

            $('#prdprice').attr('readonly',false);
        }
}
function calculateJPrice()
{
    if($('input[name=Plain]:checked').val() == 'Plain')
    {   $.ajax({url: APIDOMAIN + 'index.php?action=getGoldRate&vid='+uid,success: function(data)
            {
                var obj = eval('('+data+')');
                var metalrt = obj.results.gold_rate;
                var gwt = $('#goldweight').val();
                var pty = $('#goldpurity').val();
                var puritypatt = /(^|[^-\d])(14|18|22|24)\b/;
                if(!pty.match(puritypatt))
                {
                    common.toast(0,'Please choose the purity among 14 / 18 / 22 / 24');
                    $('#goldpurity').focus();
                }
                pty = parseInt(common.caratCheck(pty));
                var prce = $('#prdprice').val();
                if(isNaN(gwt) == false && gwt !== undefined && gwt !== '' && gwt !== null && gwt !== 'null' && isNaN(pty) == false && pty !== undefined && pty !== 'undefined' && pty !== null && pty !== 'null' && pty !== '')
                {
                    gwt = parseInt(gwt);
                    metalrt = parseInt(metalrt);

                    var price = (metalrt/10)*(pty/995)*gwt;
                    price = Math.ceil(price);
                    $('#prdprice').val(price);
                }
                else if(isNaN(prce) == true && prce == 'NaN' && prce == NaN)
                {
                    $('#prdprice').val('');
                }
            }
        });

    }
}
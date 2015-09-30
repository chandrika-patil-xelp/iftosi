var cladata = '';
var coldata = '';

function getData(cse, cfrm, cto, pfrm, pto, ccla, ccol)
{
    if(cfrm == undefined || cfrm == null)
    {
        cfrm = min_carats;
    }
    if(cto == undefined || cto == null)
    {
        cto = max_carats;
    }

    if(pfrm == undefined || pfrm == null)
    {
        pfrm = min_price;
    }

    if(pto == undefined || pto == null)
    {
        pto = max_price;
    }

    if(ccla == undefined || ccla == null)
    {
        ccla = '';
    }

    if(ccol == undefined || ccol == null)
    {
        ccol = '';
    }

    if(cse == undefined || cse == null)
    {
        cse = 'price';
    }
    
    

    $.ajax({
        url:"index.php",
        type: "get",
        data : {
            case:cse,
            cfrm: cfrm,
            cto: cto,
            pfrm: pfrm,
            pto: pto,
            ccla: ccla,
            ccol: ccol
        }, 
        success:function(result){
            if(result)
            {
                result = eval('(' + result + ')');
                if(result.error != 1)
                {
                    if(cse == 'price')
                    {
                        var slider = $("#carats").data("ionRangeSlider");
						// Call sliders update method with any params
						slider.update({
							min: result.min_carats,
							max: result.max_carats
						});
                    }
                    showHtml(result);
                }
            }
        }
    });
}

function showHtml(res)
{
    var data = '';var html_val  = '';
    if(res)
    {
        data = res.results;
		for(var i = 0; i < data.length; i++)
        {
			html_val += '<div class="temp fLeft txtCenter fmsansr font14 c666" style="transform: translateX(150%);opacity:0">';
			html_val += '<div class="dtData fLeft txtLeft mShapeCont">';
			html_val += '<div class="fLeft resShape '+data[i].cut+'"></div>';
			html_val += '<span class="showHide">'+data[i].cut+'</span>';
			html_val += '</div>';
			html_val += '<div class="dtData fLeft"><span class="hideShow  mTitle fLeft">Carats:&nbsp;</span>'+data[i].carats+'</div>';
			html_val += '<div class="dtData fLeft"><span class="hideShow  mTitle fLeft">Colour:&nbsp;</span>'+data[i].col+'</div>';
			html_val += '<div class="dtData fLeft"><span class="hideShow  mTitle fLeft">Clarity:&nbsp;</span>'+data[i].cla+'</div>';
			html_val += '<div class="dtData fLeft"><span class="hideShow  mTitle fLeft">Cut:&nbsp;</span>Excellent</div>';
			html_val += '<div class="dtData fLeft"><span class="hideShow  mTitle fLeft">Certificate:&nbsp;</span>'+data[i].cert1_no+'</div>';
			html_val += '<div class="dtData fLeft">';
			html_val += '<div class="waves-effect waves-light cFF font14 fRight viewmorebtn fmsansr">';
			html_val += '<span class="addBtn">Rs.'+data[i].price+'</span>';
			html_val += '<i class="mdi-content-add addBtn font16"></i>';
			html_val += '</div>';
			html_val += '</div>';
			html_val += '</div>';
		}
		
		$( ".temp" ).remove();
        $("#mainheader").after(html_val);
        $( ".temp" ).velocity({opacity: "0", translateX: "150%"}, {duration: 0, delay: 0});
        Materialize.showProducts('results');
    }
}
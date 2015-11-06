
 
function submitForm(formid)
{
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
    else if(formid=='jwAddForm'){
        y=$('#jwAddForm').serializeArray();
    }
    else if(formid=='bAddForm'){
        y=$('#bAddForm').serializeArray();
    }
    $('input[type=checkbox]:checked').each(function(){
       optionValue.push(($(this).attr('value'))); 
    });
    
   // var i=1;
   //console.log(y);
    $(y).each(function(i,val){
       // console.log(val.name);
       // console.log(val.value);
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
      //Need var catid 
    data = values.join('|~|');
   //console.log(data);
    //var JsonString = JSON.stringify(values);
    var params = 'action=addNewproduct&category_id='+catid+'&dt='+data+'&prdid='+pid;
  
    var URL = DOMAIN+"apis/index.php";
    console.log(params);
    $.getJSON(URL, params, function(data) {
            console.log(data);    
       // getResultsData(data);   
    });
    
    return false;
}
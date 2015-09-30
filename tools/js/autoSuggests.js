$.extend( $.ui.autocomplete.prototype, {
    _renderItem: function( ul, item ) {
        var term = this.element.val(),
            html = item.label.replace( term, "<span class='clBlue'>$&</span>" );
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( $("<a></a>").html(html) )
            .appendTo( ul );
    }
});



$(function() {
    var cityList=["ahmedabad","bengaluru","chandigarh","chennai","cochin","delhi","gandhiNagar","hyderabad","kolkata","mumbai","pune","goa"];

    var previousValue = "";

    $('#citySelect').autocomplete({
        autoFocus: true,
        minLength: 1,
        source: cityList,
        
        /*source: function(req, responseFn) {
          console.log("search on: '" + req.term + "'<br/>");

          var matches = new Array();
          var needle = req.term.toLowerCase();

          var len = cityList.length;
          for(i = 0; i < len; ++i)
          {
              var haystack = cityList[i].toLowerCase();
              if(haystack.indexOf(needle) == 0 ||
                 haystack.indexOf(" " + needle) != -1)
              {
                  matches.push(cityList[i]);
              }
          }

        console.log("Result: " + matches.length + " items<br/>");
        responseFn( matches );
        },*/
        
        
        select: function(event, ui) {
            getData('shape','','','','','','','round');
             //getData(cse, cfrm, cto, pfrm, pto, ccla, ccol,shp)
            $(this).blur();
            if(pw>960){
                $('.baseContainer').addClass('baseTransit');
                //$('.logoTop').removeClass('dn');
                $('#content').removeClass('dn');
                $('#logo').addClass('h0');
                $('#prdC4').addClass('pTop75');
                setTimeout(function(){
                    $('.logoTop').removeClass('logoTransit1');
                    doScroll=false;
                    closeFilters();
                },100);
            }
	}
    }).keyup(function() {
        var isValid = false;
        for (i in cityList) {
            if (cityList[i].toLowerCase().match(this.value.toLowerCase())) {
                isValid = true;
            }
        }
        if (!isValid) {
            this.value = previousValue;
        } else {
            previousValue = this.value;
        }
    }).on('focus', function(event) {
       // $(this).autocomplete('search', "");
        
    });
});



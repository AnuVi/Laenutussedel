 
google.load("jquery", "1.4.2")
google.setOnLoadCallback (function (){
// klaviatuurilt navigeerimiseks
$("input").not( $(":button") ).keypress(function (evt)
{   // alla tab&nool
	 if (evt.keyCode == 13 || evt.keyCode == 40) {
	iname = $(this).val();
	if (iname !== 'Submit'){
	var fields = $(this).parents('form:eq(0),body').find('button, input/*,textarea, select*/');
	var index = fields.index( this );
	if ( index > -1 && ( index + 1 ) < fields.length ) {
	fields.eq( index + 1 ).focus();
	}
	return false;
	}
	}
	//yles nool
	if (evt.keyCode == 38) {
	iname = $(this).val();
	if (iname !== 'Submit'){
	var fields = $(this).parents('form:eq(0),body').find('button, input');
	var index = fields.index( this );
	if ( index > -1 && ( index + 1 ) < fields.length ) {
	fields.eq( index -1 ).focus();
	}
	return false;
	}
	}

});

// vormi kontroll
	//nõutud väljad
    required = ["kviit", "pkiri", "pnimi", "lkaart", "pin"]; //"pin"
    kviit = $("#kviit");
	pkiri = $("#pkiri");
	pnimi = $("#pnimi");
	lkaart = $("#lkaart");
	pin = $("#pin");
	//Veateated
	emptyw="Tühi väli - Empty field"; //Tühi väli
	$rkuikaks="Rohkem kui kaks sümbolit - More than 2 symbols"; //rohkem kui kaks kohta
	$lkaartw="Pole TÜ raamatukogu lugeja - Card number isn't valid";
	$ilmaastaw="1525-1999"
	$pnimiw="Rohkem kui kaks tähte - More than 2 letters"
	
	$("#stellimus").submit(function(){	
		//nõutud väljade 
		for (i=0;i<required.length;i++) {
			var input = $('#'+required[i]);
			if ((input.val() == "") || (input.val() == emptyw)) {
				input.addClass("warning");
				input.val(emptyw);
			} else {
				input.removeClass("warning");
			}
		}
		
//nõutud väljad
    //kohaviit - rohkem kui kaks tähte
    if (kviit.val().length<2 || kviit.val()==$rkuikaks) {
			     kviit.addClass("warning");
			     kviit.val($rkuikaks);
			     
                         
    }
	//pealkiri - rohkem kui kaks tähte
	if (pkiri.val().length<2 || pkiri.val()==$rkuikaks) {
			     pkiri.addClass("warning");
			     pkiri.val($rkuikaks);
			     
                         
    }
	//nimi - rohkem kui kaks tähte
	if (pnimi.val().length<2 || pnimi.val()==$pnimiw) {
			     pnimi.addClass("warning");
			     pnimi.val($pnimiw);
    }
	
	
	// kaart peab algama y-ga
	if (lkaart.val()==$lkaartw || !/^[a|y|n|l|A|Y|L|N]{1}/.test(lkaart.val())){
			    lkaart.addClass("warning");
			    lkaart.val($lkaartw);
			     
                         
    }
	
	// kui on sisestatud, siis õiges formaadis
	autor=$("#autor");
	if (!autor.val()=="" && autor.val().length<2 || autor.val()==$rkuikaks) {
			    autor.addClass("warning");
			     autor.val($rkuikaks);
			     
                         
    }
	//ilmumisaasta vahemikus 1525-1999
	ilmaasta=$("#ilmaasta");
	if (!ilmaasta.val()=="" && !/^[1]{1}\d{3}$/.test(ilmaasta.val())) {
			    ilmaasta.addClass("warning");
			    ilmaasta.val($ilmaastaw);
			     
                         
    }
	
		//kui nõutud väljadel on klass "warning", siis vormi edasi ei saadeta
		if ($(":input").hasClass("warning")) {
			return false;
		} else {
			return true;
		}
	});
	
	// Puhastab väljad kui kasutaja klikib
	$(":input").focus(function(){		
	   if ($(this).hasClass("warning")) {
			$(this).val("");
			$(this).removeClass("warning");
			
	   }
	});
	
	
	
});
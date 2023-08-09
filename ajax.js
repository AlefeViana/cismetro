try{
    xmlhttp = new XMLHttpRequest();
  }catch(ee){
    try{
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }catch(e){
        try{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }catch(E){
            xmlhttp = false;
        }
    }
  }
  

  
  
var ajax = 	{
	engine: function(tp,campo,cd,id)
	{
	  	 //alert(tp+" "+campo+" "+cd+" "+id);
		
	switch(tp){
	  case 'texto' : {
	    campo = campo;
	  }
	  break;
	  /* case 'select' : {
        campo = campo.options[campo.selectedIndex].value;
	  }
	  break;
	   case 'check' : {
	    campo = campo_nativo.value;
		
		if(campo_nativo.name == 'confirmar'){
		  if(campo == 'marcado'){
		    campo_nativo.value = 'realizado';
		  // inser no banco
		  }else if(campo == 'realizado'){
		    campo_nativo.value = 'marcado';
		  // inser no banco
		  }
		}
		if(campo_nativo.name == 'pactuacao'){
	      campo = campo_nativo.value;

		  if(campo == '1'){
		    campo_nativo.value = '0';
		  }else if(campo == '0'){
		    campo_nativo.value = '1';
		  }
		}
	  }
	  break;*/
	}
		
		 //alert(campo+' '+cd+' '+id);
		
		// abrirPag("dados.php?cdtetoppi="+cdtetoppi);
		xmlhttp.open("GET", "altera_ajax.php?campo="+campo+"&cd="+cd+"&id="+id,true);
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState == 4){
			  //document.getElementById('msg').innerHTML = "Salvando..";
		    if ( xmlhttp.status == 200) {
			   //document.getElementById('msg').innerHTML = "Salvo..";
			   
			    //alert(xmlhttp.responseText);
			   	
				op_msg  = xmlhttp.responseText;
				res_msg = op_msg.split('|', 4);


			  if(res_msg[0] == 1) {
			    document.getElementById('alert').innerHTML = " Atenção: Existem ocorrências com o mesmo nome cadastrado!  ";
			    alert(' Atenção: Existem ocorrências com o mesmo nome cadastrado! ');
			    /*  valor = xmlhttp.responseText;
		 	    alert(' Atenção: O Paciente já existe cadastrado! ');*/
			  }else if(res_msg[0] == 2) {
				
				//alert('Valor: '+res_msg[1]+' - Valor SUS: '+res_msg[2]+' - QTDE: '+res_msg[3]);
				
				document.getElementById('qts_'+cd).value = res_msg[1];
				document.getElementById('valor_'+cd).value = res_msg[2];
				document.getElementById('valor_sus_'+cd).value = res_msg[3];

			  } else {
				  document.getElementById('alert').innerHTML = " Os Campos com * devem ser preechidos obrigatóriamente  ";	
			  }
			}
		  }
		}
		// abrirPag("dados.php?cdtetoppi="+cdtetoppi);
		xmlhttp.send(null);
	}
}





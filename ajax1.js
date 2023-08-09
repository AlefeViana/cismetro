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
	
	engine: function(campo)
	{
		
	//	alert(campo);
		xmlhttp.open("GET", "ajax.php?campo="+campo,true);
			
		xmlhttp.onreadystatechange=function() {
		
		  if (xmlhttp.readyState == 4){
		  if ( xmlhttp.status == 200) { 
		     
			  if(xmlhttp.responseText == 1)
			  {
			   document.getElementById('alert').innerHTML = " Atenção: Existem ocorrências com o mesmo nome cadastrado!  ";
			   alert(' Atenção: Existem ocorrências com o mesmo nome cadastrado! ');
			    
			  /*  valor = xmlhttp.responseText;
		 	    alert(' Atenção: O Paciente já existe cadastrado! ');*/
		  
			  }
			  else 
			  {
				  document.getElementById('alert').innerHTML = " Os Campos com * devem ser preechidos obrigatóriamente  ";	
				  
			  }
		   } 
			  else { 
				// alert( "Problema: " + xmlhttp.statusText );  
			}
		 }
		}
		xmlhttp.send(null)
		
		// xmlhttp.open("GET", "ajax.php?campo="+campo+"&tabela="+tabela+"&CdPaciente="+CdPaciente+"&CdSolCons="+CdSolCons+"&OP="+OP,true);
		// xmlhttp.open("GET", "ajax.php?campo="+campo+"&tabela="+tabela+"&CdPaciente="+CdPaciente+"&CdSolCons="+CdSolCons+"&OP="+OP,true);
   
	}
	
  /*
  engine : function(TIPO, campo_nativo, tabela, OP){
	//alert(campo_nativo.value + " - " + tabela);
	CdSolCons  = document.getElementById('CdSolCons').value;
	CdPaciente = document.getElementById('CdPaciente').value;
	
    switch(TIPO){
	  case 'texto' : {
	    campo = campo_nativo.value;
	  }
	  break;
	  case 'select' : {
        campo = campo_nativo.options[campo_nativo.selectedIndex].value;
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
	  break;
	}
	
    xmlhttp.open("GET", "ajax.php?campo="+campo+"&tabela="+tabela+"&CdPaciente="+CdPaciente+"&CdSolCons="+CdSolCons+"&OP="+OP,true);
    xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState == 4){
      if ( xmlhttp.status == 200) { 
          //  alert('Alterado com sucecsso!'); 
        } else { 
            alert( "Problema: " + xmlhttp.statusText );  
        }
      }
    }
    xmlhttp.send(null)
  }*/
  
  
}





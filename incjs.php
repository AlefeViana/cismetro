<!-- thickbox -->
<link rel="stylesheet" href="thickbox/thickbox.css" type="text/css" media="screen" />
<!--script type="text/javascript" src="thickbox/jquery.js"></script-->
<script type="text/javascript" src="thickbox/thickbox.js"></script>

<!-- ******************************************************************************--> <!--script type="text/javascript" src="js/jquery-1.4.2.min.js"></script-->
	<script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/additional-methodsbr.js"></script>
    <script type="text/javascript" src="js/localization/messages_ptbr.js"></script>
    <script type="text/javascript" src="js/jquery.maskedinput-1.2.2.min.js"></script>

    <script type="text/javascript" src="js/jquery.maskMoney.0.2.js"></script>
    <!--script type="text/javascript" src="js/jquery-ui-1.8.4.custom.min.js"></script-->
    
	<script type="text/javascript"><!--//--><![CDATA[//><!--		
													 
		startList = function() 
		{ 
			if (document.all&&document.getElementById) {
				navRoot = document.getElementById("nav");
				for (i=0; i<navRoot.childNodes.length; i++) {
					node = navRoot.childNodes[i];
					if (node.nodeName=="LI") {
						node.onmouseover=function() {
							this.className+=" over";
						}
						node.onmouseout=function() {
							this.className=this.className.replace(" over", "");
						}
					}
				}
			}
    	}
    window.onload=startList;
	
	function valida_data(value) {
		//contando chars
		if(value.length!=10) return false;
		// verificando data
		var data = value;
		var dia = data.substr(0,2);
		var barra1 = data.substr(2,1);
		var mes = data.substr(3,2);
		var barra2 = data.substr(5,1);
		var ano = data.substr(6,4);				
		
		if(isNaN(dia) && isNaN(mes) && isNaN(ano)) return true;
		
		var hoje = new Date();
		var dia_a = hoje.getDate();
		if(dia_a < 10) dia_a = '0'+dia_a;
		var mes_a = hoje.getMonth() + 1; //soma se 1 devido o mes começar com 0
		if(mes_a < 10) mes_a = '0'+mes_a;
		var ano_a = hoje.getFullYear();
		//verifica se a data do agendamento é maior ou igual a data atual
		//alert(ano_a+mes_a+dia_a+'-'+ano+mes+dia);
		
		if (ano < ano_a){
			alert('A data informada deve ser maior ou igual a data atual: '+dia_a+'/'+mes_a+'/'+ano_a);
			return false;
		}else{
			if(ano == ano_a){
				if (mes < mes_a){
					alert('A data informada deve ser maior ou igual a data atual: '+dia_a+'/'+mes_a+'/'+ano_a);
					return false;
				}else{
					if (mes == mes_a){
						if (dia_a > dia){
							alert('A data informada deve ser maior ou igual a data atual: '+dia_a+'/'+mes_a+'/'+ano_a);
							return false;
						}	
					}				
				}
			}
		}
		
		if(data.length!=10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia>31 || mes>12)return false;
		if((mes==4 || mes==6 || mes==9 || mes==11) && dia==31)return false;
		if(mes==2 && (dia>29 || (dia==29 && ano%4 != 0)))return false;
		if(ano < 1890)return false;
		return true;
	}					   

	function valida_horario(value) {
			//contando chars
			if(value.length!=5) return false;
			// verificando hora
			var horario   = value;
			var hora      = horario.substr(0,2);
			var separador = horario.substr(2,1);
			var minuto    = horario.substr(3,2);
			
			if(isNaN(hora) && isNaN(minuto)) return true;
			
			if(horario.length != 5 || separador != ":" || isNaN(hora) || isNaN(minuto) || hora>23 || minuto>59)return false;
			return true;
	}
	
	function abrirpop(url,nome,w,h,s){
	janela = window.open(url,nome,'width='+w+',height='+h+',top=1,left=1,scrollbars='+s+',toolbar=no,menubar=no,status=no,location=no,resizable=no');
	janela.focus();
	}
	
/********************************/
// MARCAR CAMPO AMARELO AO SELECIONAR 

/*
// super-hiper-mega função de estilizar inputs
window.onload=function(){
var arrObj = document.getElementsByTagName("INPUT");

        for( var i=0; i<arrObj.length; i++ ) {
                arrObj[i].onfocus= function() {
                mudarClass('nodestaque',this);
                }
                arrObj[i].onblur= function() {
                mudarClass('tabe',this);
                }
        }
var arrObj = document.getElementsByTagName("SELECT");

        for( var i=0; i<arrObj.length; i++ ) {
                arrObj[i].onfocus= function() {
                mudarClass('nodestaque',this);
                }
                arrObj[i].onblur= function() {
                mudarClass('tabe',this);
                }
        }
        
var arrObj2 = document.getElementsByTagName("TEXTAREA");
        for( var i=0; i<arrObj2.length; i++ ) {
                arrObj2[i].onfocus= function() {
                mudarClass('nodestaque',this);
                }
                arrObj2[i].onblur= function() {
                mudarClass('tabe',this);
                }
        }
        function mudarClass(nomeClasse,ids){
                ids.className = nomeClasse;
        }
}

*/







    </script>

<style> 

/*estilos dos inputs DESTAQUE AMARELO /
.nodestaque {
        background-color: #FFFFE1;
        border: 1px solid #ED6E37;
}
.tabe {
        border: 1px solid #ED6E37;
        background-color: #fff;
} */
</style>


    
<!-- JQuery -->
<!--script type="text/javascript" src="js/jquery-1.4.3.js"></script-->
<script type="text/javascript" src="ajax.js">




</script>
<?php include 'ajax.php'; ?>



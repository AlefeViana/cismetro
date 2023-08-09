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
		
		var hoje = new Date();
		var dia_a = hoje.getDate();
		if(dia_a < 10) dia_a = '0'+dia_a;
		var mes_a = hoje.getMonth() + 1; //soma se 1 devido o mes começar com 0
		if(mes_a < 10) mes_a = '0'+mes_a;
		var ano_a = hoje.getFullYear();
		//verifica se a data do agendamento é maior ou igual a data atual
		//alert(ano_a+mes_a+dia_a+'-'+ano+mes+dia);
		if(ano_a+mes_a+dia_a > ano+mes+dia){ 
			alert('A data informada deve ser maior ou igual a data atual: '+dia_a+'/'+mes_a+'/'+ano_a);
			return false;
		}
		
		//if(isNaN(dia) && isNaN(mes) && isNaN(ano)) return true;
		
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
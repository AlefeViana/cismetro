jQuery.validator.addMethod("dateBR", function(value, element) {
		//contando chars
		if(value.length!=10) return false;
		// verificando data
		var data = value;
		var dia = data.substr(0,2);
		var barra1 = data.substr(2,1);
		var mes = data.substr(3,2);
		var barra2 = data.substr(5,1);
		var ano = data.substr(6,4);
		if(data.length!=10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia>31 || mes>12)return false;
		if((mes==4 || mes==6 || mes==9 || mes==11) && dia==31)return false;
		if(mes==2 && (dia>29 || (dia==29 && ano%4 != 0)))return false;
		if(ano < 1900)return false;
		return true;
	}, "Informe uma data válida"); // Mensagem padrão

jQuery.validator.addMethod("validaHorario", function(value, element) {
		//contando chars
		if(value.length!=5) return false;
		// verificando data
		var horario   = value;
		var hora      = horario.substr(0,2);
		var separador = horario.substr(2,1);
		var minuto    = horario.substr(3,2);
		
		if(horario.length != 5 || separador != ":" || isNaN(hora) || isNaN(minuto) || hora>23 || minuto>59)return false;
		return true;
	}, "Informe um horário válido"); // Mensagem padrão

jQuery.validator.addMethod("verificaCPF", function(value, element) {
		value = value.replace('.','');
		value = value.replace('.','');
		cpf = value.replace('-','');
		//add para não validar o campo em branco
		if (cpf.length == 0) return true;
		
		while(cpf.length < 11) cpf = "0"+ cpf;
		var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
		var a = [];
		var b = new Number;
		var c = 11;
		for (i=0; i<11; i++){
			a[i] = cpf.charAt(i);
			if (i < 9) b += (a[i] * --c);
		}
		if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11-x }
		b = 0;
		c = 11;
		for (y=0; y<10; y++) b += (a[y] * c--);
		if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }
		if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) return false;
		return true;
}, "Informe um CPF válido."); // Mensagem padrão

jQuery.validator.addMethod("verificaCNPJ", function(value, element) {
	cnpj = value.replace(/\D/g,"");
	while(cnpj.length < 14) cnpj = "0"+ cnpj;
	var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
	var a = [];
	var b = new Number;
	var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];

	for (i=0; i<12; i++){
		a[i] = cnpj.charAt(i);
		b += a[i] * c[i+1];
	}

	if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
	b = 0;
	for (y=0; y<13; y++) {
		b += (a[y] * c[y]);
	}

	if ((x = b % 11) < 2) { a[13] = 0; } else { a[13] = 11-x; }
	if ((cnpj.charAt(12) != a[12]) || (cnpj.charAt(13) != a[13]) || cnpj.match(expReg) ) return false;
	return true;
}, "CNPJ inválido."); // Mensagem padrão

jQuery.validator.addMethod("validaCNS", function (vlrCNS) {
	// Formulário que contem o campo CNS
	var soma = new Number;
    var resto = new Number;
	var dv = new Number;
    var pis = new String;
    var resultado = new String;
	var tamCNS = vlrCNS.length;
	if ((tamCNS) != 15) {
	 //	alert("Numero de CNS invalido");
		return false;
	}
	if ((vlrCNS) == '000000000000000') {
	 //	alert("Numero de CNS invalido");
		return false;
	}
	pis = vlrCNS.substring(0,11);
	if(pis.substring(0,1) === "1" || pis.substring(0,1) === "2"){
		soma = (((Number(pis.substring(0,1))) * 15) +   
				((Number(pis.substring(1,2))) * 14) +
				((Number(pis.substring(2,3))) * 13) +
				((Number(pis.substring(3,4))) * 12) +
				((Number(pis.substring(4,5))) * 11) +
				((Number(pis.substring(5,6))) * 10) +
				((Number(pis.substring(6,7))) * 9) +
				((Number(pis.substring(7,8))) * 8) +
				((Number(pis.substring(8,9))) * 7) +
				((Number(pis.substring(9,10))) * 6) +
				((Number(pis.substring(10,11))) * 5));
		resto = soma % 11;
		dv = 11 - resto;
		if (dv == 11) {
			dv = 0;
		}
		if (dv == 10) {
			soma = (((Number(pis.substring(0,1))) * 15) +   
					((Number(pis.substring(1,2))) * 14) +
					((Number(pis.substring(2,3))) * 13) +
					((Number(pis.substring(3,4))) * 12) +
					((Number(pis.substring(4,5))) * 11) +
					((Number(pis.substring(5,6))) * 10) +
					((Number(pis.substring(6,7))) * 9) +
					((Number(pis.substring(7,8))) * 8) +
					((Number(pis.substring(8,9))) * 7) +
					((Number(pis.substring(9,10))) * 6) +
					((Number(pis.substring(10,11))) * 5) + 2);
			resto = soma % 11;
			dv = 11 - resto;
			resultado = pis + "001" + String(dv);
		} else { 
			resultado = pis + "000" + String(dv);
		}
		if (vlrCNS != resultado) {
		//	alert("Numero de CNS invalido");
		  return false;
		} else {
		  //	alert("Numero de CNS válido");
		   return true;
		}
	}else{
		//alert('CNS provisório!');
		 pis = vlrCNS.substring(0,15);
		  soma = ((parseInt(pis.substring(0, 1), 10)) * 15)
		  + ((parseInt(pis.substring(1, 2), 10)) * 14)
		  + ((parseInt(pis.substring(2, 3), 10)) * 13)
		  + ((parseInt(pis.substring(3, 4), 10)) * 12)
		  + ((parseInt(pis.substring(4, 5), 10)) * 11)
		  + ((parseInt(pis.substring(5, 6), 10)) * 10)
		  + ((parseInt(pis.substring(6, 7), 10)) * 9)
		  + ((parseInt(pis.substring(7, 8), 10)) * 8)
		  + ((parseInt(pis.substring(8, 9), 10)) * 7)
		  + ((parseInt(pis.substring(9, 10), 10)) * 6)
		  + ((parseInt(pis.substring(10, 11), 10)) * 5)
		  + ((parseInt(pis.substring(11, 12), 10)) * 4)
		  + ((parseInt(pis.substring(12, 13), 10)) * 3)
		  + ((parseInt(pis.substring(13, 14), 10)) * 2)
		  + ((parseInt(pis.substring(14, 15), 10)) * 1);
		
		  resto = soma % 11;
		
		  if (resto == 0) {
		  //alert("N\u00famero de CNS Provis\u00f3rio V\u00e1lido");
		  return true;
		  }
		  else {
		  //alert("N\u00famero Provis\u00f3rio Inv\u00e1lido!");
		  //alert("N\u00famero de CNS Provis\u00f3rio Inv\u00e1lido");
		  return false;
		  }		
		return true;
	}
}, "CARTÃO SUS inválido."); // Mensagem padrão 



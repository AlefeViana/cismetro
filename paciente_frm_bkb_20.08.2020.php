﻿<!-- <script type="text/javascript" src="jquery/jquery.js"></script>  -->
<!--Para ultilizar o web service deve-se fazer a inclusão da blibioteca jquery disponivel para download no site http://jquery.com/-->
<script type="text/javascript" src="jquery/cep.js"></script>
<!--outras informações dentro do arquivo jquery/cep.js--> 
<script src="pg/CadSUS/xml2json.min.js"></script>



<script type="text/javascript"> 
	var cpf_a 
	$(document).ready(function() {	
		cpf_a = $("#cpf").val();
		cpf_a = cpf_a.replace(".","");
		cpf_a = cpf_a.replace(".","");
		cpf_a = cpf_a.replace("-","");
	//validação CADSUS - INICIO

	$('#csus').on('focusout', function() {
		var csus = $(this).val();
		<?php if($_GET["acao"] != "edit") { ?>
		$.ajax({
			type: 'GET',
			url: "admin/verifica_csus_cpf.php?csus="+csus,
			success: function(data) {
				var value = data;
				if(value != "true"){
					document.getElementById("erroCsus").innerHTML = value.replace('\"','').replace('\"','');
					document.getElementById("btn").disabled = true;
				}else{
					document.getElementById("btn").disabled = false;
					document.getElementById("erroCsus").innerHTML = "";
				}
			}
		});
	<?php } ?>
		$.ajax({
			url: 'pg/CadSUS/busca_dados_paciente.php',
			type: 'GET',
			cache: false,
			data: {cns : csus },
			datatype: "json",
			error: function() {
				Swal.fire('CNS inválido!')
			},
			success: function(resposta) {
		    	//resposta = JSON.parse(resposta);
		    	//console.log(resposta);
		    	const x2js = new X2JS();
		    	const xmlText = resposta;

		    	const data = x2js.xml_str2json(xmlText);

		    	if(typeof data !== 'undefined' && data !== null) {
		    		var paciente = data.Envelope.Body;
		    		var keys = Object.keys( paciente );
		    		paciente = paciente[keys[0]].controlActProcess.subject.registrationEvent.subject1.patient.patientPerson;
		    		console.log(paciente.asOtherIDs[0].id[0]._extension);
		    		console.log(paciente);
		    		$('#nm_paciente').attr('value', paciente.name.given);
		    		$('#cpf').attr('value', paciente.asOtherIDs[1].id._extension);
		    		$('#mae_paciente').attr('value', paciente.personalRelationship[0].relationshipHolder1.name.given);
		    		$('#pai').attr('value', paciente.personalRelationship[1].relationshipHolder1.name.given);
		    		var data_pac = paciente.birthTime._value;
		    		var dataano = data_pac.substring(0, 4);
		    		var datames = data_pac.substring(4, 6);
		    		var datadia = data_pac.substring(6, 8);
		    		var data_paciente = datadia+'/'+datames+'/'+dataano;
		    		$('#data').attr('value', data_paciente);
		    		$('#sexo').val(paciente.administrativeGenderCode._code);
		    		$('#cep').attr('value', paciente.addr.postalCode);
		    		$('#logradouro').attr('value', paciente.addr.streetName);
					//$('#cidade').attr('value', paciente.personalRelationship[1].relationshipHolder1.name.given);
					$('#num').attr('value', paciente.addr.houseNumber);
					//$('#bairro').attr('value', paciente.personalRelationship[1].relationshipHolder1.name.given);
					var codlog = paciente.addr.streetNameType;
					var codlog = codlog.replace(/^0+(?!\.|$)/, '');
					$('#logr').val(codlog).trigger('change');

					Swal.fire('Dados básicos do paciente obtidos com sucesso!')
				}
			}    
		});
	});
	function limpa_formulário_cep() {
		// Limpa valores do formulário de cep.
		$("#rua").val("");
		$("#bairro").val("");
		//$("#cidade").val("");
		$("#uf").val("");
		$("#ibge").val("");
	}
	
	//Quando o campo cep perde o foco.
	$("#cep").blur(function() {

		//Nova variável "cep" somente com dígitos.
		var cep = $(this).val().replace(/\D/g, '');

		//Verifica se campo cep possui valor informado.
		if (cep != "") {

			//Expressão regular para validar o CEP.
			var validacep = /^[0-9]{8}$/;

			//Valida o formato do CEP.
			if(validacep.test(cep)) {

				//Preenche os campos com "..." enquanto consulta webservice.
				$("#logradouro").val("...");
				$("#bairro").val("...");
				//$("#cidade").val("...");

				//Consulta o webservice viacep.com.br/
				$.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
					$nomepref = $( "#CdPref option:selected" ).text();
					console.log($nomepref);
					
					if (!("erro" in dados)) {
						//Atualiza os campos com os valores da consulta.
						$("#logradouro").val(dados.logradouro);
						$("#bairro").val(dados.bairro);
						document.getElementById("btn").disabled = false;
						document.getElementById("erroCsus").innerHTML = "";
						//$("#cidade").val(dados.localidade);
					} //end if.
					else {
						//CEP pesquisado não foi encontrado.
						limpa_formulário_cep();
						document.getElementById("erroCEP").innerHTML = value.replace('\"','').replace('\"','');
						document.getElementById("btn").disabled = true;
					}
				});
			} //end if.
			else {
				//cep é inválido.
				limpa_formulário_cep();
				alert("Formato de CEP inválido.");
			}
		} //end if.
		else {
			//cep sem valor, limpa formulário.
			limpa_formulário_cep();
		}
	});
	
	$("#isNotifiable").change(function(){
		let value = $(this).is(":checked");
		!value ? $("#cel").prop("required", true) : $("#cel").prop("required", false) ;
		!value ? $("#tel").prop("required", false) : $("#tel").prop("required", true) ;
	})
	//validação CADSUS - FIM

	/*$("#commentForm").validate({
		rules: {
			"cpf_paciente": {
				required: false,
				verificaCPF: true,
				<?php if($_GET['acao'] != "edit") { ?>
					remote: 'admin/verifica_csus_cpf.php' 
				<?php } ?>					
			},
			"csus":{
				validaCNS: true,	
				<?php if($_GET["acao"] != "edit") { ?>
					remote: 'admin/verifica_csus_cpf.php' 
				<?php } ?>				
			},
			"dtnasc_paciente":{
				dateBR: true				
			}		
		},
		messages: {
			"cpf_paciente": {
				required: "Digite seu cpf.",
				verificaCPF: "CPF inválido.",
				remote: 'CPF já cadastrado!'			
			},
			"dtnasc_paciente": {
				dateBR: "Data inválida.",
			},
			"csus":{
				validaCNS: "Cartão SUS inválido!",
				//remote: 'Cartão SUS já cadastrado!'
			}						
		},
	}); 
	
	$("#commentForm").validate(); */

	$('#cpf').change(function(){	
		
		if($("#cpf").val().length == 14){
			var s = $("#cpf").val();
			s = s.replace('.','');
			s = s.replace('.','');
			s = s.replace('-','');

			if(TestaCPF(s)){
				document.getElementById("erroCPF").innerHTML = "";
				$.ajax({
					type: 'GET',
					url: "admin/verifica_csus_cpf.php?cpf_paciente="+s,
					success: function(data) {
						var value = data;
						if(value != "true" <?php if($_GET["acao"] == "edit") { echo "& cpf_a != s"; }?>){
							document.getElementById("erroCPF").innerHTML = "CPF já cadastrado";
							document.getElementById("btn").disabled = true;
						}else{
							document.getElementById("btn").disabled = false;
							document.getElementById("erroCPF").innerHTML = "";
						}
					}
				});
			}else{
				document.getElementById("btn").disabled = true;
				document.getElementById("erroCPF").innerHTML = "CPF Inválido";

			}
		}

	});
	$('#cidade').change(function(){	
	/* 	$('#bairro').load('admin/load_bairro.php?cdpref='+$('#cidade').val() , function(response, status, xhr) {
			if (status == "error") {
				var msg = "Sorry but there was an error: ";
				$("#error").html(msg + xhr.status + " " + xhr.statusText);
				alert(msg + xhr.status + " " + xhr.statusText);
			}
		}); */
		$('#und_saude').load('admin/load_upa.php?cdpref='+$('#cidade').val() , function(response, status, xhr) {
			if (status == "error") {
				var msg = "Sorry but there was an error: ";
				$("#error").html(msg + xhr.status + " " + xhr.statusText);
				alert(msg + xhr.status + " " + xhr.statusText);
			}
		});																						
	});

	$('#btncadbairro').click(function(){
		var link='';

		link += '&nmpac='  + $("#nome").val();
		link += '&rg='    + $("#rg").val();
		link += '&cpf='   + $("#cpf").val();
		link += '&docs='  + $("#docs").val();
		link += '&sexo='  + $("#sexo").val();
		link += '&data='  + $("#data").val();
		link += '&mae='   + $("#mae").val();
		link += '&pai='   + $("#pai").val();
		link += '&tel='   + $("#tel").val();
		link += '&cel='   + $("#cel").val();
		link += '&email=' + $("#email").val();
		link += '&logr='  + $("#logradouro").val();
		link += '&num='   + $("#num").val();
		link += '&compl=' + $("#compl").val();
		link += '&cdpac=' + $("#cd").val();

		window.location.href='index.php?p=frm_cadbairro&pg=1'+link;
	});
	$('#logr').select2();
	$('#cidade').select2();
	// $('#bairro').select2();
});

jQuery(function($){
	$("#data").mask("99/99/9999");
	$("#tel").mask("(99)9999-9999");
	$("#cel").mask("(99)99999-9999");
	$("#cpf").mask("999.999.999-99");
	$("#cep").mask("99999-999");
	$("#CertidaoMatricula").mask("999999 99 99 9999 9 99999 999 9999999 99");
});

function TestaCPF(strCPF) {
	var Soma;
	var Resto;
	Soma = 0;
	if (strCPF == "00000000000") return false;

	for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
		Resto = (Soma * 10) % 11;

	if ((Resto == 10) || (Resto == 11))  Resto = 0;
	if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

	Soma = 0;
	for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
		Resto = (Soma * 10) % 11;

	if ((Resto == 10) || (Resto == 11))  Resto = 0;
	if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
	return true;
}


function Onlynumbers(e) {
	var tecla = new Number();
	if (window.event) {
		tecla = e.keyCode;
	}
	else if (e.which) {
		tecla = e.which;
	}
	else {
		return true;
	}
  //if ((tecla >= "97") && (tecla <= "122")) {
  	if ((tecla < "48") || (tecla > "57")) {
  		if (tecla != "13") {
  			return false;
  		}
  		else {
  			return true;
  		}
  	}
  }
//validação de data
var dtCh= "/";
var minYear=1900;
var maxYear=<?php echo date("Y"); ?>;

function isInteger(s){
	var i;
	for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
	var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
    	var c = s.charAt(i);
    	if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
			if (i==2) {this[i] = 29}
		} 
	return this
}

function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strDay=dtStr.substring(0,pos1)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
		if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
			for (var i = 1; i <= 3; i++) {
				if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
			}
		month=parseInt(strMonth)
		day=parseInt(strDay)
		year=parseInt(strYr)
		if (pos1==-1 || pos2==-1){
			alert("O formato da date deve ser: dd/mm/aaaa")
			return false
		}
		if (strMonth.length<1 || month<1 || month>12){
			alert("Informe um mês válido!")
			return false
		}
		if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
			alert("Informe um dia válido!")
			return false
		}
		if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
			alert("O ano deve conter 4 dígitos entre "+minYear+" e "+maxYear)
			return false
		}
		if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
			alert("Informe uma data válida!")
			return false
		}
		return true
	}

	function ValidateForm(){
		var dt=document.commentForm.dtnasc_paciente
		if (isDate(dt.value)==false){
			dt.focus()
			return false
		}
		return true
	}
//fim validação de data  

</script>
<?php

require_once("verifica.php");


	//funcao para tratar erro
require("admin/function_trata_erro.php");

require "../vendor/autoload.php";
use Stringy\Stringy as S;

	//value do botao de submit do formulario
$btnAcao = "Salvar";

	// MODO EDIÇÃO 
if(($_GET['id']) && ($_GET['acao']=='edit') || ($_GET['acao']=='del'))
{
	$CdPac = $_GET["id"];
	$acao = "edit";



	$sql = "SELECT p.is_notifiable as isNotifiable, p.Status as status,CdPaciente,p.CdBairro,b.NmBairro,NmPaciente,RG,CPF,csus,p.CertidaoMatricula,
	OutrosDocs,Sexo,DtNasc,Naturalidade,Nacionalidade,NmMae,NmPai,Telefone,Celular,
	Email,Profissao,Logradouro,Numero,Compl,p.CEP,b.CdPref,p.Referencia,orgaorg,cdlogr, Matricula, Prontuario 
	FROM tbpaciente p INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro
	WHERE CdPaciente=".$CdPac; 
			//echo $sql;		
			//exibe somente os pacientes da localidade do usuario logado					
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " AND CdPref=".(int)$_SESSION["CdOrigem"];		
	}
	
	$query = mysqli_query($db,$sql);
	if (mysqli_num_rows($query)) $row = mysqli_fetch_array($query);



	if($_GET['acao']=='del') { 
		$btnAcao = ($row['status'] == "1" ? "Desativar" : "Ativar") ; 
		$acao = "del"; 
	}




}
else 
{
	  // MODO INSERÇÃO	
	$acao = "cad";
}


?>



<div class="row justify-content-center">


	<div class="col-md-8">
		<form method="POST" 
		class="form-group" 
		action="admin/regn_pac.php" 
		id="commentForm" 
		name="commentForm">
		<input 
		type="hidden" name="cd_paciente" 
		class = "form-control" id="cd" 
		value="<?php echo $row["CdPaciente"] ?? "Automático"; ?>"/>
		
		<input type="hidden" 
		name="docs_paciente" 
		id="docs" value="<?php echo $row["OutrosDocs"]; ?>" />

		<input type="hidden" name="acao" value="<?php echo $acao; ?>" />
		<input type="hidden" name="paciente_status" value="<?php echo $row['status']; ?>" />
		<input type="hidden" 
		name="profissao" 
		id="profissao" 
		value="<?php echo $row["Profissao"]; ?>" />

		<?php if($_GET['acao'] != "del") :?>

			<div class="alert alert-warning" role="alert">
				<strong>Atenção!</strong>
				Os Campos com * devem ser preechidos obrigatóriamente
			</div>
			<?php else : ?>

				<div class="alert alert-primary" role="alert">
					<h4 class="alert-heading">Atenção!</h4>
					<p>Você está <?=($row['status'] == "1" ? "desativando" : "ativando")?> este registro </p>
					<p class="mb-0"></p>
				</div>	



			<?php endif ?>


			<div class="form-group">
				<label for="csus"> Buscar Paciente - CADSUS</label>
				<input 
				type="text" 
				id="csus" 
				name="csus" 
				maxlength="15" 
				class="form-control" 
				placeholder="Digite o CNS do paciente"   
				value="<?php echo $row["csus"]; ?>" 
				onkeypress="return Onlynumbers(event)" 
				aria-describedby="helpId">
				<span id="erroCsus" style="color:red;"></span>
			</div>

			<hr>

			<h4>Dados do paciente</h4>

			<div class="form-group">
				<label for="nm_paciente"> Nome*</label>
				<input 
				type="text" 
				id="nm_paciente" 
				name="nm_paciente" 
				maxlength="100" 
				required
				class="form-control" 
				placeholder="Nome do paciente"   
				value="<?php echo $row["NmPaciente"]; ?>" 

				aria-describedby="helpId">

			</div>

			<div class="row">
				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="sexo">Sexo*</label>
						<select class="custom-select" name="sexo_paciente" id="sexo" required>
							<option value=""  >Selecione..</option>
							<option value="F" <?php  if($row["Sexo"] == "F") echo 'selected="selected"'; ?> >Feminino</option>
							<option value="M" <?php  if($row["Sexo"] == "M") echo 'selected="selected"'; ?> >Masculino</option>
						</select>
					</div>			
				</div>
				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="orgaorg">Orgão emissor</label>
						<input type="text" 
						class="form-control" name="orgaorg" id="orgaorg"  value="<?php echo $row["orgaorg"]; ?>" 
						aria-describedby="helpId" 
						placeholder="Orgão emissor">

					</div>
				</div>
				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="rg">RG</label>
						<input 
						type="text" 
						class="form-control" value="<?php echo $row["RG"]; ?>" name="rg_paciente" id="rg" aria-describedby="helpId" placeholder="RG">
					</div>
				</div>
				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="cpf">CPF</label>
						<input 
						type="text" 
						class="form-control" value="<?php echo $row["CPF"]; ?>" name="cpf_paciente" id="cpf" 
						aria-describedby="helpId" placeholder="CPF">
						<span id="erroCPF" style="color:red;"></span>
					</div>

				</div>

				<div class="col-sm-12 col-md-6">
					<div class="form-group">
						<label for="CertidaoMatricula">Certidão de Nasc.(Matrícula)</label>
						<input 
						type="text" 
						class="form-control" value="<?php echo $row["CertidaoMatricula"]; ?>" name="CertidaoMatricula" id="CertidaoMatricula" 
						aria-describedby="helpId" placeholder="Certidão de Nasc.(Matrícula)">
					</div>
				</div>

				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="data">Data de Nascimento*</label>
						<input 
						required
						type="text" 
						class="form-control" 
						value="<?php echo $row["DtNasc"] ? \Carbon\Carbon::parse($row["DtNasc"])->format("d/m/Y") : ""; ?>" 
						name="dtnasc_paciente" 
						id="data" 
						aria-describedby="helpId" placeholder="data">
					</div>
				</div>

				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="naturalidade">Naturalidade</label>
						<input 

						type="text" 
						class="form-control" 
						value="<?php echo $row["Naturalidade"]; ?>" 
						name="naturalidade" 
						id="naturalidade" 
						aria-describedby="helpId" placeholder="Naturalidade">
					</div>
				</div>

				<div class="col-sm-12 col-md-6">
					<div class="form-group">
						<label for="nacionalidade">Nacionalidade*</label>
						<input 
						required
						type="text" 
						class="form-control" 
						value="<?php echo $row["Nacionalidade"] ?? "Brasileira" ?>" 
						name="nacionalidade" 
						id="nacionalidade" 
						aria-describedby="helpId" 
						placeholder="nacionalidade">
					</div>
				</div>

				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="matricula">Matrícula</label>
						<input 

						type="text" 
						class="form-control" 

						name="matricula" 
						id="matricula" 
						value="<?php echo $row["Matricula"];?>"
						aria-describedby="helpId" 
						maxlength="20" 
						placeholder="matricula">
					</div>

				</div>

				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="numprontuario">N° do Prontuário</label>
						<input
						type="text" 
						class="form-control"
						name="numprontuario" 
						id="numprontuario" 
						value="<?php echo $row["Prontuario"];?>"
						aria-describedby="helpId" 
						maxlength="20" 
						placeholder="Número do prontuário">
					</div>
				</div>


			</div>

			<h4>Filiação</h4>

			<div class="row">
				<div class="col-sm-12 col-md-6">

					<div class="form-group">
						<label for="pai_paciente">Nome do Pai</label>
						<input
						type="text" 
						class="form-control"
						name="pai_paciente" 
						id="pai_paciente" 
						value="<?php echo $row["NmPai"];?>"
						aria-describedby="helpId" 
						maxlength="100" 
						placeholder="Nome do pai">
					</div>

				</div>
				<div class="col-sm-12 col-md-6">
					<div class="form-group">
						<label for="mae_paciente">Nome da mãe*</label>
						<input
						required
						type="text" 
						class="form-control"
						name="mae_paciente" 
						id="mae_paciente" 
						value="<?php echo $row["NmMae"];?>"
						aria-describedby="helpId" 
						maxlength="100" 
						placeholder="Nome do pai">
					</div>
				</div>
			</div>

			<h4>Contatos</h4>

			<div class="row">
				<div class="col-sm-12 col-md-4">

					<div class="form-group">
						<label for="tel">Telefone</label>
						<input 
						type="tel" 
						class="form-control" 
						name="tel_paciente" 
						id="tel"
						
						aria-describedby="helpId" 
						value="<?php echo $row["Telefone"]; ?>"
						placeholder="">

					</div>

				</div>
				<div class="col-sm-12 col-md-4">
					<div class="form-group">
						<label for="cel">Celular*</label>
						<input 
						type="tel" 
						class="form-control" 
						name="cel_paciente" 
						id="cel" 
						required
						aria-describedby="helpId" 
						value="<?php echo $row["Celular"]; ?>" 

						placeholder="">

					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="form-group">
						<label for="email_paciente">Email</label>
						<input type="email" class="form-control" name="email_paciente" 
						id="email_paciente"
						value="<?php echo $row["Email"]; ?>"
						aria-describedby="emailHelpId" placeholder="">

					</div>
				</div>
			</div>








			<h4>Informações de endereço</h4>

			<div class="row">
				<div class="col-sm-12 col-md-4">

					<div class="form-group">
						<label for="cep">CEP*</label>
						<input type="text" required name="cep_paciente" 
						id="cep" 
						class="form-control" 
						value="<?=$row['CEP']?>"
						placeholder="Cep" onblur="getEndereco()" 
						aria-describedby="helpId">

					</div>

				</div>
				<div class="col-sm-12 col-md-4">
					<div class="form-group">
						<label for="cidade">Cidade*</label>
						<select class="form-control" name="cidade" id="cidade" require>
							<?php
						//limpa variavel que mantem os dados digitados
							unset($_SESSION["dados_pac"]);
							require("conecta.php");
							$sql = "SELECT CdPref, NmCidade FROM tbprefeitura  ";
							if ((int)$_SESSION["CdOrigem"]>0)
							{
								$sql .= " WHERE CdPref =".(int)$_SESSION["CdOrigem"];		
							}

							$sql .=  " ORDER BY NmCidade";

							$query = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select cidade'));
							if (mysqli_num_rows($query) > 0){
								while ($dados = mysqli_fetch_array($query)){
									if ($row["CdPref"] == $dados["CdPref"])
										echo '<option value="'.$dados["CdPref"].'" selected="selected">'.(String)S::create($dados["NmCidade"])->titleize(["de", "da", "do"]).'</option>';	
									else
										echo '<option value="'.$dados["CdPref"].'">'.(String)S::create($dados["NmCidade"])->titleize(["de", "da", "do"]).'</option>';
								}
							} 
							mysqli_close();
							mysqli_free_result($query);
							?>
						</select>
					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="form-group">
						<label for="logr">Código do logradouro</label>
						<select 
						required 
						name="logr" 
						id="logr" 
						class="form-control" 
						>					
						<option value="">Selecione</option>
						<?php 
						//limpa variavel que mantem os dados digitados
						unset($_SESSION["dados_pac"]);
						require("conecta.php");
						$sql = "SELECT * FROM tblogr";
						$sql .=  " ORDER BY logra";
						
						$query = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select cidade'));

						while ($logradouroData = mysqli_fetch_array($query)){
							$select = ($row["cdlogr"] == $logradouroData["cdlogr"])? "selected" : "";
							echo '<option '.$select.' value="'.$logradouroData["cdlogr"].'" >'.S::create($logradouroData["logra"])->titleize(["de", "da", "do"]).' - '.$logradouroData["cdlogr"].'</option>';	
						}
						?>
					</select>
				</div>
			</div>
			
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="logradouro">Logradouro*</label>
					<input type="text" 
					required 
					value="<?php echo $row["Logradouro"]; ?>"
					name="log_paciente" 
					maxlength="100"
					id="logradouro" 
					class="form-control" 
					placeholder="Logradouro" 
					aria-describedby="helpId">
					
				</div>
			</div>
			<div class="col-sm-12 col-md-3">
				<div class="form-group">
					<label for="numero">Número*</label>
					<input type="text" 
					required 
					value="<?php echo $row["Numero"]; ?>"
					name="num_paciente" 
					maxlength="100"
					minlength="2"
					id="numero" 
					class="form-control" 
					placeholder="Número" 
					aria-describedby="helpId">
					
				</div>
				
			</div>
			<div class="col-sm-12 col-md-3">

				<div class="form-group">
					<label for="bairro">Bairro*</label>
					<!-- <select class="form-control" name="bairro" required id="bairro">
						<option value="">Selecione uma cidade primeiro</option>
						<?php
						/* if ($row["CdPref"] != ""){
							require("conecta.php");
							$sql  = "SELECT CdBairro, NmBairro FROM tbbairro WHERE CdPref=".$row["CdPref"];
							$sql .= " ORDER BY NmBairro";

							$query = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select bairro'));
							if (mysqli_num_rows($query) > 0){
								while ($dados = mysqli_fetch_array($query)){
									if ($row["CdBairro"] == $dados["CdBairro"])
										echo '<option value="'.$dados["CdBairro"].'" selected="selected">'.(String)S::create($dados["NmBairro"])->titleize(["de", "da", "do"]).'</option>';	
									else
										echo '<option value="'.$dados["CdBairro"].'">'.(String)S::create($dados["NmBairro"])->titleize(["de", "da", "do"]).'</option>';
								}
							} 
							mysqli_close();
							mysqli_free_result($query);
						} */
						?>
					</select> -->
						<input type="text" id="bairro" name="bairro" class="form-control" value="<?php echo $row["NmBairro"]; ?>" required>
				</div>
				
			</div>

			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="compl">Complemento</label>
					<input type="text" 
					
					value="<?php echo $row["Compl"]; ?>"
					name="compl_paciente" 
					maxlength="100"
					id="compl" 
					class="form-control" 
					placeholder="Complemento" 
					aria-describedby="helpId">
					
				</div>
			</div>

			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="referencia">Referência</label>
					<input type="text" 
					
					value="<?php echo $row["Referencia"]; ?>"
					name="referencia" 
					maxlength="100"
					id="referencia" 
					class="form-control" 
					placeholder="referência" 
					aria-describedby="helpId">
					
				</div>
			</div>

			<div class="col-12">
				
				<div class="form-check">
					<input 
					type="checkbox"
					class="form-check-input" 
					name="isNotifiable"
					id="isNotifiable"
					>
					<label class="form-check-label" 
					for="isNotifiable">Paciente<b> NÃO </b>deseja receber notificações pelo whatsapp</label>
				</div>
			</div>

			<div class="col-12 text-right">

				<?php if($_GET['acao'] == "del" || $_GET['acao'] == "edit") { ?>
					<button type="button" 
					id="cancelBtn"
					class='btn btn-danger'  
					onclick="window.location.href='index.php?i=1'"> Cancelar </button>

				<?php } ?>



				<button type="submit"
				class='btn <?php if($_GET['acao'] == "del") echo ($row['Status'] ? 'btn-secondary' : 'btn-primary' ); elseif($_GET['acao'] == "edit") echo "btn-primary"; else echo "btn-success";?>'  
				name="btn" 
				id="btn"> <?php echo $btnAcao ?> </button>
			</div>
			
		</div>
	</form>
</div>




<script>
	<?php if($row['isNotifiable']== 0) { ?>$("#isNotifiable").prop("checked", true); $("#cel").prop("required", true); <?php }?>
	<?php if($_GET['acao'] == "del") { ?>

		$(":input:not(:submit, #cancelBtn, [name='cd_paciente'], [name='acao'])").prop("required", false).prop('disabled', true)
	<?php } ?>
</script>


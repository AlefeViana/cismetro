<!-- <script type="text/javascript" src="jquery/jquery.js"></script>  -->
	<!--Para ultilizar o web service deve-se fazer a inclusão da blibioteca jquery disponivel para download no site http://jquery.com/-->
	<script type="text/javascript" src="jquery/cep.js"></script>
	<!--outras informações dentro do arquivo jquery/cep.js--> 
    
        
        

<script type="text/javascript"> 
$(document).ready(function() {	
	$("#commentForm").validate({
		rules: {
			"cpf_paciente": {
					required: false,
					verificaCPF: true,
					<?php if($_GET[acao] != "edit") { ?>
					remote: 'admin/verifica_csus_cpf.php' <?php } ?>					
			},
			 "csus":{
				 validaCNS: true,	
				<?php if($_GET[acao] != "edit") { ?>
				remote: 'admin/verifica_csus_cpf.php' <?php } ?>				
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
	
	$("#commentForm").validate();
					
			$('#cidade').change(function(){	
				$('#bairro').load('admin/load_bairro.php?cdpref='+$('#cidade').val() , function(response, status, xhr) {
																						  if (status == "error") {
																								var msg = "Sorry but there was an error: ";
																								$("#error").html(msg + xhr.status + " " + xhr.statusText);
																								alert(msg + xhr.status + " " + xhr.statusText);
																						  }
																						});
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
});

jQuery(function($){
	$("#data").mask("99/99/9999");
	$("#tel").mask("(99)9999-9999");
	$("#cel").mask("(99)9999-9999");
	$("#cpf").mask("999.999.999-99");
	$("#cep").mask("99999-999");
	$("#CertidaoMatricula").mask("999999 99 99 9999 9 99999 999 9999999 99");
});




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
	$destino = "admin/regn_pac.php";
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	//value do botao de submit do formulario
	$btnAcao = "Salvar";
		
	// MODO EDIÇÃO 
	if(($_GET['id']>0) and ($_GET['acao']=='edit') or ($_GET['acao']=='del'))
	{
		    $CdPac = $_GET["id"];
			$acao = "edit";
			
			if($_GET['acao']=='del') { $btnAcao = "Excluir"; $acao = "del"; }

	  		$sql = "SELECT CdPaciente,p.CdBairro,NmPaciente,RG,CPF,csus,p.CertidaoMatricula,
						   OutrosDocs,Sexo,DtNasc,Naturalidade,Nacionalidade,NmMae,NmPai,Telefone,Celular,
						   Email,Profissao,Logradouro,Numero,Compl,CEP,b.CdPref,p.Referencia,orgaorg,cdlogr, Matricula, Prontuario 
					FROM tbpaciente p INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro
					WHERE CdPaciente=".$CdPac; 
			//echo $sql;		
			//exibe somente os pacientes da localidade do usuario logado					
			if ((int)$_SESSION["CdOrigem"]>0)
			{
				$sql .= " AND CdPref=".(int)$_SESSION["CdOrigem"];		
			}
			
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select dados'));
			if (mysqli_num_rows($qry) == 1){
				 $dados_pac = mysqli_fetch_array($qry);
			}
	}
	else 
	{
	  // MODO INSERÇÃO	
	  $acao = "cad";
	}
	
	$qry = mysqli_query($db,"SELECT MAX(CdPaciente) FROM tbpaciente");
	if (mysqli_num_rows($qry)==0) {
		echo '<script language="JavaScript" type="text/javascript"> 
				alert("ERRO 70330!! Entre em contato com o suporte, contato: (31) 3822-4656.");
				window.location.href="index.php?i=1";
			  </script>';
	}
?>


    
    <form method="POST" action="<?php echo $destino; ?>" id="commentForm" name="commentForm">
    
    <div id="form">
    <div id="alert"> Os Campos com * devem ser preechidos obrigatóriamente </div>
			   
               <fieldset> 
               <legend> Dados do Paciente </legend>
		       <input type="hidden" name="cd_paciente" id="cd" size="10" 
               value="<?php if(isset($dados_pac["CdPaciente"])) echo $dados_pac["CdPaciente"]; else echo "Autom&aacute;tico"; ?>" 
              readonly="readonly" />
               
		      <label class="gr ">Nome * <input type="text" name="nm_paciente" 
              id="nm_paciente" size="50" value="<?php echo $dados_pac["NmPaciente"]; ?>" required/></label>
<label style="clear:both;" class="gr">Cartão SUS *  
             <input type="text" name="csus" maxlength="15" id="csus" size="14" class="number"   value="<?php echo $dados_pac["csus"]; ?>" onkeypress="return Onlynumbers(event)" /></label>              
              
                      <label style="clear:both" >Sexo * <select name="sexo_paciente" id="sexo" required/>
                        <option value=""  >Selecione..</option>
                        <option value="F" <?php  if($dados_pac["Sexo"] == "F") echo 'selected="selected"'; ?> >Feminino</option>
                        <option value="M" <?php  if($dados_pac["Sexo"] == "M") echo 'selected="selected"'; ?> >Masculino</option>
                </select>
              </label>
  				
              <label>Órgão emissor  <input type="text" name="orgaorg" id="orgaorg" size="14" value="<?php echo $dados_pac["orgaorg"]; ?>"/></label>  
             <label>RG  <input type="text" name="rg_paciente" id="rg" size="14" value="<?php echo $dados_pac["RG"]; ?>"/></label>
		    <label>CPF <input type="text" name="cpf_paciente" id="cpf" size="14" value="<?php echo  $dados_pac["CPF"]; ?>"  class="required"  /></label>
		    <label>Certidão de Nasc.(Matrícula)<input type="text" name="CertidaoMatricula" id="CertidaoMatricula"  value="<?php echo  $dados_pac["CertidaoMatricula"]; ?>"></label>
		     
             
             
		   
          <?php 
		  		if (isset($dados_pac["DtNasc"])){
					$dados_pac["DtNasc"] = explode("-",$dados_pac["DtNasc"]);
					$dados_pac["DtNasc"] = $dados_pac["DtNasc"][2]."/".$dados_pac["DtNasc"][1]."/".$dados_pac["DtNasc"][0];
				}
		  ?>
		      <label >Data de Nascimento *   <input type="text" name="dtnasc_paciente" id="data" size="11" value="<?php echo $dados_pac["DtNasc"]; ?>" required/></label>
		      <label>Naturalidade <input type="text"  name="naturalidade" id="naturalidade" size="40" value="<?php echo $dados_pac["Naturalidade"]; ?>" /></label>
		      <label  class="">Nacionalidade * <input type="text" class="required" name="nacionalidade" id="nacionalidade" size="20" value="<?php if ($dados_pac["Nacionalidade"] != '') 
			  echo $dados_pac["Nacionalidade"]; else echo 'Brasileira'; ?>" /></label>
               <input type="hidden" name="docs_paciente" id="docs" size="25" value="<?php echo $dados_pac["OutrosDocs"]; ?>" />
               <label  class="">Matrícula <input type="text" name="matricula" id="matricula" maxlength="20" value="<?php if ($dados_pac["Matricula"] != '') 
			  echo $dados_pac["Matricula"]; ?>" /></label>
			  <label  class="">N° do Prontuário <input type="text" name="numprontuario" id="numprontuario" maxlength="20" value="<?php if ($dados_pac["Prontuario"] != '') 
			  echo $dados_pac["Prontuario"]; ?>" /></label>
                </fieldset>
              
              
               <fieldset> 
              
               <legend> Filiação </legend>
		      <label class="gr">Nome do Pai  <input type="text" name="pai_paciente" id="pai" size="50" value="<?php echo $dados_pac["NmPai"]; ?>" /></label>
		      <label class=" gr ">Nome da M&atilde;e *  <input type="text" class="required" name="mae_paciente" id="mae_paciente" size="50" value="<?php echo $dados_pac["NmMae"]; ?>" onblur="teste()" /></label>
              
              
              
               <input type="hidden" name="profissao" id="profissao" size="45" value="<?php echo $dados_pac["Profissao"]; ?>" />
               </fieldset>
                 <fieldset> 
               <legend> Contatos </legend>
              
              <label>Telefone*  <input type="text" name="tel_paciente" id="tel" size="13" value="<?php echo $dados_pac["Telefone"]; ?>" required/></label>
		      <label>Celular  <input type="text" name="cel_paciente" size="13" id="cel" value="<?php echo $dados_pac["Celular"]; ?>" /></label>
		      <label>E-mail  <input type="text" name="email_paciente" id="email" class="email" size="35" value="<?php echo $dados_pac["Email"]; ?>" /></label>
		     
     	       </fieldset> 
             
             <fieldset> 
             <legend> Endereço </legend>
           <!--  <label class="md">CEP*  <input type="text" name="cep_paciente"  id="cep" size="18" value="<?php echo $dados_pac["CEP"]; ?>" onblur="getEndereco()" required/></label> -->
           <label class="md">CEP*  <input type="text" name="cep_paciente"  id="cep" size="18" maxlength="9" placeholder="Somente números" value="<?php echo $dados_pac["CEP"]; ?>" onblur="getEndereco()" required/></label>
             <label class="md">Cód. Logradouro*<select name="logr" class="required" required>
                                    <option value="">Selecione</option>
                                <?php 
                                    //limpa variavel que mantem os dados digitados
                                    unset($_SESSION["dados_pac"]);
                                    require("conecta.php");
                                    $sql = "SELECT * FROM tblogr";
                                    $sql .=  " ORDER BY logra";
                                    
                                    $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select cidade'));
                                    if (mysqli_num_rows($qry) > 0){
                                        while ($dados = mysqli_fetch_array($qry)){
                                            if ($dados_pac["cdlogr"] == $dados["cdlogr"])
                                                echo '<option value="'.$dados["cdlogr"].'" selected="selected">'.$dados["logra"].' - '.$dados["cdlogr"].'</option>';	
                                            else
                                                echo '<option value="'.$dados["cdlogr"].'">'.$dados["logra"].' - '.$dados["cdlogr"].'</option>';		
                                        }
                                    } 
                                    mysqli_close();
                                    mysqli_free_result($qry);
                                ?>
                                        
                                </select> </label>	                          
		   <!--   <label class="gr ">Logradouro *   <input type="text" name="log_paciente" id="logradouro" class="required" size="40" value="<?php echo $dados_pac["Logradouro"]; ?>" /></label>  -->
            <label class="gr">Logradouro *   <input type="text" name="log_paciente" id="logradouro" class="required" size="40" value="<?php echo $dados_pac["Logradouro"]; ?>" required="required" /></label>
		      <label class="pq ">N&uacute;mero * <input type="text" name="num_paciente" id="num" class="required digits" maxlength="6" required="required" size="8" value="<?php echo $dados_pac["Numero"]; ?>" /></label>
		      <label>Complemento  <input type="text" name="compl_paciente" id="compl" size="18" value="<?php echo $dados_pac["Compl"]; ?>" /></label>
              
             <!-- <label > Cidade * <input type="text" name="cidade" value="" id="cidade" class="" required placeholder="Cidade" maxlength="100" /> </label> -->
              
          	 <label >Cidade *  <select name="cidade" id="cidade" class="required">
                  		<option value="">Selecione</option>
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
						
						$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select cidade'));
						if (mysqli_num_rows($qry) > 0){
							while ($dados = mysqli_fetch_array($qry)){
								if ($dados_pac["CdPref"] == $dados["CdPref"])
									echo '<option value="'.$dados["CdPref"].'" selected="selected">'.$dados["NmCidade"].'</option>';	
								else
									echo '<option value="'.$dados["CdPref"].'">'.$dados["NmCidade"].'</option>';
							}
						} 
						mysqli_close();
						mysqli_free_result($qry);
					?>
                            
                    </select> </label>
          	  <label >Bairro *  <select name="bairro" id="bairro" class="required">               
                            <option value="">Selecione uma cidade primeiro</option>
                    <?php
							if ($dados_pac["CdPref"] != ""){
									require("conecta.php");
									$sql  = "SELECT CdBairro, NmBairro FROM tbbairro WHERE CdPref=".$dados_pac["CdPref"];
									$sql .= " ORDER BY NmBairro";
									
									$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select bairro'));
									if (mysqli_num_rows($qry) > 0){
										while ($dados = mysqli_fetch_array($qry)){
											if ($dados_pac["CdBairro"] == $dados["CdBairro"])
												echo '<option value="'.$dados["CdBairro"].'" selected="selected">'.$dados["NmBairro"].'</option>';	
											else
												echo '<option value="'.$dados["CdBairro"].'">'.$dados["NmBairro"].'</option>';
										}
									} 
									mysqli_close();
									mysqli_free_result($qry);
							}
					?>
                    </select></label>
                    <br />
                  <!--  <label> Bairro * <input type="text" name="bairro" value="" id="bairro" class="" required placeholder="Bairro" maxlength="100" /> </label> -->
                   &nbsp;&nbsp;
                   <?php 
						/*if($_SESSION["CdTpUsuario"] == 7)
							$habilitado = 'disabled="disabled"';
						else
							$habilitado = '';	
			  		*/?>
                   <?php //if($Acao != "del"){ ?>
                   			<!--input name="btncadbairro" id="btncadbairro" type="button" value="Cadastrar Bairro"  <?php echo $habilitado; ?> /-->
                   <?php //} ?>        
          <!--    <label class="pq"> Estado * <input type="text" name="estado" value="" id="estado" class="" required placeholder="Estado" maxlength="100" readonly /> </label>  -->
		      
		      <label class="gr">Refer&ecirc;ncia Moradia  <input type="text" name="referencia" id="referencia" size="50" value="<?php echo $dados_pac["Referencia"]; ?>" /></label>
          	 	<!-- <label>Unidade de Saude*: <select name="und_saude" id="und_saude" class="required">               
                            <option value="">Selecione uma unidade de saude primeiro</option>
                    <?php 
							/*if ($dados_pac["CdPref"] != ""){
									require("conecta.php");
									$sql  = "SELECT cdupa, nmupa FROM tbupa WHERE cdpref=".$dados_pac["CdPref"];
									$sql .= " ORDER BY nmupa";
									
									$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select bairro'));
									if (mysqli_num_rows($qry) > 0){
										while ($dados = mysqli_fetch_array($qry)){
											if ($dados_pac["cdupa"] == $dados["cdupa"])
												echo '<option value="'.$dados["cdupa"].'" selected="selected">'.$dados["nmupa"].'</option>';	
											else
												echo '<option value="'.$dados["cdupa"].'">'.$dados["nmupa"].'</option>';
										}
									} 
									mysqli_close();
									mysqli_free_result($qry);
							}*/
					?>
                    </select></label>--> 
                   &nbsp;&nbsp;
                   
                   </fieldset> 
              
      <div id="btns">
          <input type="submit" value="<?php echo $btnAcao ?>"  name="btn" id="btn"  />&nbsp;&nbsp;
         <?php if($acao == "edit" || $acao == "del"){ ?>
                    <input type="button" value="Cancelar" onclick="window.location.href='index.php?i=1'" />
         <?php } ?>           
          <input type="hidden" name="acao" value="<?php echo $acao; ?>" />
        </div>
    </div>
    </form>
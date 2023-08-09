<?php

function pacote($CdEspecProc, $pacote, $db)
{
	$user = $_SESSION['CdUsuario'];
	$data = date('Y-m-d');

	$sql_list = "	SELECT	cdespec_pact
					FROM	tbespecproc_pact
					WHERE	cdespec = $CdEspecProc
					AND 	status  = 1";

	$qry = mysqli_query($db, $sql_list);

	if (mysqli_num_rows($qry) > 0) {

		while ($row = mysqli_fetch_assoc($qry)) {
			$array_pact[] = $row['cdespec_pact'];
		}

		$diff1 	= array_diff($pacote, $array_pact);
		$diff2 	= array_diff($array_pact, $pacote);
		$diff 	= array_merge($diff1, $diff2);

		for ($contador = 0; $contador < count($diff); $contador++) {

			$sql_list = "	SELECT	cdespec_pact, status
							FROM	tbespecproc_pact
							WHERE	cdespec_pact = $diff[$contador]
							AND		cdespec		 = $CdEspecProc";

			$qry = mysqli_query($db, $sql_list);

			if (mysqli_num_rows($qry) > 0) {

				$verifica_pact = mysqli_fetch_array($qry);
				if ($verifica_pact['status'] == 1) {
					$status = 0;
				} else {
					$status = 1;
				}

				$sql_status = "	UPDATE 	tbespecproc_pact  
								SET		status  = $status
								WHERE	cdespec_pact = $diff[$contador]
								AND 	cdespec		 = $CdEspecProc";

				$qry = mysqli_query($db, $sql_status) or die('Erro ao invativar status da tabela pacote! - Linha 48');
			} else {
				$sql = "INSERT tbespecproc_pact (cdespec, cdespec_pact, status, userinc, datainc) VALUES ('$CdEspecProc', '$diff[$contador]', 1, '$user', '$data')";

				$qry = mysqli_query($db, $sql) or die('Erro ao inserir procedimentos da tabela pacote! - Linha 52');
			}
		}
	} else {
		for ($contador = 0; $contador < count($pacote); $contador++) {
			$sql = "INSERT tbespecproc_pact (cdespec, cdespec_pact, status, userinc, datainc) VALUES ('$CdEspecProc', '$pacote[$contador]', 1, '$user', '$data')";

			$qry = mysqli_query($db, $sql) or die('Erro ao inserir procedimentos da tabela pacote! - Linha 59');
		}
	}
}

require("../conecta.php");

define("DIRECT_ACCESS", true);

//recebe as variaveis do formulario
$CdEspecProc        = $_POST["cdespecproc"];
$NmEspecProc        = $_POST["nm_especproc"];
$CdProcedimento     = $_POST["cd_procedimento"];
$desc_sus		    = $_POST["desc_sus"];
$ppi			    = $_POST["ppi"];
$bpa			    = $_POST["bpa"];
$cdespecialidade    = $_POST["cdespecialidade"];
$cdgrupoproc	    = $_POST["cdgrupoproc"];
$CdForma	        = $_POST["CdForma"];
$nmpreparo		    = $_POST["nmpreparo"];
$cid			    = $_POST["cid"];
$cdservico		    = $_POST["servico"];
$cdclass		    = $_POST["class"];
$principal		    = $_POST["filiacao"];
$quemAgendar 	    = $_POST["quemAgendar"];
$cdsus 			    = $_POST["cdsus"];
$status 		    = $_POST["status"];
$preconsulta		= $_POST['preconsulta'];
$periodo			= $_POST['periodo'];

$pacote				= $_POST['pacote'];

/// data selecionada pelo usuário como periodo de alteração dos agendamentos
$dataini = $_POST["dataini"];
$datafim = $_POST["datafim"];

/// data do dia atual
$dataRegistrada = date("Y-m-d H:i:s");

$Valor 			   = $_POST["valor"];
$valorm			   = $_POST["valorm"];
$valorsus		   = $_POST["valorsus"];
$acao      		   = $_POST["acao"];

$codigoEspecProcSQL = $CdEspecProc;
$codigoEspecProcSQL = (int)$codigoEspecProcSQL;

/////////////////////////////////// SELECT`S ////////////////////////////////////////////////

/// Busca a licitacao vigente

// $sqlBuscaLctVigente = "	SELECT 		fl.cdlicitacao 
// 						FROM 		tblctfornecedor_licitacao fl
// 						INNER JOIN  tblctespeclicitacao lel ON fl.cdlicitacao = lel.cdlicitacao 
// 						INNER JOIN  tbespecproc ep ON ep.CdEspecProc = lel.cdespec
// 						WHERE 		fl.`status` = '1'
// 						AND 		fl.datainicio <= '$dataRegistrada'
// 						AND 		fl.datafim >= '$dataRegistrada'
// 						ORDER BY 	fl.cdlicitacao DESC LIMIT 1";

$sqlBuscaLctVigente = "	SELECT
							fl.cdlicitacao
						FROM
							tblctlicitacao fl
						WHERE
							fl.`status` = '1'
						AND fl.dtinicio <= '$dataRegistrada'
						AND fl.dtfim >= '$dataRegistrada'
						ORDER BY
							fl.cdlicitacao DESC
						LIMIT 1";

$resultSQLVigente = mysqli_query($db, $sqlBuscaLctVigente) or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 65');
$credenciadoVigente = mysqli_fetch_array($resultSQLVigente) or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 66');

/// Converte a variavel cdlicitacao para tipo de variavel numero inteiro
$codigoLicitacao = $credenciadoVigente['cdlicitacao'];
$codigoLicitacao = (int)$codigoLicitacao;

/// Busca pela Espec na tabela da atual licitação vigente
$sqlBuscaEspecLct = "	SELECT 
							* 
						FROM
							tblctespeclicitacao lel
						INNER JOIN
							tbespecproc ep ON ep.CdEspecProc = lel.cdespec
						WHERE
							lel.cdlicitacao = $codigoLicitacao
						AND
							ep.cdsus = '$cdsus'
						AND 
							lel.`status` = '1'";

if ($acao == "n") {

	/////////////////////////////////// INSERT`S ////////////////////////////////////////////////

	/// Insert Dados na EspecProc
	$sql = "INSERT INTO 
					tbespecproc (NmEspecProc,   	CdProcedimento,
								UserAlt,        	Status,
								valor,          	valorsus,
								cdsus,          	desc_sus,
								cdespecialidade,	ppi,
								bpa,            	cdgrupoproc,
								nmpreparo,      	cid,
								cdservico,      	cdclass,
								valorm,         	principal,       
								quemAgendar,    	cdForma,
								pre_sessao, 		pre_sessao_validade)
			VALUES(	'$NmEspecProc',       	 $CdProcedimento,
					$_SESSION[CdUsuario], 	'$status',
					'$Valor',             	'$valorsus',
					'$cdsus',             	'$desc_sus',
					'$cdespecialidade',   	'$ppi',
					'$bpa',               	'$cdgrupoproc',
					'$nmpreparo',         	'$cid',
					'$cdservico',         	'$cdclass',
					'$valorm',			  	'$principal',         
					'$quemAgendar',       	'$CdForma',
					'$preconsulta',			'$periodo')";

	$qry = mysqli_query($db, $sql)
		or die('Especificação, regn_especproc:insert especificacao - Linha 114' . mysqli_error($db) . "  $sql  ");

	$sqlBuscaCdEspecProc = "SELECT 	CdEspecProc
							FROM 	tbespecproc
							WHERE 	cdsus = '$cdsus'
							AND 	NmEspecProc LIKE '$NmEspecProc'
							ORDER BY CdEspecProc DESC LIMIT 1";

	$resultadoCdEspecProc = mysqli_query($db, $sqlBuscaCdEspecProc) or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 122');
	$CdEspecProcSql = mysqli_fetch_array($resultadoCdEspecProc) or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 123');

	$CdEspecProcLct = $CdEspecProcSql['CdEspecProc'];
	$CdEspecProcLct = (int)$CdEspecProcLct;

	/// Insert Dados na LctEspecLicitacao
	$sqlEspecLicitacao = "INSERT INTO	tblctespeclicitacao (cdespec, cdlicitacao, valorlct, status)
					 	  VALUES							('$CdEspecProcLct', '$codigoLicitacao', '$Valor', '$status')" or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 130');

	$qry5 = mysqli_query($db, $sqlEspecLicitacao)
		or die('Licitação, regn_especproc:insert EspecLicitação - Linha 119');

	/*
	/// Busca pelo contrato
	$selectContrato = " SELECT DISTINCT
					  		lct.cdlicitacao,
					  		c.CdContrato,
					  		c.CdForn
						FROM
						   	tbcontrato c
						INNER JOIN 
						   	tbcontratoespec ce ON c.CdContrato = ce.CdContrato
						INNER JOIN 
						   	tblctfornecedor_licitacao fl ON c.CdForn = fl.cdforn
						INNER JOIN 
						   	tblctlicitacao lct ON fl.cdlicitacao = lct.cdlicitacao
						WHERE
						   	lct.cdlicitacao = $codigoLicitacao
						AND 
						   	lct.`status` = '1'
						ORDER BY 
							c.CdContrato DESC 
						LIMIT 1
				   ";

	$resultSQLContrato = mysqli_query($db, $selectContrato);
	$selectContrato = mysqli_fetch_array($resultSQLContrato);

	/// Convertendo a variavel CdContrato para tipo de variavel numero inteiro
	$CdContrato = $selectContrato['CdContrato'];
	$CdContrato = (int)$CdContrato;

	// $Valor = $Valor + ($Valor * 0.03);

	/// Insert Dados na ContratoEspec
	$sqlLicitacao = "	INSERT INTO tbcontratoespec 	
									(	CdEspecProc, 			CdContrato,
									    Status,	   	  			DataUltimaAlt,
									    CdUsuario, 	  			valor_ctr,
									    valor_mun, 	  			qts)
					 	VALUES		(	'$CdEspecProcLct', 		'$CdContrato',
										'$status',  			null,
										'$_SESSION[CdUsuario]',	'$valorm',
										'$Valor', 				null)";

	$qry2 = mysqli_query($db, $sqlLicitacao)
		or die('Licitação, regn_especproc:insert Licitação - Linha 178');
	*/

	pacote($CdEspecProcLct, $pacote, $db);
} else {
	$CdEspecProc = (int)$CdEspecProc;

	$codigoEspecProcSQL = $CdEspecProc;
	$codigoEspecProcSQL = (int)$codigoEspecProcSQL;

	if ($acao == "e") {
		/// ALTERA OS DADOS DA ESPEC PROC ////////////////////////////////////////////////////////////////////
		$sql = "UPDATE 	tbespecproc
				SET 	NmEspecProc      	= '$NmEspecProc',	
						CdProcedimento     	= $CdProcedimento,
						UserAlt 	       	= $_SESSION[CdUsuario],
						Status		       	= '$status',
						valor		       	= '$Valor',
						cdsus		       	= '$cdsus',
						valorm		       	= '$valorm',
						valorsus		   	= '$valorsus',
						desc_sus	       	= '$desc_sus',
						ppi		  	       	= '$ppi',
						bpa		           	= '$bpa',
						cdgrupoproc		   	= '$cdgrupoproc',
						cdespecialidade    	= '$cdespecialidade',
						nmpreparo		   	= '$nmpreparo',
						cid 			   	= '$cid',
						cdservico          	= '$cdservico',
						cdclass            	= '$cdclass',
						principal          	= '$principal',
						quemAgendar        	= '$quemAgendar',
						DtAlt  		        = NOW(),
						cdForma            	= '$CdForma',
						pre_sessao			= '$preconsulta',
						pre_sessao_validade	= '$periodo'
				WHERE 	CdEspecProc    		= $codigoEspecProcSQL";

		/// ATUALIZA VALOR DAS AGENDAS RETROATIVAS DURANTE O PERIODO VIGENTE SELECIONADO PELO USUARIO
		$sqlAtualizaAgendasRetro = "UPDATE
										tbsolcons sc
									INNER JOIN 
										tbagendacons ac ON ac.CdSolCons = sc.CdSolCons
									INNER JOIN 
										tbespecproc sp ON sp.CdEspecProc = sc.CdEspecProc
									INNER JOIN 
										tblctfornecedor_licitacao fl ON fl.cdforn = ac.CdForn
									SET
										ac.valor 	 = '$Valor',
										ac.valor_sus = '$valorsus',
										ac.valormed  = '$valorm',
										ac.UserAlt 	 = $_SESSION[CdUsuario],
										ac.DtAlt 	 = '$dataRegistrada'
									WHERE
										sp.CdEspecProc = $codigoEspecProcSQL
									AND
										fl.cdlicitacao = $codigoLicitacao
									AND
										((ac.`Status`  = 1 AND sc.`Status` = 1) OR (ac.`Status` = 2 AND sc.`Status` = 1))
									AND
										ac.DtAgCons BETWEEN	'$dataini' AND '$datafim'";

		// $Valor = $Valor + ($Valor * 0.03);
		$dataRegistrada = date("Y-m-d");

		/// ATUALIZA VALORES DENTRO DA CONTRATO ESPEC PARA FORNECEDORES INTERNOS E EXTERNOS
		//Externos
		$sqlValorContEspecE = "	UPDATE
									tbcontratoespec ce 
								INNER JOIN 
									tbcontrato c ON c.CdContrato = ce.CdContrato
								INNER JOIN 
									tblctfornecedor_licitacao fl ON c.CdForn = fl.cdforn
								INNER JOIN 
									tblctfornecedor_licitacaoespec fle ON fle.CdFornlct = fl.CdFornlct
																		AND fle.cdespec = ce.CdEspecProc
								INNER JOIN 
									tblctlicitacao lct ON fl.cdlicitacao = lct.cdlicitacao
								INNER JOIN 
									tblctespeclicitacao lel ON lct.cdlicitacao = lel.cdlicitacao AND ce.CdEspecProc = lel.cdespec
								INNER JOIN 
									tbespecproc ep ON ep.CdEspecProc = lel.cdespec
								INNER JOIN tbfornecedor f ON f.cdforn = c.cdforn
								INNER JOIN tbfornespec fe ON fe.cdforn = f.cdforn
								SET
									ce.valor_ctr 		= '$Valor',
									ce.valor_mun 		= $Valor,
									ce.CdUsuario 		= $_SESSION[CdUsuario],
									ce.DataUltimaAlt 	= '$dataRegistrada',
									lel.valorlct 		= '$Valor',
									fle.valorf 			= '$Valor',
									fe.valorf 			= '$Valor',
									fe.valorc 			= $Valor,
									fle.valorf 			= $Valor
								WHERE
									lct.cdlicitacao = $codigoLicitacao
								AND
									ce.`Status` = 1
								AND
									ep.CdEspecProc = $codigoEspecProcSQL
									AND f.sit = 'E'
								";

		$sqlValorLct = "	UPDATE tblctespeclicitacao lel
									SET
										lel.valorlct 		= '$Valor'
									WHERE
										lel.cdlicitacao = $codigoLicitacao
									AND lel.cdespec = $codigoEspecProcSQL
								";

		$sqlValorFLct = "	UPDATE tblctfornecedor_licitacao fl 
									INNER JOIN tblctfornecedor_licitacaoespec fle ON fle.CdFornlct = fl.CdFornlct
									SET
										fle.valorf = '$Valor'
									WHERE
									fl.cdlicitacao = $codigoLicitacao
									AND fle.cdespec = $codigoEspecProcSQL
								";

		// die(var_dump($sqlValorContEspecE));

		/// UPDATE DO PROCEDIMENTO NA ESPEC PROC
		$qry = mysqli_query($db, $sql)
			or die('Especificação, regn_especproc:update especificacao - Linha: 280');

		/// UPDATE DE ATAULIZAÇÃO DE VALORES DOS AGENDAMENTOS RETROATIVOS 
		if (!(empty($dataini)) && !(empty($datafim))) {
			$qry2 = mysqli_query($db, $sqlAtualizaAgendasRetro)
				or die('Atualização de agendas Retroativas, regn_especproc:update Atualização de agendamentos Retroativas - Linha: 285');
		}

		/// UPDATE DO PROCEDIMENTO DENTRO DA CONTRATO ESPEC
		$qry3 = mysqli_query($db, $sqlValorContEspecE)
			or die('Valor de Contrato Especificação, regn_especproc:update Valor de Contrato Especificação - Linha: 290');

		/// UPDATE DO PROCEDIMENTO DENTRO DA CONTRATO ESPEC
		$qry3 = mysqli_query($db, $sqlValorLct)
			or die('Valor de Espec Licitação, regn_especproc:update Valor de Contrato Especificação - Linha: 376');

		/// UPDATE DO PROCEDIMENTO DENTRO DA CONTRATO ESPEC
		$qry3 = mysqli_query($db, $sqlValorFLct)
			or die('Valor de Fornecedor Licitação, regn_especproc:update Valor de Contrato Especificação - Linha: 376');

		pacote($codigoEspecProcSQL, $pacote, $db);
	} else {
		if ($acao == "d") {
			//excluir	

			//verifica se existe algum especificacao vinculado ao fornecedor
			$qry = mysqli_query($db, "SELECT CdEspec FROM tbfornespec WHERE CdEspec = $CdEspecProc")
				or die('Erro');

			if (mysqli_num_rows($qry) == 0) {
				// $sql = "DELETE FROM tbespecproc WHERE CdEspecProc=$CdEspecProc";
				$sql = "UPDATE tbespecproc SET status = 0 WHERE CdEspecProc = $CdEspecProc";
				$qry = mysqli_query($db, $sql)
					or die('Especificação, regn_especproc:delete especificacao');
			}
		}
	}
}

echo json_encode(array('success' => 'success'));
mysqli_close($db);
mysqli_free_result($qry);

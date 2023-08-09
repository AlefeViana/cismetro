<?php
require("../conecta.php");

define("DIRECT_ACCESS", true);

//recebe as variaveis do formulario
$CdEspecProc       = $_POST["cdespecproc"];
$NmEspecProc       = $_POST["nm_especproc"];
$CdProcedimento    = $_POST["cd_procedimento"];
$desc_sus		   = $_POST["desc_sus"];
$ppi			   = $_POST["ppi"];
$bpa			   = $_POST["bpa"];
$cdespecialidade   = $_POST["cdespecialidade"];
$cdgrupoproc	   = $_POST["cdgrupoproc"];
$nmpreparo		   = $_POST["nmpreparo"];
$cid			   = $_POST["cid"];
$cdservico		   = $_POST["servico"];
$cdclass		   = $_POST["class"];
$principal		   = $_POST["filiacao"];
$quemAgendar 	   = $_POST["quemAgendar"];
$cdsus 			   = $_POST["cdsus"];
$status 		   = $_POST["status"];

$valorOld		   = $_POST["valorOld"];

/// data selecionada pelo usuário como periodo de alteração dos agendamentos
$dataini = $_POST["dataini"];
$datafim = $_POST["datafim"];

/// data do dia atual
$dataRegistrada = date("Y-m-d H:i:s");
$dataInicial	= date("Y-m-d");

$Valor 		= $_POST["valor"];
$valorm		= $_POST["valor"];
$valorsus	= $_POST["valorsus"];
$acao  		= $_POST["acao"];

$codigoEspecProcSQL = $CdEspecProc;
$codigoEspecProcSQL = (int)$codigoEspecProcSQL;

/////////////////////////////////// SELECT`S ////////////////////////////////////////////////

/// Busca a licitacao vigente
$sqlBuscaLctVigente = "	SELECT 		fl.cdlicitacao 
						FROM 		tblctfornecedor_licitacao fl
						INNER JOIN  tblctespeclicitacao lel ON fl.cdlicitacao = lel.cdlicitacao 
						INNER JOIN  tbespecproc ep ON ep.CdEspecProc = lel.cdespec
						WHERE 		fl.`status` = '1'
						AND 		fl.datainicio <= '$dataRegistrada'
						AND 		fl.datafim >= '$dataRegistrada'
						ORDER BY 	fl.cdlicitacao DESC LIMIT 1";

$resultSQLVigente = mysqli_query($db, $sqlBuscaLctVigente);
$credenciadoVigente = mysqli_fetch_array($resultSQLVigente) or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 55');

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
									tbespecproc (NmEspecProc,    CdProcedimento,
												UserAlt,         Status,
												valor,           valorsus,
												cdsus,           desc_sus,
												cdespecialidade, ppi,
												bpa,             cdgrupoproc,
												nmpreparo,       cid,
												cdservico,       cdclass,
												valorm,          principal,       
												quemAgendar)
							VALUES('$NmEspecProc',        $CdProcedimento,
									$_SESSION[CdUsuario], '$status',
									'$Valor',             '$valorsus',
									'$cdsus',             '$desc_sus',
									'$cdespecialidade',   '$ppi',
									'$bpa',               '$cdgrupoproc',
									'$nmpreparo',         '$cid',
									'$cdservico',         '$cdclass',
									'$valorm',			  '$principal',         
									'$quemAgendar')";

	$qry = mysqli_query($db, $sql)
		or die('Especificação, regn_especproc:insert especificacao - Linha 103');

	$sqlBuscaCdEspecProc = "SELECT CdEspecProc
							FROM tbespecproc
							WHERE cdsus = '$cdsus'
							AND NmEspecProc LIKE '$NmEspecProc'
							ORDER BY CdEspecProc DESC LIMIT 1";

	$resultadoCdEspecProc = mysqli_query($db, $sqlBuscaCdEspecProc);
	$CdEspecProcSql = mysqli_fetch_array($resultadoCdEspecProc) or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 112');

	$CdEspecProcLct = $CdEspecProcSql['CdEspecProc'];
	$CdEspecProcLct = (int)$CdEspecProcLct;

	/// Insert Dados na LctEspecLicitacao
	$sqlEspecLicitacao = "INSERT INTO	tblctespeclicitacao (cdespec, cdlicitacao, valorlct, status)
					 	  VALUES							('$CdEspecProcLct', '$codigoLicitacao', '$Valor', '$status')" or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 119');

	$qry5 = mysqli_query($db, $sqlEspecLicitacao)
		or die('Licitação, regn_especproc:insert EspecLicitação - Linha 118');

	/// Busca pelo contrato
	$selectContrato = " SELECT DISTINCT
					   				lct.cdlicitacao,
					   				c.CdContrato,
					   				c.CdForn,
					   				c.DtValidadef
				   		FROM		tbcontrato c
				   		INNER JOIN 	tbcontratoespec ce ON c.CdContrato = ce.CdContrato
				   		INNER JOIN 	tblctfornecedor_licitacao fl ON c.CdForn = fl.cdforn
				   		INNER JOIN 	tblctlicitacao lct ON fl.cdlicitacao = lct.cdlicitacao
				   		WHERE	lct.cdlicitacao = $codigoLicitacao
				   		AND  	lct.`status` 	= '1'
						AND 	c.`Status` 		= 1
						ORDER BY c.CdContrato DESC LIMIT 1
				   ";

	$resultSQLContrato = mysqli_query($db, $selectContrato);
	$selectContrato = mysqli_fetch_array($resultSQLContrato) or die('Erro de busca do Contrato! - Linha 141');

	/// Convertendo a variavel CdContrato para tipo de variavel numero inteiro
	$CdContrato = $selectContrato['CdContrato'];
	$CdContrato = (int)$CdContrato;

	$dataFinal = $selectContrato['DtValidadef'];

	$tresPorcento = $Valor + ($Valor * 0.03);

	/// Insert Dados na ContratoEspec
	$sqlLicitacao = "INSERT INTO 
					   tbcontratoespec 	(CdEspecProc, 			CdContrato,
									     Status,	   	  		DataUltimaAlt,
									     CdUsuario, 	  		valor_ctr,
									     valor_mun, 	  		qts)
					 VALUES				('$CdEspecProcLct', 	'$CdContrato',
										 '$status',  			null,
										 '$_SESSION[CdUsuario]','$Valor',
										 '$tresPorcento', 		null)";

	$qry2 = mysqli_query($db, $sqlLicitacao)
		or die('Licitação, regn_especproc:insert Licitação - Linha 163');

	$sqlLog = "	INSERT INTO tblogespec (`CdUsuario`,`Valor_Antigo`,`Valor_Novo`,`DtInc`,`HrInc`,`CdEspecProc`,`DtAgIni`,`DtAgFim`,`Tipo`,`Situacao`)
				VALUES ('$_SESSION[CdUsuario]',0,'$Valor',CURDATE(), CURTIME(), '$CdEspecProcLct', '$dataInicial', '0000-00-00','I','Criado')";

	$query = mysqli_query($db, $sqlLog) or die(mysqli_error($db));
	$CdLogEspec = mysqli_insert_id($db);

	echo json_encode($CdLogEspec);
} else {
	$CdEspecProc = (int)$CdEspecProc;

	$codigoEspecProcSQL = $CdEspecProc;
	$codigoEspecProcSQL = (int)$codigoEspecProcSQL;

	if ($acao == "e") {
		/// ALTERA OS DADOS DA ESPEC PROC ////////////////////////////////////////////////////////////////////
		$sql = "UPDATE tbespecproc
						SET NmEspecProc      = '$NmEspecProc',	
							CdProcedimento   = $CdProcedimento,
							UserAlt 	     = $_SESSION[CdUsuario],
							Status		     = '$status',
							valor		     = '$Valor',
							cdsus		     = '$cdsus',
							valorsus		 = '$valorsus',
							desc_sus	     = '$desc_sus',
							ppi		  	     = '$ppi',
							bpa		         = '$bpa',
							cdgrupoproc		 = '$cdgrupoproc',
							cdespecialidade  = '$cdespecialidade',
							nmpreparo		 = '$nmpreparo',
							cid 			 = '$cid',
							cdservico        = '$cdservico',
							cdclass          = '$cdclass',
							valorm           = '$valorm',
							principal        = '$principal',
							quemAgendar      = '$quemAgendar',
							DtAlt  		     = NOW()
					 	WHERE CdEspecProc    = $codigoEspecProcSQL";

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
										ac.valormed	 = '$Valor',
										ac.valor_sus = '$valorsus',
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

		$dataRegistrada = date("Y-m-d");

		/// ATUALIZA VALORES DENTRO DA CONTRATO ESPEC PARA FORNECEDORES INTERNOS E EXTERNOS
		//Externos
		$sqlValorContEspecE = "UPDATE
											tbcontratoespec ce 
										INNER JOIN 
											tbcontrato c ON c.CdContrato = ce.CdContrato
										INNER JOIN 
											tblctfornecedor_licitacao fl ON fl.cdforn = c.CdForn
										INNER JOIN 
											tblctfornecedor_licitacaoespec fle  ON fle.CdFornlct = fl.CdFornlct 
																				AND fle.cdespec = ce.CdEspecProc
										INNER JOIN 
											tblctlicitacao lct ON lct.cdlicitacao = fl.cdlicitacao
										INNER JOIN 
											tblctespeclicitacao lel ON lct.cdlicitacao = lel.cdlicitacao AND ce.CdEspecProc = lel.cdespec
										INNER JOIN 
											tbespecproc ep ON ep.CdEspecProc = lel.cdespec
										INNER JOIN tbfornecedor f ON f.cdforn = c.cdforn
										INNER JOIN tbfornespec fe ON fe.cdforn = f.cdforn
										SET
											ce.valor_ctr = '$Valor',
											ce.valor_mun = '$Valor',
											ce.CdUsuario = $_SESSION[CdUsuario],
											ce.DataUltimaAlt = '$dataRegistrada',
											lel.valorlct = '$Valor',
											fe.valorf = '$Valor',
											fe.valorc = '$Valor'
										WHERE
											lct.cdlicitacao = $codigoLicitacao
										AND
											ce.`Status` = 1
										AND
											ep.CdEspecProc = $codigoEspecProcSQL
											AND f.sit = 'E'
										";

		/// UPDATE DO PROCEDIMENTO NA ESPEC PROC
		$qry = mysqli_query($db, $sql)
			or die('Especificação, regn_especproc:update especificacao - Linha: 290');

		/// UPDATE DE ATAULIZAÇÃO DE VALORES DOS AGENDAMENTOS RETROATIVOS 

		if (!empty($dataini) && !empty($datafim)) {
			$qry2 = mysqli_query($db, $sqlAtualizaAgendasRetro)
				or die('Atualização de agendas Retroativas, regn_especproc:update Atualização de agendamentos Retroativas - Linha: 272');
		}
		
		/// UPDATE DO PROCEDIMENTO DENTRO DA CONTRATO ESPEC
		$qry3 = mysqli_query($db, $sqlValorContEspecE)
			or die('Valor de Contrato Especificação, regn_especproc:update Valor de Contrato Especificação - Linha: 276');

		//Hora Medico
		$sqlValorHrMed = "	UPDATE	
								tbhrmedico hr
							SET
								hr.valor_hrmed = '$Valor'
							WHERE
								hr.`Status` = 1
							AND hr.DtHrMed BETWEEN '$dataini' AND '$datafim'
							AND
								hr.Cdespec = $codigoEspecProcSQL";

		/// UPDATE DO PROCEDIMENTO NA ESPEC PROC
		$qry = mysqli_query($db, $sqlValorHrMed)
			or die('Especificação, regn_especproc:update especificacao plantao - Linha: 291');

		/// Busca pelo contrato
		$selectContrato = " SELECT DISTINCT
								c.DtValidadef
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
							AND lct.`status` = '1'
							AND c.`Status` = 1
							ORDER BY c.CdContrato DESC LIMIT 1
							";

		$resultSQLContrato = mysqli_query($db, $selectContrato);
		$selectContrato = mysqli_fetch_array($resultSQLContrato) or die('Erro de busca do Contrato! - Linha 312');
		$dataFinal = $selectContrato['DtValidadef'];

		if (!empty($dataini) && !empty($datafim)) {
			$sql = "INSERT INTO tblogespec (`CdUsuario`,`Valor_Antigo`,`Valor_Novo`,`DtInc`,`HrInc`,`CdEspecProc`,`DtAgIni`,`DtAgFim`,`Tipo`,`Situacao`) 
					VALUES ('$_SESSION[CdUsuario]','$valorOld','$Valor',CURDATE(),CURTIME(),'$CdEspecProc', '$dataini', '$datafim','E','Editado')";
		} else {
			$sql = "INSERT INTO tblogespec (`CdUsuario`,`Valor_Antigo`,`Valor_Novo`,`DtInc`,`HrInc`,`CdEspecProc`,`DtAgIni`,`DtAgFim`,`Tipo`,`Situacao`) 
					VALUES ('$_SESSION[CdUsuario]','$valorOld','$Valor',CURDATE(),CURTIME(),'$CdEspecProc', '$dataInicial', '$dataFinal','E','Editado')";
		}

		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$CdLogEspec = mysqli_insert_id($db);

		echo json_encode($CdLogEspec);
	} else {
		if ($acao == "d") {
			//excluir	

			//verifica se existe algum especificacao vinculado ao fornecedor
			$qry = mysqli_query($db, "SELECT CdEspec FROM tbfornespec WHERE CdEspec=$CdEspecProc")
				or die('Erro');

			if (mysqli_num_rows($qry) == 0) {
				// $sql = "DELETE FROM tbespecproc WHERE CdEspecProc=$CdEspecProc";
				$sql = "UPDATE tbespecproc SET status = 0 WHERE CdEspecProc=$CdEspecProc";
				$qry = mysqli_query($db, $sql)
					or die('Especificação, regn_especproc:delete especificacao');

				$sql = "INSERT INTO tblogespec (`CdUsuario`,`Valor_Antigo`,`Valor_Novo`,`DtInc`,`HrInc`,`CdEspecProc`,`DtAgIni`,`DtAgFim`,`Tipo`,`Situacao`) 
				VALUES ('$_SESSION[CdUsuario]','0','0',CURDATE(),CURTIME(),'$CdEspecProc','0000-00-00','0000-00-00', 'D','Inativou')";

				$query = mysqli_query($db, $sqlLog) or die(mysqli_error($db));
				$CdLogEspec = mysqli_insert_id($db);

				echo json_encode($CdLogEspec);
			}
		}
	}
}
mysqli_close($db);
mysqli_free_result($qry);

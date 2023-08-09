<script type="text/javascript">
$(document).ready(function() {
    $("form").each(function() {
        $(this).find(':input').not(":button").removeClass().addClass(
        'form-control'); //<-- Should return all input elements in that specific form.
    });
    $('#cd_procedimento').select2();
    $('#cdespecialidade').select2();
    $('#cdgrupoproc').select2();
    $('#servico').select2();
    $('#class').select2();
    $("#commentForm").validate();
    $('#servico').change(function() {
        $('#class').load('admin/load_class.php?cdserv=' + $('#servico').val(), function(response,
            status, xhr) {
            if (status == "error") {
                var msg = "Sorry but there was an error: ";
                $("#error").html(msg + xhr.status + " " + xhr.statusText);
                alert(msg + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $("input[id=valor]").maskMoney({
        showSymbol: false,
        decimal: ",",
        thousands: ".",
        allowZero: true,
        defaultZero: true,
        allowNegative: true
    });
    $("input[id=valorsus]").maskMoney({
        showSymbol: false,
        decimal: ",",
        thousands: ".",
        allowZero: true,
        defaultZero: true,
        allowNegative: true
    });


});
jQuery(function($) {
    $("#csus").mask("99.99.99.999-9");

});
</script>

<script type="text/javascript">
function blocTexto(valor) {
    quant = 1000;
    total = valor.length;
    if (total <= quant) {
        resto = quant - total;
        document.getElementById('contcar').innerHTML = resto;
    } else {
        document.getElementById('nmpreparo').value = valor.substr(0, quant);
    }
}
</script>







<style type="text/css">
textarea {
    width: 300px;
    height: 200px;
}

#progreso {
    background: url(textarea.png) no-repeat;
    background-position: -300px 0px;
    width: 300px;
    height: 14px;
    text-align: center;
    color: #000000;
    font-size: 8pt;
    font-family: Arial;
    text-transform: uppercase;
}
</style>
<script type="text/javascript">
var max = 200;
var ancho = 250;

function progreso_tecla(obj) {
    var progreso = document.getElementById("progreso");
    if (obj.value.length < max) {
        progreso.style.backgroundColor = "#FFFFFF";
        progreso.style.backgroundImage = "url(textarea.png)";
        progreso.style.color = "#000000";
        var pos = ancho - parseInt((ancho * parseInt(obj.value.length)) / 250);
        progreso.style.backgroundPosition = "-" + pos + "px 0px";
    } else {
        progreso.style.backgroundColor = "#CC0000";
        progreso.style.backgroundImage = "url()";
        progreso.style.color = "#FFFFFF";
    }
    progreso.innerHTML = "(" + obj.value.length + " / " + max + ")";
}
</script>



<?php
  require_once("verifica.php");
  
  //funcao para tratar erro
  require("admin/function_trata_erro.php");

  require "../vendor/autoload.php";
  use Stringy\Stringy as S;
  
  //verifica se o usuario tem permissão para acessar a pagina
  if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2) 
  {
    echo '<script language="JavaScript" type="text/javascript"> 
      window.location.href="index.php";       
      </script>'; 
  } 
  
  $destino = "admin/regn_especproc.php";
  
  $Acao = $_GET["acao"];
  $CdEspecProc = $_GET["id"];
  
  //value do botao de submit do formulario
  $btnAcao = "Salvar";
  
  //carrega os dados do paciente
  if (is_numeric($CdEspecProc))
  {
      require("conecta.php");
      
      $sql = "SELECT *
          FROM tbespecproc
          WHERE CdEspecProc=".$CdEspecProc; 
      
      $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_especproc','frm_cadespecproc:select dados'));
      if (mysqli_num_rows($qry) == 1){
         $dados = mysqli_fetch_array($qry);
      }
      else{
        echo '<script language="JavaScript" type="text/javascript"> 
          alert("Especificação não encontrada!");
          window.location.href="index.php?i=4";       
            </script>'; 
      }
      //@mysqli_free_result($qry);
      //@mysqli_close();
      
      switch ($Acao)
      {
        case "edit": $destino .= "?acao=edit";
               $btnAcao = "Salvar";
               break;
        case "del" : $destino .= "?acao=del";
               $btnAcao = "Excluir";
               break;
      }
      
  }
  
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" id="commentForm">

            <div id="alert" class="alert alert-warning" role="alert"><strong>Atenção</strong> Os Campos com * devem ser
                preechidos obrigatóriamente </div>
            <fieldset>
                <h5> Dados do Procedimento </h5>
                <input type="hidden" name="cd_especproc" size="10" readonly
                    value="<?php if(isset($dados["CdEspecProc"])) echo $dados["CdEspecProc"]; else echo "Autom&aacute;tico"; ?>" />

                <label> C&oacute;digo SUS <input type="text" name="cdsus" id="csus" size="35"
                        value="<?php echo $dados["cdsus"]; ?>" /></label>

                <label> Filiação
                    <select name="principal">
                        <option value="0" <?php if($dados["principal"] == 0) echo 'selected="selected"'; ?>>Padrão
                        </option>
                        <option value="1" <?php if($dados["principal"] == 1) echo 'selected="selected"'; ?>>Principal
                        </option>
                    </select>
                </label>

                <label class="gr">Especifica&ccedil;&atilde;o: <input name="nm_especproc" type="text" class="required"
                        value="<?php echo $dados["NmEspecProc"];  ?>" size="50" maxlength="100" /></label>

                <label class="control-label" for="cd_procedimento">Tipo de Procedimento: </label>
                <select name="cd_procedimento" id="cd_procedimento" size="50" class="form-control select2-single">
                    <?php 
                require("conecta.php");
                $sql = "SELECT CdProcedimento, NmProcedimento FROM tbprocedimento WHERE Status='1' ";

                if ($_SESSION['cdgrusuario'] == 18) {
                  $sql .= " AND CdProcedimento = 8";
                }

                $sql .=  " ORDER BY NmProcedimento";
                
                $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_especproc','frm_cadespecproc:select tipo proc'));
                if (mysqli_num_rows($qry) > 0){
                    while ($l = mysqli_fetch_array($qry)){
                        if ($dados["CdProcedimento"] == $l["CdProcedimento"])
                            echo '<option value="'.$l["CdProcedimento"].'" selected="selected">'.(String)S::create($l["NmProcedimento"])->titleize(["de", "da", "do"]).'</option>';  
                        else
                            echo '<option value="'.$l["CdProcedimento"].'">'.(String)S::create($l["NmProcedimento"])->titleize(["de", "da", "do"]).'</option>';
                    }
                } 
                //mysqli_close();
                //mysqli_free_result($qry);
                //
            $dados["quemAgendar"]
          ?>
                </select>
                <label class="control-label" for="cdgrupoproc"> Grupo </label>
                <select name="cdgrupoproc" id="cdgrupoproc" class="required">
                    <option value=""> </option>
                    <?php 
            require("conecta.php");
            $sql = "SELECT cdgrupoproc, nmgrupoproc FROM tbgrupoproc ";
            $sql .=  " ORDER BY nmgrupoproc";
            
            $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_especproc','frm_cadespecproc:select tipo proc'));
            if (mysqli_num_rows($qry) > 0){
                while ($l = mysqli_fetch_array($qry)){
                    if ($dados["cdgrupoproc"] == $l["cdgrupoproc"])
                        echo '<option value="'.$l["cdgrupoproc"].'" selected="selected">'.(String)S::create($l["nmgrupoproc"])->titleize(["de", "da", "do"]).'</option>';  
                    else
                        echo '<option value="'.$l["cdgrupoproc"].'">'.(String)S::create($l["nmgrupoproc"])->titleize(["de", "da", "do"]).'</option>';
                }
            } 
            //mysqli_close();
            //mysqli_free_result($qry);
           ?>
                </select>

                <label>Quem pode Agendar:
                    <select name="quemAgendar" style='width:200px;'>
                        <option value="T" <?php echo($dados["quemAgendar"]=='T')?'selected':'';?>>Todos</option>
                        <option value="C" <?php echo($dados["quemAgendar"]=='C')?'selected':'';?>>Consórcio</option>
                        <option value="M" <?php echo($dados["quemAgendar"]=='M')?'selected':'';?>>Município</option>
                    </select>
                </label>

                <label>Valor <input type="text" name="valor" id="valor" size="28" class="required" data-valorold = "<?php echo  number_format((double)$dados["valor"], 2, ',', ' ');  ?>"
                        value="<?php echo  number_format((double)$dados["valor"], 2, ',', ' ');  ?>" /> </label>

                <label>Valor SUS <input type="text" name="valorsus" id="valorsus" size="28"
                        value="<?php echo  number_format((double)$dados["valorsus"], 2, ',', ' ');  ?>" /></label>

                <label> Situa&ccedil;&atilde;o
                    <select name="status">
                        <option value="1" <?php if($dados["Status"] == 1) echo 'selected="selected"'; ?>>Ativo</option>
                        <option value="2" <?php if($dados["Status"] == 2) echo 'selected="selected"'; ?>>Inativo
                        </option>
                    </select>
                </label>

                <label> Descri&ccedil;&atilde;o SUS <input name="desc_sus" type="text" id="desc_sus"
                        value="<?php echo $dados["desc_sus"]; ?>" maxlength="100" /></label>





                <label> PPI
                    <select name="ppi" class="required">
                        <option value=""> </option>
                        <option value="S" <?php if($dados["ppi"] == "S") echo 'selected="selected"'; ?>>Sim</option>
                        <option value="N" <?php if($dados["ppi"] == "N") echo 'selected="selected"'; ?>>Não</option>
                    </select>
                </label>
                <label> BPA
                    <select name="bpa" class="required">
                        <option value=""> </option>
                        <option value="C" <?php if($dados["bpa"] == "C") echo 'selected="selected"'; ?>>Consolidado
                        </option>
                        <option value="I" <?php if($dados["bpa"] == "I") echo 'selected="selected"'; ?>>Individualizado
                        </option>
                        <option value="N" <?php if($dados["bpa"] == "N") echo 'selected="selected"'; ?>>Não</option>
                    </select>
                </label>
                <label> CID
                    <input type="text" name="cid" value="<?php $dados["cid"]; ?>" />

                </label>
                <div class="row">
                    <div class="col-6">
                        <label class="gr" for='cdespecialidade'> Especialidade
                            <select name="cdespecialidade" id="cdespecialidade" class="required">
                                <option value=""> </option>
                                <?php 
                require("conecta.php");
                $sql = "SELECT cdespecialidade, nmespecialidade, cbo FROM tbespecialidade ";
                $sql .=  " ORDER BY nmespecialidade";
                
                $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_especproc','frm_cadespecproc:select tipo proc'));
                if (mysqli_num_rows($qry) > 0){
                    while ($l = mysqli_fetch_array($qry)){
                        if ($dados["cdespecialidade"] == $l["cdespecialidade"])
                            echo '<option value="'.$l["cdespecialidade"].'" selected="selected">'.(String)S::create($l["nmespecialidade"])->titleize(["de", "da", "do"]).' CBO:'.$l["cbo"].'</option>';  
                        else
                            echo '<option value="'.$l["cdespecialidade"].'">'.(String)S::create($l["nmespecialidade"])->titleize(["de", "da", "do"]).' CBO:'.$l["cbo"].'</option>';
                    }
                } 
                //mysqli_close();
                //mysqli_free_result($qry);
            ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="gr"> Serviço
                            <select name="servico" id="servico">
                                <option value=""> </option>
                                <?php 
              require("conecta.php");
              $sql = "SELECT tbservico.co_servico,tbservico.no_servico FROM tbservico ";
              $sql .=  " ORDER BY tbservico.co_servico";
              
              $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_especproc','frm_cadespecproc:select tipo proc'));
              if (mysqli_num_rows($qry) > 0){
                  while ($l = mysqli_fetch_array($qry)){
                      if ($dados["cdservico"] == $l["co_servico"])
                          echo '<option value="'.$l["co_servico"].'" selected="selected">'.$l["co_servico"].' - '.(String)S::create($l["no_servico"])->titleize(["de", "da", "do"]).'</option>'; 
                      else
                          echo '<option value="'.$l["co_servico"].'">'.$l["co_servico"].' - '.(String)S::create($l["no_servico"])->titleize(["de", "da", "do"]).'</option>';
                  }
              } 
              //mysqli_close();
              //mysqli_free_result($qry);
              ?>
                            </select>
                        </label>
                    </div>
                </div>


                <label for="class" class="control-label"> Classificação </label>
                <select name="class" id="class">
                    <option value=""> Selecione um serviço primeiro...</option>
                    <?php 
                    if ($dados["cdclass"] != ""){
                            require("conecta.php");
              $sql = "SELECT tbservico_classificacao.co_classificacao,tbservico_classificacao.no_classificacao FROM tbservico_classificacao"; 
              $sql .= " WHERE tbservico_classificacao.co_servico =".$dados["cdservico"];    
              $sql .=  " ORDER BY tbservico_classificacao.co_classificacao";
   
                            $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select bairro'));
                            if (mysqli_num_rows($qry) > 0){
                                while ($l = mysqli_fetch_array($qry)){
                                    if ($dados["cdclass"] == $l["co_classificacao"])
                                        echo '<option value="'.$l["co_classificacao"].'" selected="selected">'.$l["co_classificacao"].' - '.(String)S::create($l["no_classificacao"])->titleize(["de", "da", "do"]).'</option>'; 
                                    else
                                        echo '<option value="'.$l["co_classificacao"].'">'.$l["co_classificacao"].' - '.(String)S::create($l["no_classificacao"])->titleize(["de", "da", "do"]).'</option>';
                                }
                            } 
                            //mysqli_close();
                            //mysqli_free_result($qry);
                    }
            ?>
                </select><br><br>


                <label for='nmpreparo'>
                    <span id="contcar" style="font-size:12px;"> </span>
                    Preparo (1000 Caracteres )</label>
                <textarea name="nmpreparo" id='nmpreparo' class="form-control"
                    onkeyup="blocTexto(this.value)"><?php echo $dados['nmpreparo']?></textarea>

            </fieldset>
            <hr>
            <div id="btns">

                <button type="button" class='btn btn-lg btn-success gogo'
                    style="float:right;"><?php echo $btnAcao ?></button>

                <?php if($Acao == "edit" || $Acao == "del"){ ?>
                <input type="button" value="Cancelar" onclick="window.location.href='index.php?i=4'"
                    class="btn btn-lg btn-danger" style="float:right; margin-right:10px;" />
                <?php } ?>
                <!-- <input type="submit" class='btn btn-lg btn-success' style="float:right;" value=<?php echo $btnAcao ?> />
           <button type="submit"  class='btn btn-lg btn-success' style="float:right;"><?php echo $btnAcao ?></button> -->
                <input type="hidden" name="acao" id="acao" value="<?php echo $Acao; ?>" />
            </div>

        </form>
    </div>
</div>


<script>

function myFunction(CdLogEspec){
    window.open("http://cismetro.sitcon.com.br/cismetro/relatorio_proc.php?cdlogespec="+CdLogEspec);
}

$(document).ready(function() {
   $('.gogo').click(function() {
    if($('#valor').val() != $('#valor').data('valorold') && $('#acao').val() == 'edit'){
    Swal.fire({
    title: '<strong> Valor do Procedimento</strong>',
    icon: 'info',
    html:
    `<div class="container"><div class="row"> <p> O valor do procedimento foi alterado, informe o período que deve ser alterada as agendas! </p> </div> <div class="row"> <div class="col-6"> <label> Data Inicio </label> 
    <input type="date" class="form-control" name="datainicio"  id="datainicio" />
    </div> <div class-"col-6"> <label> Data Fim </label> <input type="date" class="form-control" name="datatermino" id="datatermino" /></div>
    <div class="row"> <p> Deseja infomar a hora dos agendamentos? </p><div class="col-6"> <label> Hora Inicio </label>
    <input type="time" class="form-control" id="horainicio" name="horainicio" /> </div> <div class="col-6">  <label> Hora Fim</label> <input type="time" 
    class="form-control" id="horafim" class="horafim" /> </div></div></div>
    `,
  showCloseButton: true,
  showCancelButton: true,
  focusConfirm: false,
  confirmButtonText:'Gravar',
  confirmButtonAriaLabel: 'Gravar!',
  cancelButtonText:
    'Cancelar',
  cancelButtonAriaLabel: 'Cancelar'
  }).then((result)=>{
  if(result.isConfirmed){
    dataIni = $('#datainicio').val();
    dataFim = $('#datatermino').val();
    horaIni = $('#horainicio').val();
    horaFim = $('#horafim').val();
    valor = $('#valor').data('valorold');
    $.ajax({
            url: 'admin/regn_especproc.php',
            method: 'POST',
            cache: false,
            data: {
                dados: $('#commentForm').serialize(),
                dataIni: dataIni,
                dataFim: dataFim,
                valorOld : valor,
                horaIni : horaIni,
                horaFim : horaFim,
                change:'change'
            },
            datatype: "json",
            error: function() {
                alert('Erro ao tentar ação!');
            },
            success: function(resposta) {
                resposta = JSON.parse(resposta);
                console.log(resposta);
                if (resposta.erro == '') {
                    Swal.fire(
                        "Sucesso",
                        resposta.msg,
                        'success'
                    ).then((result) => {
                        myFunction(resposta.CdLogEspec);
                        window.location.reload();
                    })
                } else {
                    Swal.fire("Erro",
                        resposta.erro,
                        'error');
                }
            }
        });
  }
  });
} else {
    $.ajax({
            url: 'admin/regn_especproc.php',
            method: 'POST',
            cache: false,
            data: {
                dados: $('#commentForm').serialize(),
            },
            datatype: "json",
            error: function() {
                alert('Erro ao tentar ação!');
            },
            success: function(resposta) {
                resposta = JSON.parse(resposta);
                console.log(resposta);
                if (resposta.erro == '') {
                    Swal.fire(
                        "Sucesso",
                        resposta.msg,
                        'success'
                    ).then((result) => {
                        window.location.reload();
                    })
                } else {
                    Swal.fire("Erro",
                        resposta.erro,
                        'error');
                }
            }
        });
      }
    });

});
</script>
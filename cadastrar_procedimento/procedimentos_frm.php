<link rel="stylesheet" href="js/mult/multiple-select.css" type="text/css" />
<script type="text/javascript" src="js/mult/multiple-select.js"></script>
<script src="./cadastrar_procedimento/js/procedimentos_frm.js"> </script>
<script src="./node_modules/moment/moment.js"></script>

<?php

require_once("verifica.php");
require("admin/function_trata_erro.php");
require_once("conecta.php");

?>

<style>
  .overlay {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 350px;
    transform: translateX(-50%) translateY(-50%) scale(1.5);
    z-index: 50;
    text-align: center;
    padding: 1em 0 0;
    background-color: #17A2B8;
  }

  #submit-form {
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.4);
    margin-top: 1%;
    margin-bottom: 2%;
    border-radius: 10px;
    font-weight: bold;
    width: 10rem;
  }
</style>

<div class="row my-3">
  <div class="col-md-6 offset-md-3">
    <form method="POST" id="commentForm">

      <input type="hidden" name="acao" id="acao" value="<?php echo $_GET["s"]; ?>">
      <input type="hidden" name="cdespecproc" id="cdespecproc" value="<?php echo $_GET["id"]; ?>">

      <div class="row">
        <div class="col-12">
          <div class="alert alert-warning text-center" role="alert">
            <strong>Atenção!</strong> Os campos com <strong style="color: red;">*</strong> devem ser preenchidos obrigatoriamente
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">

          <fieldset>
            <div class="card-body" style="padding: 0;">
              <div class="p-3 mb-2 bg-info text-dark" style="border-radius: 10px 10px 0 0; color: white;">
                <h5 class="card-title" style="margin-bottom: -1px;color: white;">Dados do Procedimento</h5>
              </div>
            </div>

            <div class="row">
              <!-- Linha 1 -->

              <!-- Especificacao -->
              <div class="col-5 col-md-6 form-group">
                <label>Especifica&ccedil;&atilde;o<strong style="color: red;">*</strong>: </label>
                <input type="text" class="form-control" name="nm_especproc" id="nm_especproc" size="10" value="" size="50" maxlength="100" />
              </div>
              <!-- Codigo SUS -->
              <div class="col-4 col-md-3 form-group">
                <label> C&oacute;digo SUS<strong style="color: red;">*</strong>:</label>
                <input type="text" class="form-control" name="cdsus" id="cdsus" size="10" />
              </div>
              <!-- Filiacao -->
              <div class="col-3 form-group">
                <label>Filia&ccedil;&atilde;o<strong style="color: red;">*</strong>: </label>
                <select class="form-control add-field select2-single" name="filiacao" id="filiacao">
                  <option value="0">Padrão</option>
                  <option value="1">Principal</option>
                </select>
              </div>
            </div><!-- Fim Linha 1 -->

            <div class="row">
              <!-- Linha 2 -->

              <!-- Descricao SUS -->
              <label class="col-12">Descri&ccedil;&atilde;o SUS:
                <input type="text" placeholder="Max. 100 caracteres" id="desc_sus" class="form-control" name="desc_sus" size="50" maxlength="100" />
              </label>

            </div><!-- Fim Linha 2 -->

            <div class="row">
              <!-- Linha 3 -->

              <!-- Tipo Procedimento -->
              <div class="col-6 form-group">
                <label class="control-label" for="cd_procedimento">Tipo do Procedimento<strong style="color: red;">*</strong>: </label>
                <select name="cd_procedimento" id="cd_procedimento" size="50" class="form-control select2-single">
                  <option value="0" selected="selected">Selecione o Tipo de Procedimento...</option>
                </select>
              </div>
              <!-- Grupo do Procedimento -->
              <div class="col-6 form-group">
                <label class="control-label" for="cdgrupoproc">Grupo do Procedimento: </label>
                <select name="cdgrupoproc" id="cdgrupoproc" class="form-control">
                </select>
              </div>

            </div><!-- Fim Linha 3 -->

            <div class="row">
              <!-- Linha 4 -->

              <!-- Servico -->
              <div class="col-6 form-group">
                <label class="gr">Servi&ccedil;o: </label>
                <select name="servico" id="servico" class="form-control">
                  <option value="0" selected="selected">Selecione um Servi&ccedil;o...</option>
                </select>
              </div>
              <!-- Especialidade -->
              <div class="col-6 form-group">
                <label class="gr" for='cdespecialidade'>Especialidade<strong style="color: red;">*</strong>: </label>
                <select name="cdespecialidade" id="cdespecialidade" class="form-control">
                  <option value="0" selected="selected">Selecione uma Especialidade...</option>
                </select>
              </div>

            </div><!-- Fim Linha 4 -->

            <div class="row">
              <!-- Linha 5 -->

              <!-- Classificacao -->
              <div class="col-5 form-group">
                <label class="control-label" for="class">Classifica&ccedil;&atilde;o: </label>
                <select name="class" id="class" class="form-control">
                  <option value="0">Selecione um Servi&ccedil;o primeiro...</option>
                </select>
              </div>
              <!-- BPA -->
              <div class="col-3 form-group">
                <label> BPA: </label>
                <select name="bpa" class="form-control" id="bpa">
                  <option value="I" selected="selected">Individualizado</option>
                  <option value="N">Não</option>
                  <option value="C">Consolidado</option>
                </select>
              </div>
              <!-- Situacao -->
              <div class="col-2 form-group">
                <label> Situa&ccedil;&atilde;o<strong style="color: red;">*</strong>: </label>
                <select name="status" class="form-control" id="status">
                  <option value="1">Ativo</option>
                  <option value="0">Inativo</option>
                </select>
              </div>
              <!-- PPI -->
              <div class="col-2 form-group">
                <label> PPI: </label>
                <select name="ppi" class="form-control" id="ppi">
                  <option value="N">Não</option>
                  <option value="S">Sim</option>
                </select>
              </div>


            </div><!-- Fim Linha 5 -->

            <div class="row">
              <!-- Linha 6 -->

              <!-- Valor -->
              <div class="col-3 form-group">
                <label>Valor<strong style="color: red;">*</strong>:</label>
                <input type="text" placeholder="R$ 0.00" name="valor" id="valor" size="28" class="form-control" value="R$ 0.00" />
              </div>
              <!-- Valor SUS -->
              <div class="col-3 form-group">
                <label>Valor SUS:</label>
                <input type="text" placeholder="R$ 0.00" name="valorsus" id="valorsus" size="28" class="form-control" value="R$ 0.00" />
              </div>
              <!-- Quem Pode Agendar -->
              <div class="col-4 form-group">
                <label for="class" class="control-label"> Quem pode Agendar<strong style="color: red;">*</strong>: </label>
                <select class="form-control" name="quemAgendar" id="quemAgendar" required>
                  <option value="T">Todos</option>
                  <option value="C">Consórcio</option>
                  <option value="M">Municipio</option>
                </select>
              </div>
              <!-- CID -->
              <div class="col-2 form-group">
                <label> CID:</label>
                <input type="text" name="cid" id="cid" class="form-control" pattern="[A-Za-z]{1}[0-9]{2}" maxlength="3" />
              </div>

            </div><!-- Fim Linha 6 -->

            <div class="row">
              <!-- Linha 7 -->

              <!-- Preparo -->
              <div class="col-12 form-group">
                <label>Preparo:</label>
                <textarea placeholder="Max. 300 caracteres..." name="preparo" id="preparo" rows="4" maxlength="300" style="resize:none" class="form-control"></textarea>
              </div>

            </div><!-- Fim Linha 7 -->
            <div class="card-body" style="padding: 0;">
              <div class="p-3 mb-2 bg-info text-dark" style="border-radius: 10px 10px 0 0; color: white;">
                <h5 class="card-title" style="margin-bottom: -1px;color: white;">Definições de Plantão</h5>
              </div>
            </div>
            
            <div class="row">

              <!-- CID -->
              <div class="col-6 form-group">
                <label> Tempo de execução do plantão</label>
                <input type="number" name="tempo_plantao" id="tempo_plantao" class="form-control" value="0"/>
              </div>

            </div>
            
            <div class="card-body" style="padding: 0;">
              <div class="p-3 mb-2 bg-info text-dark" style="border-radius: 10px 10px 0 0; color: white;">
                <h5 class="card-title" style="margin-bottom: -1px;color: white;">Definições de Sessões</h5>
              </div>
            </div>

            <div class="row">

              <!-- Quem Pode Agendar -->
              <div class="col-6 form-group">
                <label for="class" class="control-label"> Pré-Consulta: </label>
                <select class="form-control" name="preconsulta" id="preconsulta" required>
                  <option value="1">Sim</option>
                  <option value="0">Não</option>
                </select>
              </div>

              <!-- CID -->
              <div class="col-6 form-group">
                <label> Período Validade:</label>
                <input type="number" name="periodo" id="periodo" class="form-control" pattern="[0-9]" />
              </div>

            </div>
            <?php if ($_GET['s'] == "e") { ?>
              <p class="text-data-vigencia" style="text-align: center;" id="text-data-vigencia">Deseja atualizar os valores dos agendamentos retroativos?</p>
              <input type="checkbox" class="form-control" style="margin-bottom: 20px;" id="data-vigente" name="data-vigente" value="0" onclick="isChecked()"></input>
              <div class="datas-de-vigencia" id="datas-de-vigencia">
              </div>
            <?php } ?>
        </div>
        </fieldset>
      </div>

      <!-- Salvar -->
      <div class="row my-3">
        <div class="col-12 text-center" id="botao-confirmar">
          <button type="button" class="btn btn-success btn-lg salvar" id="submit-form" name="submit-form" disabled>Salvar</button>
        </div>
      </div>
  </div>
</div>

<!-- Modal cdsus -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false" id="modal-mensagem">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="title-cdsus">Código SUS Já Cadastrado</h4>
      </div>
      <div class="modal-body">
        <p>O código SUS do procedimento que está tentando realizar um cadastro já possui um registro no sistema, selecione o seguinte procedimento que deseja realizar a atualização das informações com os dados repassados anteriormente:</p>

        <div class="row my-3">
          <div class="col-md-11 offset-md-1">
            <div class="row">
              <div class="col-11 form-group">
                <label> Procedimento: </label>
                <select class="form-control" id="selectproc" name="selectproc">
                </select>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <div id="botao-confirmar">
          <button type="hidden" class='btn btn-lg btn-success' id="confirmar-edicao" data-dismiss="modal">Confirmar</button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal Agendamentos Retroativos -->

<div class="modal fade" id="modal-data">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Selecione o periodo de alteração</h4>
        <button type="button" class="close" data-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <div class="row my-3">
          <div class="col-md-9 offset-md-3">
            <div class="col-7">
              <p>Selecione a data inicial<strong style="color: red;">*</strong>:</p>
              <input class="form-control" type="date" name="dataini" id="dataini" required onkeydown="return false" onmousedown="(function(e){e.preventDefault();})()">
            </div>
            <br></br>
            <div class="col-7">
              <p>Selecione a data final<strong style="color: red;">*</strong>:</p>
              <input class="form-control" type="date" name="datafim" id="datafim" required onkeydown="return false" onmousedown="(function(e){e.preventDefault();})()">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div id="data-vigencia-agendamentos">
          <button type="button" class='btn btn-lg btn-danger' id="cancelar" data-dismiss="modal">Cancelar</button>
          <button type="hidden" class='btn btn-lg btn-success' id="confirmar-data" data-dismiss="modal">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="carregando_op" style="display: none">
  <div class="overlay">
    <div class="row" id="loading">
      <div class="col-12">
        <div class="alert alert-info text-center" role="alert">
          <div class="spinner-border text-info" role="status">
            <span class="sr-only">Carregando...</span>
          </div>
          <strong id="tituloMenu" style="display: block;"> Carregando Procedimento... </strong>
        </div>
      </div>
    </div>
  </div>
</div>

</form>
</div>
</div>
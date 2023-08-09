<link rel="stylesheet" href="js/mult/multiple-select.css" type="text/css" />
<script type="text/javascript" src="js/mult/multiple-select.js"></script>
<script src="./cadastrar_procedimento/js/procedimentos_frm.js"> </script>
<script src="./node_modules/moment/moment.js"></script>

<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>

<style>
  .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 4px;
  }

  .ms-parent,
  .ms-drop {
    width: 100% !important;
  }

  .ms-choice {
    padding: 18px !important;
    border-color: lightgrey;
  }

  .ms-choice span {
    padding-top: 5px;
  }

  #pacote option {
    max-height: 50px !important;
    max-width: 50px !important;
    overflow-y: scroll;
  }
</style>

<?php

require_once("verifica.php");
require("admin/function_trata_erro.php");
require_once("conecta.php");

?>
<div class="row my-3">
  <div class="col-md-6 offset-md-3">
    <form method="POST" id="commentForm">

      <input type="hidden" name="acao" id="acao" value="<?php echo $_GET["s"]; ?>">
      <input type="hidden" name="cdespecproc" id="cdespecproc" value="<?php echo $_GET["id"]; ?>">

      <div class="row">
        <div class="col-12">
          <div class="alert alert-warning text-center" role="alert">
            <strong>Atenção!</strong> Os campos com * devem ser preenchidos obrigatoriamente
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

            <!-- Linha 1 -->
            <div class="row">

              <!-- Especificacao -->
              <div class="col-5 col-md-6 form-group">
                <label>Especifica&ccedil;&atilde;o*: </label>
                <input type="text" class="form-control" name="nm_especproc" id="nm_especproc" size="10" value="" size="50" maxlength="250" />
              </div>

              <!-- Codigo SUS -->
              <div class="col-4 col-md-3 form-group">
                <label> C&oacute;digo SUS*:</label>
                <input type="text" class="form-control" name="cdsus" id="cdsus" size="10" />
              </div>

              <!-- Filiacao -->
              <div class="col-3 form-group">
                <label>Filia&ccedil;&atilde;o*: </label>
                <select class="form-control add-field select2-single" name="filiacao" id="filiacao">
                  <option value="0">Padrão</option>
                  <option value="1">Principal</option>
                </select>
              </div>
            </div>
            <!-- Fim Linha 1 -->

            <!-- Linha 2 -->
            <div class="row">

              <!-- Descricao SUS -->
              <label class="col-12">Descri&ccedil;&atilde;o SUS:
                <input type="text" placeholder="Max. 100 caracteres" id="desc_sus" class="form-control" name="desc_sus" size="50" maxlength="100" />
              </label>

            </div>
            <!-- Fim Linha 2 -->

            <!-- Linha 3 -->
            <div class="row">

              <!-- Grupo (Tipo Procedimento) -->
              <!-- Nomenclatura do campo foi mudada para ser a mesa da Sigtap por Solicitação do consórcio -->
              <!-- Com isso ficou:
                Grupo (Sigtap) =  Procedimento (Sitcon)
                Sub-Grupo (Sigtap) =  cdgrupoproc (Sitcon)
                Forma de Organização (Sigtap) =  CdForma (Sitcon) 
              -->

              <div class="col-6 form-group">
                <label class="control-label" for="cd_procedimento">Grupo*: </label>
                <select name="cd_procedimento" id="cd_procedimento" size="50" class="form-control select2-single">
                  <!-- <option value="0" selected="selected">Selecione o Grupo.</option> -->
                </select>
              </div>

              <!-- Grupo do Procedimento -->
              <div class="col-6 form-group">
                <label class="control-label" for="cdgrupoproc">Sub-Grupo*: </label>
                <select name="cdgrupoproc" id="cdgrupoproc" class="form-control">
                  <option value="0" selected="selected">Selecione o Grupo primeiro.</option>
                </select>
              </div>

            </div>
            <!-- Fim Linha 3 -->

            <!-- Linha 4 -->
            <div class="row">

              <!-- Forma de Organização -->
              <div class="col-6 form-group">
                <label class="control-label" for="CdForma">Forma de Organização*: </label>
                <select name="CdForma" id="CdForma" class="form-control">
                  <option value="0" selected="selected">Selecione o Sub-grupo primeiro.</option>
                </select>
              </div>

              <!-- Servico -->
              <div class="col-6 form-group">
                <label class="gr">Servi&ccedil;o: </label>
                <select name="servico" id="servico" class="form-control">
                  <option value="0" selected="selected">Selecione um Servi&ccedil;o...</option>
                </select>
              </div>

            </div>
            <!-- Fim Linha 4 -->

            <!-- Linha 5 -->
            <div class="row">

              <!-- Especialidade -->
              <div class="col-6 form-group">
                <label class="gr" for='cdespecialidade'>Especialidade*: </label>
                <select name="cdespecialidade" id="cdespecialidade" class="form-control">
                  <option value="0" selected="selected">Selecione uma Especialidade...</option>
                </select>
              </div>

              <!-- Classificacao -->
              <div class="col-6 form-group">
                <label class="control-label" for="class">Classifica&ccedil;&atilde;o: </label>
                <select name="class" id="class" class="form-control">
                  <option value="0">Selecione um Servi&ccedil;o primeiro...</option>
                </select>
              </div>

            </div>
            <!-- Fim Linha 5 -->

            <!-- Linha 6 -->
            <div class="row">

              <!-- BPA -->
              <div class="col-2 form-group">
                <label> BPA: </label>
                <select name="bpa" class="form-control" id="bpa">
                  <option value="N">Não</option>
                  <option value="C">Consolidado</option>
                  <option value="I">Individualizado</option>
                </select>
              </div>

              <!-- Situacao -->
              <div class="col-2 form-group">
                <label> Situa&ccedil;&atilde;o*: </label>
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

              <!-- Quem Pode Agendar -->
              <div class="col-4 form-group">
                <label for="class" class="control-label"> Quem pode Agendar*: </label>
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

            </div>
            <!-- Fim Linha 6 -->

            <!-- Linha 7 -->
            <div class="row">

              <!-- Valor Médico -->
              <div class="col-5 form-group">
                <label>Valor Regional (Valor Médico):</label>
                <input type="text" placeholder="R$ 0.00" name="valorm" id="valorm" size="28" class="form-control" value="R$ 0.00" />
              </div>

              <!-- Valor SUS -->
              <div class="col-3 form-group">
                <label>Valor SUS:</label>
                <input type="text" placeholder="R$ 0.00" name="valorsus" id="valorsus" size="28" class="form-control" value="R$ 0.00" />
              </div>

              <!-- Valor -->
              <div class="col-4 form-group">
                <label>Valor Total (Valor):</label>
                <input type="text" placeholder="R$ 0.00" name="valor" id="valor" size="28" class="form-control" value="R$ 0.00" />
              </div>

            </div>
            <!-- Fim Linha 7 -->

            <!-- Linha 8 -->
            <div class="row">

              <!-- Preparo -->
              <div class="col-12">
                <label>Preparo:</label>
                <textarea placeholder="Max. 300 caracteres..." name="preparo" id="preparo" rows="4" maxlength="300" style="resize:none" class="form-control"></textarea>
              </div>

            </div>
            <!-- Fim Linha 8 -->

            <div class="card-body" style="padding: 0;">
              <div class="p-3 mb-2 bg-info text-dark" style="margin-top: 15px;border-radius: 10px 10px 0 0; color: white;">
                <h5 class="card-title" style="margin-bottom: -1px;color: white;">Definições de Pacote</h5>
              </div>
            </div>

            <!-- Linha 9 -->
            <div class="row">

              <!-- Pacote de Procedimento -->
              <div class="col-12 form-group">
                <label for="pacote">Pacote de Procedimentos: </label>
                <select name="pacote[]" id="pacote" multiple="multiple">
                </select>
              </div>

            </div>
            <!-- Fim Linha 9 -->

            <div class="card-body" style="padding: 0;">
              <div class="p-3 mb-2 bg-info text-dark" style="border-radius: 10px 10px 0 0; color: white;">
                <h5 class="card-title" style="margin-bottom: -1px;color: white;">Definições de Sessão</h5>
              </div>
            </div>

            <!-- Linha 10 -->
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
            <!-- Fim Linha 10 -->

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
        <div class="col-7">
          <button type="button" class="btn btn-primary btn-lg salvar float-right" id="submit-form" name="submit-form" disabled> Salvar </button>
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
              <p>Selecione a data inicial:</p>
              <input class="form-control" type="date" name="dataini" id="dataini" required>
            </div>
            <br></br>
            <div class="col-7">
              <p>Selecione a data final:</p>
              <input class="form-control" type="date" name="datafim" id="datafim" required>
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

</form>
</div>
</div>
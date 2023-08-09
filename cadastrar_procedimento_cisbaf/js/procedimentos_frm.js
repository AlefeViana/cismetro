$(document).ready(function () {
  $("#cd_procedimento").select2({ placeholder: "Selecione o Grupo." });
  $("#selectproc").select2({ width: "100%" });
  $("#cdespecialidade").select2();
  $("#cdgrupoproc").prop("disabled", true);
  $("#CdForma").prop("disabled", true);
  $("#servico").select2();
  $("#class").select2();
  $("#commentForm").validate();

  $(function () {
    $("#pacote").multipleSelect({
      filter: true,
      width: "100%",
      placeholder: "Selecione os procedimento",
      selectAll: "Selecionar tudo",
      selectAllText: "Selecionado tudo",
      allSelected: "Tudo selecionado",
      countSelected: "# de % selecionados",
      noMatchesFound: "Nenhuma opção encontrada",
    });
  });

  $("#dataini").attr({
    min: min(),
  });

  if ($("#acao").val() == "e") {
    $("#submit-form").prop("disabled", false);
  }

  /* ----------- LISTA PROCEDIMENTOS COM CODIGO SUS JA CADASTRADO -----------*/

  $("#cdsus").focusout(function () {
    $(this).val();
    $("#submit-form").prop("disabled", true);
    $.ajax({
      url: "cadastrar_procedimento/loads/load_procedimento_cdsus.php",
      type: "POST",
      dataType: "json",
      data: {
        cdsus: $("#cdsus").val(),
      },
      error: function () {
        $("#submit-form").prop("disabled", false);
      },
      success: function (data) {
        const { dados } = data;

        var options = "";
        var select = "selected";

        if (dados.length > 0) {
          options += '<option value="0"' + select + ">Selecione uma opção...</option>";

          data.dados.forEach(function (item) {
            options += '<option value="' + `${item.CdEspecProc}` + '">' + `${item.NmEspecProc}` + " | Valor: R$" + `${item.valor}` + " | Valor SUS: R$" + `${item.valorsus}` + "</option>";
          });

          options += '<option value="0">Desejo realizar um novo cadastro com código SUS repetido</option>';
          $("#selectproc").html(options);

          $("#modal-mensagem").modal("show");

          $("#selectproc").change(function () {
            var valor = $("#selectproc").val();
            if (valor > 0) {
              $("#confirmar-edicao").attr("onclick", "window.location.href='index.php?i=4&s=e&id=" + valor + "&acao=edit'");
            } else {
              $("#confirmar-edicao").attr("onclick", "");
              $("#submit-form").prop("disabled", false);
            }
          });
        } else {
          $("#submit-form").prop("disabled", false);
        }
      },
    });
  });

  /* ----------- PREENCHE DADOS DO PROCEDIMENTO -----------*/

  if ($("#cdespecproc").val() > 0) {
    $.ajax({
      url: "cadastrar_procedimento/loads/load_procedimento_preenche_dados_iniciais.php",
      type: "POST",
      dataType: "json",
      data: {
        CdEspecProc: $("#cdespecproc").val(),
      },
      success: function (data) {
        const item = data.espec.dados[0];

        data.espec.dados.forEach(function (item) {
          $("#nm_especproc").val(`${item.NmEspecProc}`);
          $("#cdsus").val(`${item.cdsus}`);
          $("#valorsus").val("R$ " + `${item.valorsus}`);
          $("#valor").val("R$ " + `${item.valor}`);
          $("#valorm").val("R$ " + `${item.valorm}`);
          $("#desc_sus").val(`${item.desc_sus}`);
          $("#cid").val(`${item.cid}`);
          $("#cdgrupoproc").val(`${item.cdgrupoproc}`);
          $("#filiacao").val(`${item.principal}`);
          $("#quemAgendar").val(`${item.quemAgendar}`);
          $("#ppi").val(`${item.ppi}`);
          $("#bpa").val(`${item.bpa}`);
          $("#status").val(`${item.status}`);
          $("#preparo").val(`${item.nmpreparo}`);
          $("#periodo").val(`${item.periodo}`);
          $("#preconsulta").val(`${item.preconsulta}`);
        });

        if (`${item.cdgrupoproc}` == 0) {
          $("#cdgrupoproc").val(0);
        } else {
          $("#cdgrupoproc").append(`<option selected="selected" value="${item.cdgrupoproc}">${item.nmgrupoproc}</option>`);
        }

        if (`${item.CdProcedimento}` == 0) {
          $("#cd_procedimento").val(0);
        } else {
          $("#cd_procedimento").append(`<option selected="selected" value="${item.CdProcedimento}">${item.NmProcedimento}</option>`);
        }

        if (`${item.cdForma}` == 0) {
          $("#CdForma").val(0);
        } else {
          $("#CdForma").append(`<option selected="selected" value="${item.cdForma}">${item.NmForma}</option>`);
        }

        $("#CdForma").prop("disabled", false);
        $("#cdgrupoproc").prop("disabled", false);

        if (`${item.co_servico}` > 0) {
          $("#servico").append(`<option selected="selected" value="${item.co_servico}">${item.no_servico}</option>`);
          $("#class").append(`<option id="option-class-inicial" selected="selected" value="${item.co_classificacao}">${item.no_classificacao}</option>`);

          $.ajax({
            url: "cadastrar_procedimento/loads/load_procedimento_classificacao.php",
            type: "POST",
            dataType: "json",
            data: {
              co_servico: $("#servico").val(),
            },
            success: function (data) {
              var verifica = $("#class").val();

              data.class.forEach(function (item) {
                if (verifica != `${item.co_classificacao}`) {
                  $("#class").append(`<option value="${item.co_classificacao}">${item.no_classificacao}</option>`);
                }
              });
            },
          });
        }
        if (`${item.cdespecialidade}` == 0) {
          $("#cdespecialidade").val(0);
        } else {
          $("#cdespecialidade").append(`<option selected="selected" value="${item.cdespecialidade}">${item.nmespecialidade}</option>`);
        }
      },
    });
  }

  /* ----------- LISTA PACOTE -----------*/
  $.ajax({
    url: "cadastrar_procedimento/loads/load_pacote.php",
    type: "POST",
    dataType: "json",
    data: {
      cdespecproc: $("#cdespecproc").val() > 0 ? $("#cdespecproc").val() : null,
    },
    error: function () {
      alert("Erro!");
    },
    success: function (data) {
      const acao = parametrosUrl().s;

      data.pacote.forEach(function (item) {
        if (`${item.cdespec}` == $("#cdespecproc").val() && acao == 'e') {
          $("#pacote").append(`<option value="${item.CdEspecProc}" selected="selected">${item.NmEspecProc}</option>`);
        } else if (item.cdespec > 0  && acao == 'e') {
          $("#pacote").append(`<option value="${item.CdEspecProc}" selected="selected">${item.NmEspecProc}</option>`);
        } else {
          $("#pacote").append(`<option value="${item.CdEspecProc}">${item.NmEspecProc}</option>`);
        }
      });
      $("#pacote").multipleSelect("refresh");
    },
  });

  /* ----------- LISTA TIPO DE PROCEDIMENTOS -----------*/
  $.ajax({
    url: "cadastrar_procedimento/loads/load_procedimento_tpprocedimentos.php",
    type: "POST",
    dataType: "json",
    data: {},
    success: function (data) {
      var verifica = $("#cd_procedimento").val();

      data.tpproc.forEach(function (item) {
        if (verifica != `${item.CdProcedimento}`) {
          $("#cd_procedimento").append(`<option value="${item.CdProcedimento}">${item.NmProcedimento}</option>`);
        }
      });
    },
  });

  /* ----------- LISTA ESPECIALIDADES -----------*/
  $.ajax({
    url: "cadastrar_procedimento/loads/load_procedimento_especialidade.php",
    type: "POST",
    dataType: "json",
    data: {},
    success: function (data) {
      var verifica = $("#cdespecialidade").val();

      data.especialidade.forEach(function (item) {
        if (verifica != `${item.cdespecialidade}`) {
          $("#cdespecialidade").append(`<option value="${item.cdespecialidade}">${item.nmespecialidade}</option>`);
        }
      });
    },
  });

  /* ----------- LISTA SERVICOS -----------*/
  $.ajax({
    url: "cadastrar_procedimento/loads/load_procedimento_servico.php",
    type: "POST",
    dataType: "json",
    data: {},
    success: function (data) {
      var verifica = $("#servico").val();

      data.servico.forEach(function (item) {
        if (verifica != `${item.co_servico}`) {
          $("#servico").append(`<option value="${item.co_servico}">${item.no_servico}</option>`);
        }
      });
    },
  });

  /* ----------- LISTA CLASSIFICACAO -----------*/
  $("#servico").change(function () {
    if ($("#servico").val() == "0") {
      $("#class").empty();
    }
    $("#option-class-inicial").remove();
    $.ajax({
      url: "cadastrar_procedimento/loads/load_procedimento_classificacao.php",
      type: "POST",
      dataType: "json",
      data: {
        co_servico: $("#servico").val(),
      },
      success: function (data) {
        var verifica = $("#class").val();
        var options = "";

        data.class.forEach(function (item) {
          if (verifica != `${item.co_classificacao}`) {
            options += `<option value="${item.co_classificacao}">${item.no_classificacao}</option>`;
            $("#class").html(options);
          }
        });
      },
    });
  });

  function numberify(value) {
    return parseFloat(value.trim().replace(/^R\$ +/, "")).toFixed(2);
  }

  $(document).on("click", "#submit-form", function () {
    if ($("#valor").val() == "") $("#valor").val("R$ 0.00");
    if ($("#valorm").val() == "") $("#valorm").val("R$ 0.00");
    if ($("#valorsus").val() == "") $("#valorsus").val("R$ 0.00");

    $("#valor").val(numberify($("#valor").val()));
    $("#valorm").val(numberify($("#valorm").val()));
    $("#valorsus").val(numberify($("#valorsus").val()));

    if ($("#nm_especproc").val() != "" && $("#cd_procedimento").val() > 0 && $("#cdsus").val() != "" && $("#filiacao").val() != "" && $("#cdespecialidade").val() > 0) {
      $.ajax({
        url: "cadastrar_procedimento/submit.php",
        type: "POST",
        cache: false,
        datatype: "json",
        data: {
          cdespecproc: $("#cdespecproc").val(),
          nm_especproc: $("#nm_especproc").val(),
          cd_procedimento: $("#cd_procedimento").val(),
          desc_sus: $("#desc_sus").val(),
          ppi: $("#ppi").val(),
          bpa: $("#bpa").val(),
          cdespecialidade: $("#cdespecialidade").val(),
          cdgrupoproc: $("#cdgrupoproc").val(),
          CdForma: $("#CdForma").val(),
          nmpreparo: $("#preparo").val(),
          cid: $("#cid").val(),
          servico: $("#servico").val(),
          class: $("#class").val(),
          filiacao: $("#filiacao").val(),
          quemAgendar: $("#quemAgendar").val(),
          cdsus: $("#cdsus").val(),
          status: $("#status").val(),

          dataini: $("#dataini").val(),
          datafim: $("#datafim").val(),

          valor: $("#valor").val(),
          valorsus: $("#valorsus").val(),
          valorm: $("#valorm").val(),
          acao: $("#acao").val(),
          periodo: $("#periodo").val(),
          preconsulta: $("#preconsulta").val(),

          pacote: $("#pacote").val(),
        },
        error: function () {
          alert("Erro ao tentar ação!");
        },
        success: function (response) {
          if ((response.success = "success")) {
            Swal.fire("Sucesso!", "", "success").then((e) => {
              location.href = "index.php?i=4";
            });
          } else {
            Swal.fire("Erro ao salvar!", "Favor tentar novamente!", "error").then((e) => {
              location.reload();
            });
          }
        },
      });
    } else {
      alert("Preencha os campos obrigatorios!");
    }
  });

  $("#cdgrupoproc").select2({
    ajax: {
      url: "cadastrar_procedimento/loads/load_procedimento_grupoproc.php",
      type: "POST",
      dataType: "json",
      data: function (params) {
        const cdProcedimento = $("#cd_procedimento").val();

        return {
          cdProcedimento,
          q: params.term,
        };
      },
      processResults: function (data) {
        $("#cdgrupoproc").prop("disabled", false);
        return {
          results: data.itens,
        };
      },
    },
    placeholder: "Selecione",
  });

  $("#CdForma").select2({
    ajax: {
      url: "cadastrar_procedimento/loads/load_procedimento_forma.php",
      type: "POST",
      dataType: "json",
      data: function (params) {
        const cdProcedimento = $("#cd_procedimento").val();
        const cdgrupoproc = $("#cdgrupoproc").val();

        return {
          cdgrupoproc,
          cdProcedimento,
          q: params.term,
        };
      },
      processResults: function (data) {
        $("#CdForma").prop("disabled", false);
        return {
          results: data.itens,
        };
      },
    },
    placeholder: "Selecione",
  });
});

jQuery(function ($) {
  $("#cdsus").mask("99.99.99.999-9");
  $("#cid").mask("a99");
  $("#valor").maskMoney({
    prefix: "R$ ",
    allowNegative: true,
    thousands: "",
    decimal: ".",
  });
  $("#valorsus").maskMoney({
    prefix: "R$ ",
    allowNegative: true,
    thousands: "",
    decimal: ".",
  });
  $("#valorm").maskMoney({
    prefix: "R$ ",
    allowNegative: true,
    thousands: "",
    decimal: ".",
  });
});

function dataAtual() {
  var data = new Date();
  var dia = String(data.getDate()).padStart(2, "0");
  var mes = String(data.getMonth() + 1).padStart(2, "0");
  var ano = data.getFullYear();

  var dataAtual;

  dataAtual = ano + "-" + mes + "-" + dia;
  return dataAtual;
}

// FUNCAO QUE LIMITA EM ATE 1 MES ANTERIOR A DATA ATUAL
function min() {
  var data = new Date();
  var mes = String(data.getMonth()).padStart(2, "0");
  var ano = data.getFullYear();

  var dataMin;

  dataMin = ano + "-" + mes + "-01";

  return dataMin;
}

// LIMITA A DATA FINAL DE ATUALIZACAO DE VALORES DE AGENDAMENTOS
$(document).on("change", "#dataini", function () {
  let dataFinal = moment($(this).val()).endOf("month");

  $("#datafim").val(null);

  $("#datafim").attr({
    min: $(this).val(),
    max: dataFinal.format("YYYY-MM-DD"),
  });
});

/* ----------- LISTA GRUPO -----------*/
$(document).on("change", "#cd_procedimento", function () {
  $("#cdgrupoproc").empty();
  $("#CdForma").empty();
  $("#cdgrupoproc").prop("disabled", false);
});

/* ----------- LISTA FORMA DE ORGANIZAÇÃO -----------*/
$(document).on("change", "#cdgrupoproc", function () {
  $("#CdForma").empty();
  $("#CdForma").prop("disabled", false);
});

/* ----------- Adiciona ou remove as datas de vigencia de atualizacao dos agendamentos retroativos -----------*/

function isChecked() {
  if ($("#data-vigente").val() == 0) {
    if (document.getElementById("data-vigente").checked) {
      $("#modal-data").modal("show");

      $("#confirmar-data").click(function () {
        if ($("#dataini").val() != "") {
          var dataEscolhida = "";
          var aux;

          let dataIni = $("#dataini").val();
          if ($("#datafim").val() <= 0) {
            $("#datafim").val(dataAtual());
          }
          let dataFim = $("#datafim").val();

          var data_1 = new Date($("#dataini").val());
          var data_2 = new Date($("#datafim").val());

          if (data_1 > data_2) {
            aux = dataFim;
            dataFim = dataIni;
            dataIni = aux;
            $("#dataini").val(dataIni);
            $("#datafim").val(dataFim);
          }

          let dataFormatadaIni = dataIni.split("-").reverse().join("/");
          let dataFormatadaFim = dataFim.split("-").reverse().join("/");

          dataEscolhida += '<p style="text-align:center" id="data-ini-escolhida" name="data-ini-escolhida" value="' + dataFormatadaIni + '">Data Inicial: ' + dataFormatadaIni + "</p>";
          dataEscolhida += '<p style="text-align:center" id="data-fim-escolhida" name="data-fim-escolhida" value="' + dataFormatadaFim + '">Data Final: ' + dataFormatadaFim + "</p>";

          $("#datas-de-vigencia").html(dataEscolhida);

          $("#data-vigente").val("1");
        } else {
          $("#data-vigente").prop("checked", false);
          alert("Escolha uma data inicial!");
        }
      });
      $("#cancelar").click(function () {
        $("#data-vigente").prop("checked", false);
      });
    }
  } else {
    $("#data-ini-escolhida").remove();
    $("#data-fim-escolhida").remove();

    $("#data-vigente").val("0");
  }
}

// Paramentros da url

function parametrosUrl() {
  const partes = location.search.split("&");
  const data = {};

  partes.forEach((parte) => (data[parte.split("=")[0]] = parte.split("=")[1]));

  return data;
}

// Evento para atualizar os campos de Select2 ao mudar o tamanho da tela. Quando muda o tamanho da tela a visualização fica zoada sem esse evento.

// addEventListener("resize", (event) => {
//   $("#cd_procedimento").select2("destroy");
//   $("#selectproc").select2("destroy");
//   $("#cdespecialidade").select2("destroy");
//   $("#cdgrupoproc").select2("destroy");
//   $("#servico").select2("destroy");
//   $("#class").select2("destroy");
//   $("#CdForma").select2("destroy");

//   $("#cd_procedimento").select2({ placeholder: "Selecione o Grupo." });
//   $("#selectproc").select2({ width: "100%" });
//   $("#cdespecialidade").select2();
//   $("#servico").select2();
//   $("#class").select2();
//   $("#cdgrupoproc").select2({
//     ajax: {
//       url: "cadastrar_procedimento/loads/load_procedimento_grupoproc.php",
//       type: "POST",
//       dataType: "json",
//       data: function (params) {

//         const cdProcedimento = $("#cd_procedimento").val();

//         return {
//           cdProcedimento,
//           q: params.term,
//         };
//       },
//       processResults: function (data) {
//         $("#cdgrupoproc").prop("disabled", false);
//         return {
//           results: data.itens,
//         };
//       },
//     },
//     placeholder: "Selecione",
//   });
//   $("#CdForma").select2({
//     ajax: {
//       url: "cadastrar_procedimento/loads/load_procedimento_forma.php",
//       type: "POST",
//       dataType: "json",
//       data: function (params) {

//         const cdProcedimento = $("#cd_procedimento").val();
//         const cdgrupoproc = $("#cdgrupoproc").val();

//         return {
//           cdgrupoproc,
//           cdProcedimento,
//           q: params.term,
//         };
//       },
//       processResults: function (data) {
//         $("#CdForma").prop("disabled", false);
//         return {
//           results: data.itens,
//         };
//       },
//     },
//     placeholder: "Selecione",
//   });
// });

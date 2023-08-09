Chart.Legend.prototype.afterFit = function () {
  this.height = this.height + 8;
};

const createBarChart = (ctx) => new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Fila de Espera', 'Emcaminhados', 'Marcado', 'Realizado', 'Cancelado', 'Falta'],
    datasets: [{
      backgroundColor: ['#F44336', '#FF7043', '#4CAF50', '#0D47A1', '#D32F2F', '#BDBDBD'],
      data: [0, 0, 0, 0, 0, 0, 0],
    }]
  },
  options: {
    title: {
      display: true,
      text: 'Agendamentos por Status'
    },
    animation: {
      duration: 0,
      onComplete() { // adiciona o label de valor ao top da barra
        const ctx = this.chart.ctx;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';

        this.data.datasets.forEach((dataset, i) => {
          const meta = this.chart.controller.getDatasetMeta(i);

          meta.data.forEach((bar, index) => {
            if (dataset.data[index] > 0) {
              // formata o valor para real
              const value = dataset.values[index];

              ctx.fillText(value, bar._model.x, bar._model.y);
            }
          });
        });

      },
    },
    legend: {
      display: false
    },
    tooltips: {
      callbacks: {
        label: (tooltipItem) => tooltipItem.yLabel,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      xAxes: [{
        barPercentage: 0.7,
        beginAtZero: true,
      }],
    },
  },
});

const createLineChart = (ctx) => new Chart(ctx, {
  type: 'line',
  options: {
    title: {
      display: true,
      text: 'Agendamentos por Município'
    },
    legend: {
      display: false
    },
    elements: {
      line: {
        tension: 0.2,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    hoverMode: 'index',
    stacked: false,
    legendCallback: createLegend,
  }
});

const createPieChart = (ctx) => new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['Disponibilizado', 'Ocupadas', 'Não Agendadas'],
    datasets: [{
      backgroundColor: ['#2196F3', '#EF5350', '#4CAF50'],
      data: [0, 0, 0],
    }]
  },
  options: {
    title: {
      display: true,
      text: 'Agendas'
    },
    elements: {
      line: {
        tension: 0.2,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
    hoverMode: 'index',
    stacked: false,
  }
});

const createGaugeChart = (ctx) => new Chart(ctx, {
  type: 'gauge',
  labels: ['Muito ruim', 'Ruim', 'Regular', 'Bom', 'Muito Bom'],
  data: {
    datasets: [{
      data: [1, 2, 3, 4, 5],
      value: 0,
      backgroundColor: ['#C62828', '#FF5722', '#FDD835', '#A5D6A7', '#4CAF50'],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    hoverMode: 'index',
    stacked: false,
    title: {
      display: true,
      text: 'Pesquisa de Satisfação'
    },
    layout: {
      padding: {
        bottom: 30
      }
    },
    needle: {
      radiusPercentage: 1,
      widthPercentage: 2,
      lengthPercentage: 60,
      color: '#424242'
    },
    valueLabel: {
      formatter: (value) => Math.round(value * 100 / 5) + '%',
    },
  }
});

// cria a leganda customizada para o gráfico de linhas
function createLegend(chart) {
  return chart.data.datasets.map(({ backgroundColor, label }) => ({
    backgroundColor,
    label,
    hidden: false,
  }));
}

(new Vue({
  el: '#graphics-app',

  data() {
    const path = 'Gestor/service/';
    // 5min tempo de recarregar os dados do gráfico
    const reloadTime = 1000 * 60 * 5;

    return {
      filter: {},
      path,
      charts: [
        { // gráfico de barras (agendamentos)
          url: `${path}graficoBarra.php`,
          createChart: createBarChart,
          updateChart: this.updateBarChart,
          reloadTime,
        },

        { // gráfico de pizza (agendas fornecedor)
          url: `${path}graficoTorta.php`,
          createChart: createPieChart,
          updateChart: this.updatePieChart,
          reloadTime,
        },

        { // gráfico de linhas por município (agendamentos)
          url: `${path}graficoLinha.php`,
          createChart: createLineChart,
          updateChart: this.updateLineChart,
          reloadTime,
          excludeFilter: ['municipio'],
        },

        { // gráfico de pizza (agendas fornecedor)
          url: `${path}graficoMedidor.php`,
          createChart: createGaugeChart,
          updateChart: this.updateGaugeChart,
          reloadTime,
        },
      ],
    }
  },

  methods: {
    randomColor: () => '#' + Math.floor(Math.random() * 16777215).toString(16),

    getFilter(filter) {
      this.filter = { ...filter };
    },

    updateBarChart(chart, { quantitativo, valores }) {
      const ctx = chart.ctx;

      chart.data.datasets.forEach((dataset) => {
        dataset.data = quantitativo;

        dataset.values = valores;
      });
    },

    updateLineChart(chart, { municipios, labels }) {
      chart.data.labels = labels;

      // atualiza os dados do gráfico
      chart.data.datasets = municipios.map((municipio) => {
        const data = Object.keys(municipio)
          .filter((key) => /data:/g.test(key))
          .map((key) => municipio[key]);

        const color = this.randomColor();

        return {
          label: municipio.NmCidade,
          backgroundColor: color,
          borderColor: color,
          borderWidth: 1.5,
          data,
          fill: false,
        };
      });
    },

    updatePieChart(chart, data) {
      chart.data.datasets.forEach((dataset) => {
        dataset.data = [
          data.disponibilizado,
          data.ocupadas,
          data.naoAgendadas,
        ];
      });
    },

    updateGaugeChart(chart, { satisfacao }) {
      chart.data.datasets.forEach((dataset) => {
        dataset.value = satisfacao
      });
    },

  },
}))

Vue.component('Graphic', {
  template: '#graphic',

  props: {
    url: String,
    createChart: Function,
    updateChart: Function,
    reloadTime: {
      type: Number,
      default: 1000 * 60,
    },
    filter: {
      type: Object,
      default: () => { },
    },
  },

  data: () => ({
    chart: null,
    legend: [],
  }),

  methods: {
    toggleDataSetLegend(index) {
      const toggle = !this.legend[index].hidden;

      this.legend[index].hidden = toggle;
      this.chart.data.datasets[index].hidden = toggle;

      this.chart.update();
    },

    async fetchGraphic() {
      const graphicData = await $.ajax({
        url: this.url,
        type: 'GET',
        dataType: 'json',
        data: { ...this.filter },
      });

      this.updateChart(this.chart, graphicData);
      this.chart.update();

      const leg = this.chart.generateLegend();
      if (leg instanceof Array) {
        this.legend = this.chart.generateLegend();
      }
    },
  },

  watch: {
    filter() {
      this.fetchGraphic();
    },
  },

  mounted() {
    const ctx = this.$refs.graphic?.getContext('2d');
    if (!ctx) {
      return;
    }

    this.chart = this.createChart(ctx);

    setInterval(this.fetchGraphic, this.reloadTime);
  },

  destroyed() {
    clearInterval(this.fetchGraphic);
  },
});

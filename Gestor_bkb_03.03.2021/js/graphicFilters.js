Vue.component('GraphicFilter', {
  template: '#graphic-filter',

  props: {
    path: String,
    exclude: { // exclui filtros
      type: Array,
      default: () => [],
    },
  },

  data: () => ({
    showModal: false,
    filter: {
      main: 'dia',
      CdPref: 0,
      CdForn: 0,
      cdgrupoproc: 0,
      CdEspecProc: 0,
      Sexo: 0,
    },
    options: {
      municipios: [],
      fornecedores: [],
      gruposProcedimentos: [],
      especificacoes: [],
      sexo: [
        { value: 0, text: 'Todos' },
        { value: 'M', text: 'Masculino' },
        { value: 'F', text: 'Feminino' },
      ],
    },
  }),

  methods: {
    wasNotExcluded(filterName) {
      return !this.exclude.includes(filterName);
    },

    handleMainFilter(main) {
      this.filter.main = main;
    },

    handleModal() {
      this.showModal = true;
    },

    emitFilter() {
      this.$emit('filter', this.filter);
    },

    /* handleHideModal({ target }) {
      const modalRef = this.$refs.modalContent;
      const buttonRef = this.$refs.button;
      console.log(buttonRef, target);

      if (modalRef !== target && !modalRef.contains(target) && buttonRef !== target) {
        this.showModal = false;
      }
    }, */

    handleSubmit() {
      this.showModal = false;
      this.emitFilter();
    },

    async fetchSecondaryFilters() {
      const options = await $.ajax({
        url: `${this.path}filters.php`,
        type: 'GET',
        dataType: 'json',
      });

      this.options = {
        ...this.options,
        ...options,
      };
    },

    async fetchEspec() {
      this.filter.CdEspecProc = 0;

      const options = await $.ajax({
        url: `${this.path}filterEspecProc.php`,
        type: 'GET',
        dataType: 'json',
        data: {
          cdgrupoproc: this.filter.cdgrupoproc,
        },
      });

      this.options = {
        ...this.options,
        ...options,
      };
    }
  },

  watch: {
    'filter.cdgrupoproc'(newGrupo, oldGrupo) {
      if (newGrupo !== oldGrupo) {
        this.fetchEspec();
      }
    },
    'filter.main'(newMain, oldMain) {
      if (newMain !== oldMain) {
        this.emitFilter();
      }
    },
  },

  mounted() {
    this.fetchSecondaryFilters();
    this.fetchEspec();

    this.emitFilter();
  },
});

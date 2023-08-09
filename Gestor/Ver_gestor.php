<div id="graphics-app">
  <graphic-filter :path="path" @filter="getFilter"></graphic-filter>

  <div class="graphics">
    <graphic v-for="(chart, index) in charts" :key="`graphic-${index}`" :url="chart.url" :create-chart="chart.createChart" :update-chart="chart.updateChart" :reload-time="chart.reloadTime" :filter="filter"></graphic>
  </div>
</div>

<script type="text/x-template" id="graphic">
  <div class="graphic">
    <!-- Custom legend -->
    <div ref="legend" class="graphic-legend" v-if="legend.length">
      <div class="action-legend">Legenda</div>
      <div class="menu-legend">
        <div v-for="({ backgroundColor, label, hidden }, index) in legend" :key="`legend-${index}`" :class="{ 'legend-hidden': hidden, 'menu-item': true }" @click="() => toggleDataSetLegend(index)">
          <div :style="{ backgroundColor }"></div> 
          <span>{{label}}</span>
        </div>
      </div>
    </div>

    <canvas ref="graphic"></canvas>
  </div>
</script>

<script type="text/x-template" id="graphic-filter">
  <div>
    <div class="main-filter" title="Filtro Principal">
      <div v-if="wasNotExcluded('dia')" :class="{ selected: filter.main === 'dia' }" @click="() => handleMainFilter('dia')">Dia</div>
      <div v-if="wasNotExcluded('semana')" :class="{ selected: filter.main === 'semana' }" @click="() => handleMainFilter('semana')">Semana</div>
      <div v-if="wasNotExcluded('mes')" :class="{ selected: filter.main === 'mes' }" @click="() => handleMainFilter('mes')">Mês</div>
      <div v-if="wasNotExcluded('ano')" :class="{ selected: filter.main === 'ano' }" @click="() => handleMainFilter('ano')">Ano</div>

      <button class="secondary-filter" title="Filtro Secundário" @click="handleModal" ref="button"><i class="fa fa-cog"></i></button>
    </div>

    <div v-show="showModal" class="graphic-modal" style="display: none;">
      <div class="content" ref="modalContent">
        <form @submit.prevent="handleSubmit">

          <div class="form-group" v-if="wasNotExcluded('municipio')">
            <label for="municipio">Município</label>
            <select class="form-control" v-model.number="filter.CdPref">
              <option v-for="{ CdPref, NmCidade } in options.municipios" :key="'municipio-' + CdPref" :value="CdPref">{{ NmCidade }}</option>
            </select>
          </div>

          <div class="form-group" v-if="wasNotExcluded('fornecedor')">
            <label for="fornecedor">Fornecedor</label>
            <select class="form-control" v-model.number="filter.CdForn">
              <option v-for="{ CdForn, NmForn } in options.fornecedores" :key="'fornecedor-' + CdForn" :value="CdForn">{{ NmForn }}</option>
            </select>
          </div>

          <div class="form-group" v-if="wasNotExcluded('grupoProcedimento')">
            <label for="grupoProcedimento">Grupo Procedimento</label>
            <select class="form-control" v-model.number="filter.cdgrupoproc">
              <option v-for="{ cdgrupoproc, nmgrupoproc } in options.gruposProcedimentos" :key="'grupo-' + cdgrupoproc" :value="cdgrupoproc">{{ nmgrupoproc }}</option>
            </select>
          </div>

          <div class="form-group" v-if="wasNotExcluded('especificacao')">
            <label for="especificacao">Especificação</label>
            <select class="form-control" v-model.number="filter.CdEspecProc">
              <option v-for="{ CdEspecProc, NmEspecProc } in options.especificacoes" :key="'espec-' + CdEspecProc" :value="CdEspecProc">{{ NmEspecProc }}</option>
            </select>
          </div>

          <div class="form-group" v-if="wasNotExcluded('sexo')">
            <label for="sexo">Sexo</label>
            <select class="form-control" v-model.number="filter.Sexo">
              <option v-for="{ value, text } in options.sexo" :key="'sexo-' + value" :value="value">{{ text }}</option>
            </select>
          </div>

          <div class="graphic-actions">
            <button type="submit" class="btn btn-success">Filtrar</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</script>
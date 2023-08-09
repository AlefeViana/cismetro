import "./../../node_modules/jspdf/dist/jspdf.umd.min.js";
import "./../../node_modules/jspdf-autotable/dist/jspdf.plugin.autotable.min.js";
import "./../../node_modules/lodash/lodash.min.js";
import "./../../node_modules/lodash/lodash.min.js";
import "./../../node_modules/jspdf-barcode/dist/jspdf-barcode-edited.js";
import moment from "./../../node_modules/moment/dist/moment.js";

import "./fonts/Proxima Nova Font-normal.js";
import "./fonts/Proxima Nova Font-bold.js";
import "./fonts/Proxima Nova Font-bolditalic.js";
import "./fonts/Proxima Nova Font-italic.js";

const jsPDF = jspdf.jsPDF;

// import header from './imgs/header.png'
const header = './js/DefaultReport/imgs/header.png';

const totalPagesExp = '{pg}';
class DefaultReport extends jsPDF {
  static cores = {
    cinzaPares: 'rgb(255, 255, 255)',
    cinzaImpares: 'rgb(235, 235, 235)',
    azulPares: 'rgb(0, 120, 164)',
    azulImpares: 'rgb(0, 65, 109)',
    azulClaroPares: 'rgb(184, 236, 255)',
    azulClaroImpares: 'rgb(173, 222, 255)',
    vermelhoPares: 'rgb(255, 112, 112)',
    vermelhoImpares: 'rgb(255, 71, 71)',
    laranjaPares: 'rgb(255, 218, 179)',
    laranjaImpares: 'rgb(255, 206, 153)',
    salmaoPares: 'rgb(255, 148, 148)',
    salmaoImpares: 'rgb(255, 129, 129)',
  }

  static headStyles = {
    fillColor: DefaultReport.cores.azulImpares,
    lineWidth: 0.1,
    textColor: 'white',
    fontStyle: 'bold',
    fontSize: 10,
    lineColor: 'rgb(80, 80 ,80)',
  }

  static bodyStyles = {
    fontSize: 6,
    textColor: 'black',
    lineColor: 'rgb(80, 80 ,80)',
  }

  #customOptions;
  finalY = 0;
  #lastMargin = 0;
  #jaPrepPag = {};
  prepPaginar = () => {
    if (!this.#customOptions.pagination) {
      console.warn('Tentou inserir paginação em um documento com paginação oculta');
    }
    const pag = this.internal.getNumberOfPages();
    if (this.#jaPrepPag[pag]) return;
    this.#jaPrepPag[pag] = true;
    // Footer
    var str = 'Página ' + pag;
    // Total page number plugin only available in jspdf v1.0+
    if (typeof this.putTotalPages === 'function') {
      str = str + ' de ' + totalPagesExp;
    }
    this.setFontSize(10).setTextColor('black');

    // jsPDF 1.4+ uses getWidth, <1.4 uses .width
    var pageSize = this.internal.pageSize;
    var pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
    this
      .text(str, pageSize.width - this.#customOptions.marginX, pageHeight - this.#customOptions.marginY + 4, { align: 'right' })
    if (this.#customOptions.organization)
      this.text(this.#customOptions.organization, this.#customOptions.marginX, pageHeight - this.#customOptions.marginY + 4);
    if (pag > 1 && (this.#customOptions.title || this.#customOptions.subtitle))
      this.text([this.#customOptions.title, this.#customOptions.subtitle].filter(e => e).join(' – '), this.#customOptions.marginX, this.#customOptions.marginY + 2, { baseline: 'top' });
    return this;
  };

  show = (action, filename) => {
    const newwindow = 'newwindow';
    const newtab = 'newtab';
    const download = 'download';
    const thiswindow = 'thiswindow';
    const generateuri = 'generateuri';
    if (![newwindow, newtab, download, thiswindow, generateuri].includes(action = action?.toString().toLowerCase()))
      action = newwindow;
    this.#putPagination(true);
    if (filename || action === download)
      filename = (filename ?? `${[this.#customOptions.title, this.#customOptions.subtitle].filter(e => e).join('_') || 'relatorio'}_:timestamp:`)
        .replace(/:timestamp:/gi, moment().format('YYYY-MM-DD_HH-MM-SS'))
        .replace(/_?(?:[\\/:*?"<>|]+_?)+/g, '_');
    const uri = (action == newwindow || action == newtab || action == thiswindow || action == generateuri) && (this.output('bloburi'));
    if (filename && (action === newwindow || action === newtab))
      this.setProperties({ title: filename, filename: filename });
    switch (action) {
      case newwindow:
        window.open(uri, filename, `toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes`);
        break;
      case newtab:
        window.open(uri, '_blank');
        break;
      case download:
        this.save(filename);
        break;
      case thiswindow:
        location = uri;
        break;
      case generateuri:
        return uri;
    }
  }

  constructor(jsPdfOptions, customOptions) {
    jsPdfOptions = _.defaultsDeep({}, jsPdfOptions, {});
    super(jsPdfOptions);
    const defaultCustomOption = {
      headerBG: { x: 0, y: 0, width: this.internal.pageSize.width, height: 82, imageData: typeof header == 'string' ? header : 'src' in header ? header.src : '', format: 'PNG', alias: 'Header', compression: 'NONE', rotation: 0 },
      headerFontColor: customOptions.headerBG === false ? 'black' : 'white',
      logo: {
        format: (customOptions?.logo?.imageData ?? '.PNG').replace(/\.([^.])*$/, '$1').toUpperCase(),
        x: 18, y: 18, width: 20, height: 20, alias: 'Logo', compression: 'NONE', rotation: 0
      },
      organization: null,
      contactInfo: null,
      title: 'Relatório',
      subtitle: null,
      reportInfo: null,
      reportInfoStrong: null,
      reportInfo2: null,
      reportInfoStrong2: null,
      reportInfoLabelSize: 26,
      pagination: true,
      marginX: 14,
      marginY: 10,
      start: 44,
      headerOffset: 0,
    };
    this.#customOptions = customOptions = _.defaultsDeep({}, customOptions, defaultCustomOption);
    [customOptions.headerBG, customOptions.logo].forEach(e => {
      if (e && e.imageData)
        this.addImage(e.imageData, e.format, e.x, e.y + customOptions.headerOffset, e.width, e.height, e.alias, e.compression, e.rotation);
    });

    this.setFont('Proxima Nova Font', 'normal').setTextColor(customOptions.headerFontColor);
    if (customOptions.organization)
      this.setFontSize(12)
        .text(customOptions.organization, this.internal.pageSize.getWidth() / 2, 15 + customOptions.headerOffset, { align: 'center' });
    if (customOptions.contactInfo) {
      this.setFontSize(6);
      if (!Array.isArray(customOptions.contactInfo)) customOptions.contactInfo = [customOptions.contactInfo];
      customOptions.contactInfo = customOptions.contactInfo.filter(e => e).map(e => e + '');
      customOptions.contactInfo.forEach((e, i) => this.text(e, this.internal.pageSize.getWidth() / 2, 19 + customOptions.headerOffset + i * 4, { align: 'center' }));
    }
    const rowDist = 5;
    const start = customOptions.start;
    const firstAlign = customOptions.marginX;
    const secondAlign = firstAlign + customOptions.reportInfoLabelSize;
    if (customOptions.title)
      this.setFontSize(20)
        .text(customOptions.title, customOptions.marginX, start - 8);
    this.finalY = start - rowDist;
    [
      [customOptions.reportInfo, 10, 12, rowDist],
      [customOptions.reportInfoStrong, 12, 14, rowDist + 1],
      [customOptions.reportInfo2, 10, 12, rowDist],
      [customOptions.reportInfoStrong2, 12, 14, rowDist + 1]
    ].forEach(([reportInfo, small, big, rd]) => {
      if (reportInfo) {
        if (!Array.isArray(reportInfo)) reportInfo = [reportInfo];
        reportInfo.forEach(e => {
          this.finalY += rd;
          if (!Array.isArray(e)) e = [null, e];
          if (e.length < 2) e = [null, e[0]];
          if (e[0]) this.setFontSize(small).text(e[0], firstAlign, this.finalY);
          if (e[1]) this.setFontSize(big).text(e[1], secondAlign, this.finalY);
        });
      }
    });
    this.setProperties({
      title: [customOptions.title, customOptions.subtitle].filter(e => e).join(' — ')
    });
  }

  #putPagination = () => {
    if (typeof this.putTotalPages === 'function') {
      this.putTotalPages(totalPagesExp);
    }
  };

  tabelas = (tables, options = {}) => {
    const treat = (table, i) => {
      if (typeof table == 'function') return table;
      function limpaNullsEUndefineds(arr) {
        if (Array.isArray(arr)) arr = arr.map(limpaNullsEUndefineds);
        if (arr === null || arr === undefined) return '';
        if (typeof arr == 'object' && 'content' in arr) arr.content ??= '';
        return arr;
      }
      ['head', 'body', 'foot'].forEach(k => (k in table) && (table[k] = limpaNullsEUndefineds(table[k])));
      const result = _.defaultsDeep({}, table, {
        styles: {
          font: 'Proxima Nova Font'
        },
        margin: { top: 18 },
        headStyles: DefaultReport.headStyles,
        footStyles: {
          fillColor: DefaultReport.cores.azulImpares,
          lineWidth: 0.1
        },
        alternateRowStyles: {
          fillColor: DefaultReport.cores.cinzaImpares
        },
        bodyStyles: DefaultReport.bodyStyles,
        theme: 'grid',
      }, i == 0 ? { startY: this.finalY + (table.margin?.top ?? 18) / 3 } : {});
      if (this.#customOptions.pagination)
        if ('didDrawPage' in result) {
          result.didDrawPage = (data) => {
            return this.prepPaginar(), result.didDrawPage(data);
          }
        }
        else
          result.didDrawPage = () => this.prepPaginar();
      return result;
    };
    tables.forEach((e, i) => {
      if (typeof e === 'function') {
        e = e();
        if (!e) return;
      }
      e = treat(e, i);
      if (options.pageBreakBetweenTables && i > 0) {
        this.addPage();
        this.prepPaginar();
      }
      if (!e?.margin?.top)
        _.set(e, 'margin.top', this.#customOptions.marginY);
      if (!e?.margin?.bottom)
        _.set(e, 'margin.bottom', this.#customOptions.marginY);
      if (!e?.margin?.left)
        _.set(e, 'margin.left', this.#customOptions.marginX);
      if (!e?.margin?.right)
        _.set(e, 'margin.right', this.#customOptions.marginX);
      this.autoTable(e);
      this.#lastMargin = this.lastAutoTable.settings.margin.bottom;
      this.finalY = this.lastAutoTable.finalY;
    });
    return this;
  }

  assinaturas = (labels, options) => {
    const defaultOptions = {
      maxItensPerRow: 3,
      labelHeight: 5,
      textColor: 'black',
      fontSize: 12,
      fontFamily: 'Proxima Nova Font',
      fontStyle: 'normal',
      margin: { top: 24, bottom: 14, left: 14, right: 14, betweenCols: 12, betweenRows: 14 },
      lineWidth: 50,
      contentAlign: 'center', // start, end, center, justify 
    };
    options = _.defaultsDeep({}, options, defaultOptions);
    if (!['start', 'end', 'center', 'justify'].includes(options.contentAlign))
      options.contentAlign = 'justify';
    labels = JSON.parse(JSON.stringify(labels)); // copia as labels
    if (!Array.isArray(labels))
      labels = [labels];
    let algumArray = false;
    labels = labels.map(e => {
      if (Array.isArray(e)) {
        algumArray = true;
        return e;
      }
      return [e];
    });
    if (!algumArray)
      labels = [labels];
    labels = labels.flatMap(e => _.chunk(e, options.maxItensPerRow));
    this.setTextColor(options.textColor)
      .setFontSize(options.fontSize)
      .setFont(options.fontFamily, options.fontStyle);
    labels.forEach((row, i) => {
      let startY = this.finalY + Math.max(this.#lastMargin, i == 0 ? options.margin.top : options.margin.betweenRows);
      if (startY + options.labelHeight + options.margin.bottom > this.internal.pageSize.height) {
        this.addPage();
        if (this.#customOptions.pagination)
          this.prepPaginar();
        startY = options.margin.top;
      }
      this.#lastMargin = i == labels.length - 1 ? options.margin.bottom : options.margin.betweenRows;
      this.finalY = startY + options.labelHeight;

      let betweenCols, startX, fullWidth;
      const available = this.internal.pageSize.width - options.margin.left - options.margin.right;
      if (options.contentAlign === 'justify')
        betweenCols = (available - options.lineWidth * row.length) / (row.length - 1);
      else
        betweenCols = options.margin.betweenCols
      fullWidth = options.lineWidth * row.length + betweenCols * (row.length - 1);
      if (options.contentAlign === 'start' || options.contentAlign === 'justify')
        startX = options.margin.left;
      else if (options.contentAlign === 'end')
        startX = options.margin.left + available - fullWidth;
      else
        startX = options.margin.left + (available - fullWidth) / 2;
      row.forEach(label => {
        this.line(startX, startY, startX + options.lineWidth, startY)
          .text(label, startX + (options.lineWidth / 2), startY + options.labelHeight, { align: 'center' });
        startX += options.lineWidth + betweenCols;
      });
    });
    return this;
  }
}

// export default DefaultReport;
window.DefaultReport = DefaultReport;
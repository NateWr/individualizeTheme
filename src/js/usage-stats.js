/**
 * Adapt the style of the article stats chart
 * to match the theme settings
 *
 * This adapts the bar/line chart added to the article landing
 * page by the Usage Stats plugin. These chart options match
 * the ChartJS version loaded by the plugin (v2.9.4)
 *
 * @see plugins/generic/usageStats/js/UsageStatsFrontendHandler.js
 * @see https://www.chartjs.org/docs/2.9.4/
 */
const init = () => {
  document.addEventListener('usageStatsChartOptions.pkp', (e) => {
    if (
      !e?.chartOptions?.elements?.rectangle
      || !e?.chartOptions?.elements?.line
      || !e?.chartOptions?.tooltips
      || !e?.chartOptions?.scales?.xAxes?.length
      || !e?.chartOptions?.scales?.xAxes[0]?.gridLines
      || !e?.chartOptions?.scales?.yAxes?.length
      || !e?.chartOptions?.scales?.yAxes[0]?.gridLines
    ) {
      return
    }
    const bodyStyle = window.getComputedStyle(document.body)
    const rootStyle = window.getComputedStyle(document.documentElement)
    e.chartOptions.elements.rectangle.backgroundColor = bodyStyle.getPropertyValue('--color-page-links')
    e.chartOptions.elements.line.borderColor = bodyStyle.getPropertyValue('--color-page-links')
    e.chartOptions.elements.line.backgroundColor = bodyStyle.getPropertyValue('--color-page-links')
    e.chartOptions.tooltips.titleColor = rootStyle.getPropertyValue('--color-overlay-text')
    e.chartOptions.tooltips.bodyColor = rootStyle.getPropertyValue('--color-overlay-text')
    e.chartOptions.tooltips.footerColor = rootStyle.getPropertyValue('--color-overlay-text')
    e.chartOptions.tooltips.backgroundColor = rootStyle.getPropertyValue('--color-overlay-background')
    e.chartOptions.tooltips.borderColor = rootStyle.getPropertyValue('--color-overlay-text')
    e.chartOptions.tooltips.borderWidth = 2
    e.chartOptions.scales.xAxes[0].gridLines.color = bodyStyle.getPropertyValue('--color-page-text')
    e.chartOptions.scales.xAxes[0].gridLines.lineWidth = 0.25
    e.chartOptions.scales.xAxes[0].gridLines.zeroLineColor = bodyStyle.getPropertyValue('--color-page-text')
    e.chartOptions.scales.yAxes[0].gridLines.color = bodyStyle.getPropertyValue('--color-page-text')
    e.chartOptions.scales.yAxes[0].gridLines.lineWidth = 0.25
    e.chartOptions.scales.yAxes[0].gridLines.zeroLineColor = bodyStyle.getPropertyValue('--color-page-text')
    e.chartOptions.scales.xAxes[0].ticks = {
      ...e.chartOptions.scales.xAxes[0]?.ticks ?? {},
      fontFamily: bodyStyle.getPropertyValue('--font-base'),
      fontColor: bodyStyle.getPropertyValue('--color-page-text'),
    }
    e.chartOptions.scales.yAxes[0].ticks = {
      ...e.chartOptions.scales.yAxes[0]?.ticks ?? {},
      fontFamily: bodyStyle.getPropertyValue('--font-base'),
      fontColor: bodyStyle.getPropertyValue('--color-page-text'),
    }
  })
}

export default {
  init
}
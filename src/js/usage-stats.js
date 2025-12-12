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
      !e?.chartOptions?.elements?.bar
      || !e?.chartOptions?.elements?.line
      || !e?.chartOptions?.scales
    ) {
      return
    }
    const bodyStyle = window.getComputedStyle(document.body)
    const rootStyle = window.getComputedStyle(document.documentElement)
    e.chartOptions.elements.bar.backgroundColor = bodyStyle.getPropertyValue('--color-page-links')
    e.chartOptions.elements.line.borderColor = bodyStyle.getPropertyValue('--color-page-links')
    e.chartOptions.elements.line.backgroundColor = bodyStyle.getPropertyValue('--color-page-links')
    const scale = {
      border: {
        lineWidth: 0.25,
        color: bodyStyle.getPropertyValue('--color-page-text'),
      },
      grid: {
        lineWidth: 0.25,
        color: bodyStyle.getPropertyValue('--color-page-text'),
      },
      ticks: {
        color: bodyStyle.getPropertyValue('--color-page-text'),
      }
    }
    e.chartOptions.scales.x = {...e.chartOptions.scales.x, ...scale}
    e.chartOptions.scales.y = {...e.chartOptions.scales.y, ...scale}
    e.chartOptions.plugins = {
      legend: {
        display: false,
      },
      tooltip: {
        titleColor: rootStyle.getPropertyValue('--color-overlay-text'),
        bodyColor: rootStyle.getPropertyValue('--color-overlay-text'),
        footerColor: rootStyle.getPropertyValue('--color-overlay-text'),
        backgroundColor: rootStyle.getPropertyValue('--color-overlay-background'),
        borderColor: rootStyle.getPropertyValue('--color-overlay-text'),
        borderWidth: 1,
        padding: 12,
      },
    }
  })
}

export default {
  init
}
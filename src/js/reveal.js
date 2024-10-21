/**
 * Show part of long texts with a read more button
 * that will show the full text when clicked.
 *
 * Add an element with the data-reveal attribute.
 *
 * <div data-reveal>
 *   ...
 * </div>
 *
 * Control the height by passing an int to a data-height attribute.
 *
 * <div data-reveal data-height="30">
 *   ...
 * </div>
 *
 * This will set the height to 30em, based on the `fontSize` of the
 * element.
 *
 * You can not nest a reveal block inside of another.
 */

const init = () => {
  let i = 0
  const $reveals = document.querySelectorAll('[data-reveal]')
  for (const $reveal of $reveals) {
    const height = parseInt($reveal.dataset?.height ?? '20', 10)
    const fontSize = window.getComputedStyle($reveal).getPropertyValue('font-size')
    const maxHeight = height * (parseInt(fontSize.replace('px', ''), 10))
    if ($reveal.clientHeight <= maxHeight) {
      continue
    }
    i++
    $reveal.id = `reveal-${i}`
    $reveal.tabIndex = -1
    const $button = document.createElement('button')
    $button.setAttribute('aria-controls', `#${$reveal.id}`)
    $button.dataset.revealLink = true
    $button.innerHTML = `
      ${window?.slubTheme?.i18n?.reveal}
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
        <path d="M480-371.69 267.69-584 296-612.31l184 184 184-184L692.31-584 480-371.69Z"/>
      </svg>
    `

    $button.addEventListener('click', function() {
      $reveal.dataset.open = true
    })

    $reveal.appendChild($button)
    $reveal.style.maxHeight = `${maxHeight}px`
  }
}

export default {
  init
}
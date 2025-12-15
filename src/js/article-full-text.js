/**
 * Add functionality for the table of contents
 * on article pages with full text HTML
 */
import debounce from "debounce";

const init = () => {
  const $fullText = document.querySelector('[individualize-full-text]')
  const $tableOfContents = document.querySelector('[individualize-table-of-contents]')

  if (!$fullText || !$tableOfContents) {
    return;
  }

  initFixedPanel($fullText, $tableOfContents)
  initToggle($tableOfContents)
  setTop($tableOfContents)
  addEventListener('resize', debounce(() => {
    setTop($tableOfContents)
  }, 300))}

/**
 * Initialize the "sticky" table of contents panel
 * that appears as you scroll down the article
 */
const initFixedPanel = ($fullText, $tableOfContents) => {

  if (!('IntersectionObserver' in window) ||
    !('IntersectionObserverEntry' in window) ||
    !('intersectionRatio' in window.IntersectionObserverEntry.prototype) ||
    ('ResizeObserver' in window === false)) {
      return
  }

  let isVisible = false

  const show = () => {
    isVisible = true
    $tableOfContents.dataset.show = true
  }

  const hide  = () => {
    isVisible = false
    $tableOfContents.dataset.show = false
  }

  const callback = entries => {
    entries.forEach(entry => {
      if (!isVisible && entry.isIntersecting) {
        show()
      } else if (isVisible && !entry.isIntersecting) {
        hide()
      }
    })
  }

  const ob = new IntersectionObserver(callback)
  ob.observe($fullText)
}

/**
 * Initialize the button to toggle the table of
 * contents open/close
 */
const initToggle = ($tableOfContents) => {

  const $button = $tableOfContents.querySelector('button[aria-controls]')
  const $panel = document.getElementById($button?.getAttribute('aria-controls'))

  if (!$button || !$panel) {
    return
  }

  $button.addEventListener('click', () => {
    $button.setAttribute(
      'aria-expanded',
      $button.getAttribute('aria-expanded') === 'true'
        ? false
        : true
    )
  })

  const $links = $panel.querySelectorAll('a')

  if (!$links) {
    return
  }

  $links.forEach($link => {
    $link.addEventListener('click', () => {
      $button.setAttribute('aria-expanded', false)
    })
  })
}

/**
 * Set the top position of the table of contents
 * so that it sits directly beneath any sticky
 * header.
 */
const setTop = ($tableOfContents) => {

  const clear = () => {
    $tableOfContents.style.top = ''
    $tableOfContents.style.maxHeight = ''
  }

  // On small devices, the table of contents is correctly
  // positioned through CSS code
  if (document.body.clientWidth < 1280) {
    clear()
    return
  }

  let $stickyHeader = document.querySelector([
    '.header-desktop-line .header-desktop-inner',
    '.header-desktop-default .header-desktop-nav-main',
    '.header-desktop-defaultCenter .header-desktop-nav-main',
  ].join(','))

  if (!$stickyHeader) {
    clear()
    return
  }

  $tableOfContents.style.top = `${$stickyHeader.clientHeight}px`
  $tableOfContents.style.maxHeight = `${window.innerHeight - $stickyHeader.clientHeight}px`
}

export default {
  init,
}

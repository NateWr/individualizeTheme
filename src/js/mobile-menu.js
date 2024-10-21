/**
 * Toggles the mobile navigation menu on/off
 *
 * Only handles the aria- attributes. The visible changes need to be
 * added in CSS.
 *
 * Usage:
 *
 * <button data-mobile-menu="example-id">
 *   Toggle
 * </button>
 * <div id="example-id">
 *   ...
 * </div>
 *
 * Use the following selector to style the button when open:
 *
 * [data-mobile-menu][aria-expanded="true"]
 *
 * Use the following selector to style the panel when open:
 *
 * [data-open="true"]
 */
import debounce from "debounce"

const overflowHiddenClass = 'overflow-hidden'

const set = ($button, $target, state) => {
  $button.setAttribute('aria-expanded', state)
  $target.dataset.open = state
  if (state) {
    document.body.className = ` ${overflowHiddenClass}`
  } else {
    document.body.className = document.body.className.replace(` ${overflowHiddenClass}`, '')
  }
}
const init = () => {
  const $button = document.querySelector('[data-mobile-menu]')
  if (!$button) {
    return
  }
  const target = $button.dataset?.mobileMenu
  if (!target) {
    return
  }
  const $target = document.querySelector(target)

  $button.id = `${target.replace('#', '')}-button`
  $button.setAttribute('aria-controls', target)
  $button.setAttribute('aria-expanded', false)
  set($button, $target, $button.getAttribute('aria-expanded') === 'true')

  $button.addEventListener('click', function() {
    set($button, $target, $button.getAttribute('aria-expanded') !== 'true')
  })

  $target.addEventListener('focusin', function() {
    set($button, $target, true)
  })

  $target.addEventListener('focusout', function() {
    setTimeout(() => {
      if (!$target.contains(document.activeElement)) {
        set($button, $target, false)
      }
    }, 500)
  })

  document.addEventListener('click', function(e) {
    if (!$button.contains(e.target) && !$target.contains(e.target)) {
      set($button, $target, false)
    }
  })

  addEventListener('resize', debounce(() => {
    if (document.body.clientWidth >= 1280) {
      set($button, $target, false)
    }
  }, 300))
}

export default {
  init
}
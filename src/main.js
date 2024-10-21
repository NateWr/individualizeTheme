/**
 * Import Bootstrap components
 *
 * Only import the components that we use in order to keep the
 * JavaScript and CSS bundles small.
 *
 * @see https://getbootstrap.com/docs/5.2/getting-started/vite/
 */
import Dropdown from 'bootstrap/js/dist/dropdown'
import './css/bootstrap.scss'

/**
 * Custom JS for the theme
 */
import reveal from './js/reveal'

/**
 * Custom CSS for the theme
 *
 * @see https://vite.dev/guide/features#css
 */
import './main.css'

/**
 * Initialize
 */
document.addEventListener('DOMContentLoaded',function() {
  reveal.init()
})
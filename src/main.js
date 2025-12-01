/**
 * Import Bootstrap components
 *
 * Only import the components that we use in order to keep the
 * JavaScript and CSS bundles small.
 *
 * @see https://getbootstrap.com/docs/5.2/getting-started/vite/
 */
import Collapse from 'bootstrap/js/dist/collapse'
import Dropdown from 'bootstrap/js/dist/dropdown'
import './css/bootstrap.scss'

/**
 * Custom JS for the theme
 */
import articleFullText from './js/article-full-text'
import citation from './js/citation'
import mobileMenu from './js/mobile-menu'
import reveal from './js/reveal'
import reviewerInterests from './js/reviewer-interests'
import usageStats from './js/usage-stats'

/**
 * Custom CSS for the theme
 *
 * @see https://vite.dev/guide/features#css
 */
import './main.css'

/**
 * Initialize
 */
document.addEventListener('DOMContentLoaded', () => {
  articleFullText.init()
  citation.init()
  mobileMenu.init()
  reveal.init()
  reviewerInterests.init()
  usageStats.init()
})
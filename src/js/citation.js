/**
 * Load a new citation format on the article landing page
 *
 * This script replaces the articleCitation.js file of the
 * CitationStyleLanguage plugin, which relies on jQuery in
 * order to work.
 */
const init = () => {
  const citation = document.getElementById('citationOutput');
  const links = [...document.querySelectorAll('[data-load-citation][data-json-href]')]
  if (!citation || !links.length) {
    return
  }

  links.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault()
      citation.style.opacity = 0.5
      const url = link.dataset['jsonHref']
      fetch(url, {dataType: 'json'})
        .then(r => r.json())
        .then(r => {
          if (!r?.content) {
            throw new Error(`Unable to load new citation format ${link.innerText}`)
          }
          return r.content
        })
        .then(html => citation.innerHTML = html)
        .finally(() => {
          citation.style.opacity = 1
        })
    })
  })
}

export default {
  init
}
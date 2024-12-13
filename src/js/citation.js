/**
 * Load a new citation format on the article landing page
 *
 * This script replaces the articleCitation.js file of the
 * CitationStyleLanguage plugin, which relies on jQuery in
 * order to work.
 */
const init = () => {
  const citations = [...document.querySelectorAll('[data-citation]')]

  if (!citations.length) {
    return
  }

  citations.forEach(citation => {
    const output = citation.querySelector('[data-citation-output]')
    const links = [...citation.querySelectorAll('[data-load-citation][data-json-href]')]

    if (!output || !links.length) {
      return
    }

    links.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault()
        output.style.opacity = 0.5
        const url = link.dataset['jsonHref']
        fetch(url, {dataType: 'json'})
          .then(r => r.json())
          .then(r => {
            if (!r?.content) {
              throw new Error(`Unable to load new citation format ${link.innerText}`)
            }
            return r.content
          })
          .then(html => output.innerHTML = html)
          .finally(() => {
            output.style.opacity = 1
          })
      })
    })
  })
}

export default {
  init
}
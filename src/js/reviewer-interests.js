/**
 * Toggle the reviewer interests field on/off in
 * the registration form.
 */
const toggle = ($checkbox, $input) => {
  $input.className = $checkbox.checked ? '' : 'hidden'
}

const init = () => {
  const $checkbox = document.getElementById('reviewerInterests')
  const $input = document.getElementById('reviewerInterestsInput')
  if (!$checkbox || !$input) {
    return
  }
  toggle($checkbox, $input)
  $checkbox.addEventListener('change', function() {
    toggle($checkbox, $input)
  })
}

export default {
  init
}
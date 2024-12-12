import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

export default class FormcycleSelectionElement {

  hiddenInputElement = null

  pageUid = 0

  constructor(itemFormElID = '', pageUid = 0) {
    this.hiddenInputElement = document.querySelector('#' + itemFormElID)
    this.pageUid = pageUid

    const hasError = document.querySelector('#xm-formcycle-forms .callout-danger')
    if (hasError) {
      console.debug('Formcycle configuration has errors, aborting')
      return
    }

    this.init()
  }

  init() {
    const formsWrapper = document.querySelector('#xm-available-forms-wrapper')
    if (!formsWrapper) {
      this.loadForms(TYPO3.settings.ajaxUrls.xm_formcycle_form_selection)
    } else {
      this.initFormEvents()
    }
  }

  loadForms(url) {
    const wrapper = document.querySelector('#xm-formcycle-forms')

    new AjaxRequest(url)
      .withQueryArguments({pageUid: this.pageUid})
      .get()
      .then(async function (response) {
        const resolved = await response.resolve();
        wrapper.innerHTML = resolved.html
      }).then(() => {
      this.initFormEvents()
    })
  }

  initFormEvents() {
    document.querySelectorAll('#xm-available-forms-wrapper .card').forEach(card => {
      card.addEventListener('click', (e) => {
        e.preventDefault()
        document.querySelectorAll('#xm-available-forms-wrapper .card').forEach(card => {
          card.classList.remove('active')
        })
        e.currentTarget.classList.add('active')
        this.hiddenInputElement.value = e.currentTarget.getAttribute('data-form-id')
      })
    })

    const loadingSpinner = document.querySelector('#xm-loading-spinner')
    const reloadButton = document.querySelector('#xm-reload-available-forms')
    const formsWrapper = document.querySelector('#xm-available-forms-wrapper')
    if (reloadButton) {
      reloadButton.addEventListener('click', e => {
        e.preventDefault()
        // hide form gallery, display loading spinner
        formsWrapper.classList.add('hidden')
        loadingSpinner.classList.remove('hidden')
        this.loadForms(TYPO3.settings.ajaxUrls.xm_formcycle_form_reload)

        // close settings if open
        settingsButton.classList.remove('active')
        wrapper.classList.remove('open-settings')
      })
    }


  }
}

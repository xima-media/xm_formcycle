for (const div of document.querySelectorAll('.xm-formcycle-ajax')) {
  const url = div.getAttribute('data-ajax-url')
  const errorText = div.getAttribute('data-error-text') || ''

  $.ajax({
    url: url,
    dataType: 'html',
    xhrFields: {
      withCredentials: true
    }
  }).done(data => {
    $(div).html(data)
  }).fail(() => {
    $(div).html(errorText)
  })
}

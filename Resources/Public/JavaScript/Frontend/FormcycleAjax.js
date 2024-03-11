for (const div of document.querySelectorAll('.xm-formcycle-ajax')) {
  const url = div.getAttribute('data-ajax-url')

  $.ajax({
    url: url,
    dataType: 'html',
    xhrFields: {
      withCredentials: true
    }
  }).done(data => {
    $(div).html(data)
  })
}

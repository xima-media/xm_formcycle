window.addEventListener("message", event => {
  document.querySelectorAll('iframe.xm-formcycle-iframe').forEach(iframe => {
    iframe.style.height = `${event.data.height}px`
  })
})

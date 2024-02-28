window.addEventListener("message", event => {
  const iframeUrl = event.data.url
  const iframeElement = document.querySelector(`iframe.xm-formcycle-iframe[src="${iframeUrl}"]`)
  if (iframeElement) {
    iframeElement.style.height = `${event.data.height}px`
  }
})

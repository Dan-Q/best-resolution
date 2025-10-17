(function(){
  // Preloads an image to ensure it's in the cache.
  // Returns a Promise that resolves when the image is loaded.
  function preloadImage(url){
    return new Promise((resolve, reject) => {
      const img = new Image();
      img.onload = resolve;
      img.onerror = reject;
      img.src = url;
    });
  }

  function bestResolutionButton(){
    const width = window.innerWidth;
    const height = window.innerHeight;
    if(width < 300 || width > 8000 || height < 300 || height > 4500) return;
    const url = `//best-resolution.danq.dev/img.php?w=${width}&h=${height}`;
    preloadImage(url).then(() => {
      for(let img of document.querySelectorAll('img[src*="//best-resolution.danq.dev/any.gif"], img[data-best-resolution-button]')){
        img.dataset.bestResolutionButton = 'true';
        img.src = url;
        img.alt = `Looks best at: ${width}×${height}`;
        if(img.closest('a')) return;
        const a = document.createElement('a');
        a.href = `//best-resolution.danq.dev/?from=${encodeURIComponent(window.location.href)}`;
        if(window.location.hostname != 'best-resolution.danq.dev') a.target = '_blank';
        a.rel = 'noopener noreferrer';
        a.appendChild(img.cloneNode(true));
        img.parentElement.replaceChild(a, img);
      }
    });
  }

  if(document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bestResolutionButton);
  } else {
    bestResolutionButton();
  }

  let resizeTimeout; // debounce for resize events
  window.addEventListener('resize', function(){
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function(){
      bestResolutionButton();
    }, 100);
  });
})();

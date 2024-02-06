var scrollToTopTimeout = null;
var lastScrollTop = window.scrollY || document.documentElement.scrollTop;

var topButtons = document.querySelector('#scroll');
topButtons.style.transform = 'rotate(180deg)';

topButtons.addEventListener('click', function () {
    var scroll = 0;

    if(topButtons.style.transform == 'rotate(180deg)') {
        scroll = document.documentElement.scrollHeight;
    }
      
    window.scroll({
        top: scroll,
        left: 0,
        behavior: "smooth"
    });
})

window.addEventListener('scroll', function () {
    const scrollTopPosition = window.scrollY || document.documentElement.scrollTop;

    if(topButtons.style.display == 'none') {
        topButtons.style.display = 'block';
    }

    if (scrollTopPosition > lastScrollTop) {
        topButtons.style.transform = 'rotate(180deg)';
    } else if (scrollTopPosition < lastScrollTop) {
        topButtons.style.transform = 'rotate(0deg)';
    }

    lastScrollTop = scrollTopPosition <= 0
        ? 0: scrollTopPosition;

    clearTimeout(scrollToTopTimeout)
    scrollToTopTimeout = setTimeout(() => {
        topButtons.style.display = 'none';
    }, 3000)
});
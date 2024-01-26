var scrollToTopTimeout = null;
var lastScrollTop = window.scrollY || document.documentElement.scrollTop;

var topButton = document.querySelector('#scrollTop');
var topButtonClass = topButton.classList;
topButton.addEventListener('click', function () {
    window.scroll({
        top: 0,
        left: 0,
        behavior: "smooth"
    });
})

var bottomButton = document.querySelector('#scrollBottom');
var bottomButtonClass = bottomButton.classList;
bottomButton.addEventListener('click', function () {
    window.scroll({
        top: document.documentElement.scrollHeight,
        left: 0,
        behavior: "smooth"
    });
})

window.addEventListener('scroll', function () {
    const scrollTopPosition = window.scrollY || document.documentElement.scrollTop;

    if (scrollTopPosition > lastScrollTop) {
        // scrolling down
        topButtonClass.remove('scroll-to-top-active')
        bottomButtonClass.add('scroll-to-top-active')
    } else if (scrollTopPosition < lastScrollTop) {
        // scrolling up
        bottomButtonClass.remove('scroll-to-top-active')
        topButtonClass.add('scroll-to-top-active')
    }

    lastScrollTop = scrollTopPosition <= 0
        ? 0: scrollTopPosition;

    clearTimeout(scrollToTopTimeout)
    scrollToTopTimeout = setTimeout(() => {
        topButtonClass.remove('scroll-to-top-active')
        bottomButtonClass.remove('scroll-to-top-active')
    }, 3000)
});

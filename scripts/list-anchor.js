$(document).ready(function () {
    const menuHeight = document.querySelector('.nav-top').clientHeight;
    const alphabetLinks = document.querySelectorAll('.byletter a');

    alphabetLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                const targetOffset = targetElement.getBoundingClientRect().top + window.scrollY;
                window.scrollTo({
                    top: targetOffset - menuHeight,
                    behavior: 'smooth'
                });
            }
        });
    });
});
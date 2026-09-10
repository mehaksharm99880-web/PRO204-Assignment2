document.addEventListener('DOMContentLoaded', function () {
    const navbar = document.querySelector('.navbar');
    const navigation = document.querySelector('#main-navigation');

    function updateNavbar() {
        navbar.classList.toggle('scrolled', window.scrollY > 30);
    }

    updateNavbar();
    window.addEventListener('scroll', updateNavbar, { passive: true });

    document.querySelectorAll('#main-navigation .nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (navigation.classList.contains('show')) {
                bootstrap.Collapse.getOrCreateInstance(navigation).hide();
            }
        });
    });
});
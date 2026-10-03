document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // Navbar scroll effect
    // =========================
    const navbar = document.querySelector('.navbar');
    const navigation = document.querySelector('#main-navigation');

    function updateNavbar() {
        if (navbar) {
            navbar.classList.toggle('scrolled', window.scrollY > 30);
        }
    }

    updateNavbar();

    window.addEventListener('scroll', updateNavbar, {
        passive: true
    });


    // =========================
    // Mobile navigation
    // =========================
    document.querySelectorAll(
        '#main-navigation .nav-link'
    ).forEach(function (link) {

        link.addEventListener('click', function () {

            if (
                navigation &&
                navigation.classList.contains('show')
            ) {
                bootstrap.Collapse
                    .getOrCreateInstance(navigation)
                    .hide();
            }

        });

    });


    // =========================
    // Contact form validation
    // =========================
    const contactForm =
        document.querySelector('#contactForm');

    if (contactForm) {

        contactForm.addEventListener(
            'submit',
            function (event) {

                let isValid = true;

                const name =
                    contactForm.querySelector('[name="name"]');

                const email =
                    contactForm.querySelector('[name="email"]');

                const service =
                    contactForm.querySelector(
                        '[name="service_id"]'
                    );

                const message =
                    contactForm.querySelector('[name="message"]');


                // Remove previous JavaScript errors
                contactForm
                    .querySelectorAll('.js-error')
                    .forEach(function (error) {
                        error.remove();
                    });


                // Remove previous invalid styling
                contactForm
                    .querySelectorAll('.is-invalid')
                    .forEach(function (input) {
                        input.classList.remove('is-invalid');
                    });


                // Name validation
                if (
                    name &&
                    name.value.trim() === ''
                ) {

                    showError(
                        name,
                        'Please enter your name.'
                    );

                    isValid = false;
                }


                // Email validation
                if (email) {

                    const emailPattern =
                        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (
                        email.value.trim() === ''
                    ) {

                        showError(
                            email,
                            'Please enter your email address.'
                        );

                        isValid = false;

                    } else if (
                        !emailPattern.test(
                            email.value.trim()
                        )
                    ) {

                        showError(
                            email,
                            'Please enter a valid email address.'
                        );

                        isValid = false;
                    }
                }


                // Service validation
                if (
                    service &&
                    (
                        service.value === '' ||
                        service.value === '0'
                    )
                ) {

                    showError(
                        service,
                        'Please select a service.'
                    );

                    isValid = false;
                }


                // Message validation
                if (
                    message &&
                    message.value.trim() === ''
                ) {

                    showError(
                        message,
                        'Please enter your message.'
                    );

                    isValid = false;
                }


                // Stop submission if validation fails
                if (!isValid) {
                    event.preventDefault();
                }

            }
        );
    }


    // =========================
    // Dynamic message counter
    // =========================
    const messageField =
        document.querySelector('[name="message"]');

    const messageCounter =
        document.querySelector('#messageCounter');

    if (
        messageField &&
        messageCounter
    ) {

        function updateMessageCounter() {

            const currentLength =
                messageField.value.length;

            const maxLength =
                messageField.getAttribute('maxlength') || 5000;

            messageCounter.textContent =
                currentLength +
                ' / ' +
                maxLength +
                ' characters';
        }

        updateMessageCounter();

        messageField.addEventListener(
            'input',
            updateMessageCounter
        );
    }


    // =========================
    // Service category filter
    // =========================
    const filterButtons =
        document.querySelectorAll('.service-filter');

    const serviceItems =
        document.querySelectorAll('.service-item');


    if (
        filterButtons.length > 0 &&
        serviceItems.length > 0
    ) {

        filterButtons.forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const selectedCategory =
                        button.dataset.category;


                    // Update active button
                    filterButtons.forEach(
                        function (filterButton) {

                            filterButton.classList.remove(
                                'active'
                            );

                            filterButton.classList.remove(
                                'btn-dark'
                            );

                            filterButton.classList.add(
                                'btn-outline-dark'
                            );

                            filterButton.setAttribute(
                                'aria-pressed',
                                'false'
                            );
                        }
                    );


                    button.classList.add('active');

                    button.classList.remove(
                        'btn-outline-dark'
                    );

                    button.classList.add('btn-dark');

                    button.setAttribute(
                        'aria-pressed',
                        'true'
                    );


                    // Show/hide services
                    serviceItems.forEach(
                        function (service) {

                            const serviceCategory =
                                service.dataset.category;

                            if (
                                selectedCategory === 'all' ||
                                serviceCategory === selectedCategory
                            ) {

                                service.style.display = '';

                            } else {

                                service.style.display = 'none';

                            }

                        }
                    );

                }
            );

        });

    }


    // =========================
    // Helper function
    // =========================
    function showError(input, message) {

        const error =
            document.createElement('div');

        error.className =
            'text-danger small mt-1 js-error';

        error.textContent = message;

        input.insertAdjacentElement(
            'afterend',
            error
        );

        input.classList.add('is-invalid');
    }

});
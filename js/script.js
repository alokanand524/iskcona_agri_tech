// JS/script.js content
$(document).ready(function () {
    // Smooth scrolling for navigation links
    $('a[href^="#"]').on('click', function (event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80
            }, 1000);
        }
    });

    // Navbar background change on scroll
    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('.navbar').addClass('bg-white shadow-sm');
        } else {
            $('.navbar').removeClass('bg-white shadow-sm');
        }
    });

    // Scroll to top button functionality
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('#scrollTopBtn').addClass('show');
        } else {
            $('#scrollTopBtn').removeClass('show');
        }
    });

    $('#scrollTopBtn').click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    // Animation on scroll
    function animateOnScroll() {
        $('.fade-in, .slide-up').each(function () {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animate');
            }
        });
    }

    // Initial animation check
    animateOnScroll();

    // Animation on scroll
    $(window).scroll(function () {
        animateOnScroll();
    });

    // Contact form submission
    $('.contact-form').on('submit', function (e) {
        e.preventDefault();

        // Get form data
        var name = $(this).find('input[type="text"]').val();
        var email = $(this).find('input[type="email"]').val();
        var message = $(this).find('textarea').val();

        // Simple validation
        if (name && email && message) {
            alert('Thank you for your message! We will get back to you soon.');
            $(this)[0].reset();
        } else {
            alert('Please fill in all fields.');
        }
    });

    // Parallax effect for hero section
    $(window).scroll(function () {
        var scrolled = $(this).scrollTop();
        var parallax = $('.hero-section');
        var speed = scrolled * 0.5;

        parallax.css('background-position', 'center ' + speed + 'px');
    });

    // Add loading animation to buttons
    $('.btn').on('click', function () {
        var $btn = $(this);
        var originalText = $btn.text();

        if (!$btn.hasClass('loading')) {
            $btn.addClass('loading');
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...');

            setTimeout(function () {
                $btn.removeClass('loading');
                $btn.html(originalText);
            }, 2000);
        }
    });

    // Navbar collapse on mobile
    $('.navbar-nav .nav-link').on('click', function () {
        $('.navbar-collapse').collapse('hide');
    });

    // Initialize tooltips if Bootstrap tooltips are needed
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add hover effects to cards
    $('.feature-card, .service-card, .plant-card').hover(
        function () {
            $(this).find('.feature-icon, .service-icon').addClass('animate__pulse');
        },
        function () {
            $(this).find('.feature-icon, .service-icon').removeClass('animate__pulse');
        }
    );

    // Image lazy loading effect
    $('img').on('load', function () {
        $(this).addClass('loaded');
    });

    // Counter animation for statistics (if needed)
    function animateCounter(element, target) {
        var current = 0;
        var increment = target / 100;
        var timer = setInterval(function () {
            current += increment;
            $(element).text(Math.floor(current));
            if (current >= target) {
                $(element).text(target);
                clearInterval(timer);
            }
        }, 20);
    }

    // Initialize animations on page load
    setTimeout(function () {
        $('.fade-in').first().addClass('animate');
    }, 500);
});


// /\//\\/\\/\//\\/\/\/\/\/\//\/\/\/\/\/\/\/\/\\//\/\\\/\/\/\

// nav bar visit indication 
  const links = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname.split("/").pop();

    links.forEach(link => {
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        }
    });


// #_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#_#

//Js for Pagination on insecticisde, pasticide page 
document.addEventListener("DOMContentLoaded", function () {
    const itemsPerPage = 4;
    const items = document.querySelectorAll(".paginated-item");
    const totalPages = Math.ceil(items.length / itemsPerPage);
    const pagination = document.getElementById("pagination");

    function showPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        items.forEach((item, index) => {
            item.parentElement.style.display = (index >= start && index < end) ? "block" : "none";
        });

        // Highlight current page
        document.querySelectorAll(".page-link").forEach(link => link.classList.remove("active"));
        document.querySelector(`.page-link[data-page="${page}"]`).classList.add("active");
    }

    function setupPagination() {
        pagination.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement("li");
            li.classList.add("page-item");

            const a = document.createElement("a");
            a.classList.add("page-link");
            a.href = "#";
            a.textContent = i;
            a.dataset.page = i;

            a.addEventListener("click", function (e) {
                e.preventDefault();
                showPage(i);
            });

            li.appendChild(a);
            pagination.appendChild(li);
        }
    }

    setupPagination();
    showPage(1); // Show first page initially
});
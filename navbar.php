<?php include 'load_lang.php'; ?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ISKCONA Agri Tech</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        .dropdown-menu.show {
            display: block;
        }

        .lang-dropdown {
            position: absolute;
            right: 20px;
            top: 60px;
            z-index: 9999;
            min-width: 150px;
        }
    </style>
</head>

<body>

    <!-- ✅ Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="#">
                <img src="src/iskcona_logo.png" alt="ISKCONA AGRI TECH Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><?= __('Home') ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="insecticides.php"><?= __('Insecticides') ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="fungicides.php"><?= __('Fungicides') ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="herbicides.php"><?= __('Herbicides') ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="blog.php"><?= __('Blog') ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php"><?= __('About') ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php"><?= __('Contact') ?></a></li>

                    <!-- 🌐 Language Button -->
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="#" id="langBtn" onclick="toggleLangDropdown(event)">
                            🌐 <?= strtoupper($_SESSION['lang'] ?? 'EN') ?>
                        </a>

                        <!-- Custom Language Dropdown -->
                        <div id="langDropdown" class="lang-dropdown bg-white border rounded shadow-sm p-2"
                            style="display: none;">
                            <form method="get" action="">
                                <select class="form-select form-select-sm" name="lang" onchange="this.form.submit()">
                                    <option value="en" <?= ($_SESSION['lang'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
                                    <option value="hi" <?= ($_SESSION['lang'] ?? 'en') === 'hi' ? 'selected' : '' ?>>हिन्दी</option>
                                </select>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- JS -->
    <script>
        function toggleLangDropdown(event) {
            event.preventDefault();
            const menu = document.getElementById("langDropdown");
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }

        document.addEventListener("click", function (e) {
            const langBtn = document.getElementById("langBtn");
            const langDropdown = document.getElementById("langDropdown");
            if (!langBtn.contains(e.target) && !langDropdown.contains(e.target)) {
                langDropdown.style.display = "none";
            }
        });

        // Mobile navbar close after clicking a link
        document.addEventListener('DOMContentLoaded', function () {
            const links = document.querySelectorAll('.nav-link');
            const collapse = document.querySelector('.navbar-collapse');
            links.forEach(link => {
                link.addEventListener('click', () => {
                    if (collapse.classList.contains('show')) {
                        new bootstrap.Collapse(collapse).toggle();
                    }
                });
            });
        });
    </script>

    <div id="google_translate_element" style="display:none;"></div>

<script>
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'en',
      includedLanguages: 'en,hi',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
      autoDisplay: false
    }, 'google_translate_element');
  }
</script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

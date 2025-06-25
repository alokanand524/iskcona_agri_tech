<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISKCONA AGRI TECH - to cure infected plants</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
   <!-- Navigation -->
   <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100 pt-5">
                <div class="col-lg-6">
                    <div class="hero-content fade-in">
                        <h1 class="display-4 fw-bold mb-4">Cultivating new Crops</h1>
                        <p class="lead hero-para mb-4">Discover the power of nature's. Ancient wisdom meets modern
                            wellness through carefully sourced medicine for plants.</p>
                        <div class="hero-buttons">
                            <!-- <a href="#services" class="btn btn-success btn-lg me-3">Explore Services</a> -->
                            <a href="about.html" class="btn btn-outline-success btn-lg">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image fade-in">
                        <img src="src/hero_img.png"
                            alt="Medicinal Plants" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="section-title">Our Services</h2>
                    <p class="text-muted">Comprehensive plant medicine services tailored to your wellness journey.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="service-card slide-up">
                        <div class="service-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4>Consultations</h4>
                        <p>Personalized consultations to understand your health needs and recommend appropriate plant
                            medicines.</p>
                        <a href="#" class="btn btn-outline-success btn-sm">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card slide-up">
                        <div class="service-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h4>Custom Formulations</h4>
                        <p>Specially crafted herbal formulations designed for your specific health conditions and goals.
                        </p>
                        <a href="#" class="btn btn-outline-success btn-sm">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card slide-up">
                        <div class="service-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4>Education</h4>
                        <p>Workshops and courses on plant medicine, herbalism, and natural healing practices.</p>
                        <a href="#" class="btn btn-outline-success btn-sm">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plants Section -->
    <section id="plants" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="section-title">Out Products</h2>
                    <!-- <p class="text-muted">Explore some of the powerful healing medicine plants.</p> -->
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-5">
                    <div class="plant-card slide-up">
                        <a href="insecticides.html">
                            <img src="src/insecticide5.png" alt="Ginger" class="card-img-top">
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-5">
                    <div class="plant-card slide-up">
                        <a href="insecticides.html">
                            <img src="src/insecticide7.png" alt="Echinacea" class="card-img-top">
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-5">
                    <div class="plant-card slide-up">
                        <a href="insecticides.html">
                            <img src="src/insecticide6.png" alt="Lavender" class="card-img-top">
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-5">
                    <div class="plant-card slide-up">
                        <a href="insecticides.html">
                            <img src="src/insecticide8.png" alt="Lavender" class="card-img-top">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


   <!-- Footer -->
   <?php include 'footer.php'; ?>

    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn" class="scroll-top-btn">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Custom JS -->
    <script src="js/script.js"></script>

</body>

</html>
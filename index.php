<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>ISKCONA AGRI TECH - to cure infected plants</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="src/favicon_io/favicon.ico" type="image/x-icon">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: url(src/sq.png) no-repeat center center/cover;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            border-bottom-left-radius: 0%;
            border-bottom-right-radius: 0%;
            margin-top: 3rem;
        }

        .hero-content {
            display: flex;
            align-items: center;
            gap: 4rem;
            position: relative;
            z-index: 2;
        }

        .hero-text {
            flex: 1;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(2px);
            padding: 4rem 5rem;
            border-radius: 20px;
        }

        .hero-text h1 {
            font-size: 4rem;
            font-weight: bold;
            color: var(--light-color);
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            line-height: 1.1;
        }

        .hero-text .highlight {
            color: green;
        }

        .hero-text h2 {
            font-size: 2.5rem;
            color: var(--light-color);
            margin-bottom: 2rem;
            font-weight: 400;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .hero-description {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--light-color);
            color: var(--primary-color);
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            color: var(--primary-color);
        }

        .cta-button::after {
            content: '→';
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .cta-button:hover::after {
            transform: translateX(5px);
        }



        /* Animations */
        .fade-in {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {

            .cta-button {
                padding: .5rem 1rem;
            }

            .hero-text h1 {
                font-size: 2rem;
            }

            .hero-text h2 {
                font-size: 1.5rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .hero-text {
                padding: 1rem 2rem;
            }

            .hero-section{
                margin-top: 1rem;
            }
        }

        @media (max-width: 770px){
                .cta-button {
                padding: .5rem 1rem;
            }

            .hero-text h1 {
                font-size: 2rem;
            }

            .hero-text h2 {
                font-size: 1.5rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .hero-text {
                padding: 1rem 2rem;
            }


        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <!-- <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100 pt-5">
                <div class="col-lg-6">
                    <div class="hero-content fade-in">
                        <h1 class="display-4 fw-bold mb-4">Cultivating new Crops</h1>
                        <p class="lead hero-para mb-4">Discover the power of nature's. Ancient wisdom meets modern
                            wellness through carefully sourced medicine for plants.</p>
                        <div class="hero-buttons">
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
    </section> -->

    <!-- Hero Section -->
    <section class="hero-section" id="home">

        <div class="container">
            <div class="hero-content fade-in">
                <div class="hero-text">
                    <h1>ISKCONA AGRI TECH : <span class="highlight">The Solution</span></h1>
                    <h2>For Your Tomorrow's Agriculture</h2>
                    <p class="hero-description">
                        Welcome to <strong>ISKCONA AGRI TECH PVT. LTD.</strong> where we cultivate innovation and grow
                        sustainable futures. Discover the latest in agriculture, from expert insights to cutting-edge
                        practices. Join us in nurturing a greener world.
                    </p>
                    <a href="#services" class="cta-button">Get Started Now</a>
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
                        <h4>Workshops</h4>
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
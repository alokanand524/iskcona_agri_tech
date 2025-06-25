<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insecticide - ISKCONA AGRI TECH</title>

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

    <!-- Plants Section -->
    <section id="plants" class="py-5 bg-light contact">
        <div class="container">
            <!-- <div class="row plant-margin">
                <div class="col-lg-8 mx-auto text-center mb-5 insect">
                    <h2 class="section-title">Insecticide for Plants</h2>
                    <p class="text-muted">Explore some of the powerful insecticide which heal the plants.</p>
                </div>
            </div> -->

            <div class="row plant-margin position-relative bg-glass-wrapper">
                <div class="position-absolute bg-blur-image"></div>

                <div class="col-lg-8 mx-auto text-center insect position-relative glass-effect">
                    <h2 class="section-title">Insecticide for Plants</h2>
                    <p class="text-muted">Explore some of the powerful insecticide which heal the plants.</p>
                </div>
            </div>


            <div class="col-lg-8 mx-auto text-center mb-3 mt-5">
                <h2 class="section-title2">Products -</h2>
            </div>


            <div class="row g-4">
                <div id="product-container" class="row g-4">
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide5.png" alt="Ginger" class="card-img-top">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide7.png" alt="Echinacea" class="card-img-top">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide6.png" alt="Lavender" class="card-img-top">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide8.png" alt="Lavender" class="card-img-top">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide5.png" alt="Lavender" class="card-img-top">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide4.png" alt="Lavender" class="card-img-top">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide3.png" alt="Lavender" class="card-img-top">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide2.png" alt="Lavender" class="card-img-top">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="plant-card slide-up paginated-item">
                            <img src="src/insecticide1.png" alt="Lavender" class="card-img-top">
                        </div>
                    </div>
                </div>



            </div>

            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination" id="pagination">
                        <!-- Pagination buttons will be dynamically added -->
                    </ul>
                </nav>
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
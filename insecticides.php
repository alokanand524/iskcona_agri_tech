<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->connect();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Get products
try {
    $count_stmt = $db->prepare("SELECT COUNT(*) as total FROM products WHERE category = 'insecticide' AND is_active = 1");
    $count_stmt->execute();
    $total_products = $count_stmt->fetch()['total'];
    $total_pages = ceil($total_products / $per_page);

    $stmt = $db->prepare("SELECT * FROM products WHERE category = 'insecticide' AND is_active = 1 ORDER BY id DESC, created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $per_page, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
    $total_pages = 0;
}
?>



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
                    <?php if (empty($products)): ?>
                        <div class="col-12 text-center">
                            <p class="text-muted">No insecticide products available at the moment.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="plant-card slide-up paginated-item" data-bs-toggle="modal"
                                    data-bs-target="#productModal" data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                    data-image="/iskcona_agri_tech/uploads/<?php echo htmlspecialchars($product['image_url']); ?>"
                                    data-price="<?php echo number_format($product['price'], 2); ?>"
                                    data-ingredient="<?php echo htmlspecialchars($product['active_ingredient']); ?>"
                                    data-dosage="<?php echo htmlspecialchars($product['dosage']); ?>"
                                    data-pests="<?php echo htmlspecialchars($product['target_pests']); ?>"
                                    data-method="<?php echo htmlspecialchars($product['application_method']); ?>">
                                    <img src="/iskcona_agri_tech/uploads/<?php echo htmlspecialchars($product['image_url']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-img-top"
                                        style="height: 220px; object-fit: cover; border-radius: 10px;">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>




            </div>

            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination" id="pagination">
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <!-- Product Detail Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProductName">Product Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-5 text-center">
                        <img id="modalProductImage" src="" alt="Product Image" class="img-fluid rounded">
                    </div>
                    <div class="col-md-7">
                        <!-- <p><strong>Price:</strong> ₹<span id="modalProductPrice"></span></p> -->
                        <p><strong>Description:</strong><br><span id="modalProductDescription"></span></p>
                        <p><strong>Dosage:</strong> <span id="modalProductDosage"></span></p>
                        <p><strong>Ingredient:</strong> <span id="modalProductIngredient"></span></p>
                        <!-- <p><strong>Target Pests:</strong> <span id="modalProductPests"></span></p> -->
                        <!-- <p><strong>Application Method:</strong> <span id="modalProductMethod"></span></p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>


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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const productCards = document.querySelectorAll('.plant-card');

            productCards.forEach(card => {
                card.addEventListener('click', function () {
                    document.getElementById('modalProductName').textContent = card.getAttribute('data-name');
                    document.getElementById('modalProductDescription').textContent = card.getAttribute('data-description');
                    // document.getElementById('modalProductPrice').textContent = card.getAttribute('data-price');
                    document.getElementById('modalProductIngredient').textContent = card.getAttribute('data-ingredient');
                    document.getElementById('modalProductDosage').textContent = card.getAttribute('data-dosage');
                    // document.getElementById('modalProductPests').textContent = card.getAttribute('data-pests');
                    // document.getElementById('modalProductMethod').textContent = card.getAttribute('data-method');
                    document.getElementById('modalProductImage').src = card.getAttribute('data-image');
                });
            });
        });
    </script>

</body>

</html>
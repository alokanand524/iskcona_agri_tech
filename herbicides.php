<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->connect();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

try {
    $count_stmt = $db->prepare("SELECT COUNT(*) as total FROM products WHERE category = 'herbicide' AND is_active = 1");
    $count_stmt->execute();
    $total_products = $count_stmt->fetch()['total'];
    $total_pages = ceil($total_products / $per_page);

    $stmt = $db->prepare("SELECT * FROM products WHERE category = 'herbicide' AND is_active = 1 ORDER BY id DESC, created_at DESC LIMIT ? OFFSET ?");
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
    <title>herbicide - ISKCONA AGRI TECH</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="src/favicon_io/favicon.ico" type="image/x-icon">
    <link href="css/style.css" rel="stylesheet">
        <style>
        .bg-glass-wrapper {
            position: relative;
            overflow: hidden;
            min-height: 300px;
            margin-top: -2rem;
        }

        .bg-blur-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background-color: blue; */
            background-image: url('src/herbicide_bg.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(2px);
            opacity: 0.6;
            z-index: 0;
            border-bottom-left-radius: 60%;
            border-bottom-right-radius: 100%;
        }

        .insect {
            position: relative;
            padding: 3rem 3rem 12rem 3rem;
            border-bottom-left-radius: 60%;
            border-bottom-right-radius: 100%;
            width: 100%;
            text-align: center;
            z-index: 1;
            /* must be above background */
        }

        .section-title {
            font-size: 3rem;
            color: #333;
        }

        .text-muted {
            color: #666;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <section id="plants" class="py-5 bg-light contact">
        <div class="container">
            <!-- <div class="row plant-margin position-relative bg-glass-wrapper">
                <div class="position-absolute bg-blur-image"></div>
                <div class="col-lg-8 mx-auto text-center insect position-relative glass-effect">
                    <h2 class="section-title">herbicide for Plants</h2>
                    <p class="text-muted">Explore powerful herbicides that protect your crops from fungal diseases.</p>
                </div>
            </div> -->

            <div class="bg-glass-wrapper">
                <div class="bg-blur-image"></div>
                <div class="insect">
                    <h2 class="section-title">Herbicide for Plants</h2>
                    <p class="text-muted">Explore powerful herbicides that protect your crops from fungal diseases.</p>
                </div>
            </div>

            <div class="col-lg-8 mx-auto text-center mb-3 mt-5">
                <h2 class="section-title2">Products -</h2>
            </div>

            <div class="row g-4">
                <div id="product-container" class="row g-4">
                    <?php if (empty($products)): ?>
                        <div class="col-12 text-center">
                            <p class="text-muted">No herbicide products available at the moment.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="plant-card slide-up paginated-item" data-bs-toggle="modal"
                                    data-bs-target="#productModal" data-id="<?php echo $product['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                    data-image="./uploads/<?php echo htmlspecialchars($product['image_url']); ?>">
                                    <img src="./uploads/<?php echo htmlspecialchars($product['image_url']); ?>"
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
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <!-- Modal for Product Detail -->
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
                        <p><strong>Description:</strong><br><span id="modalProductDescription"></span></p>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="ingredientTable">
                                <thead>
                                    <tr>
                                        <th>CROP</th>
                                        <th>A.I.</th>
                                        <th>Dosage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows filled by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <button id="scrollTopBtn" class="scroll-top-btn">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/script.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const productCards = document.querySelectorAll('.plant-card');

            productCards.forEach(card => {
                card.addEventListener('click', function () {
                    document.getElementById('modalProductName').textContent = card.getAttribute('data-name');
                    document.getElementById('modalProductDescription').textContent = card.getAttribute('data-description');
                    document.getElementById('modalProductImage').src = card.getAttribute('data-image');

                    const productId = card.getAttribute('data-id');

                    const tbody = document.querySelector('#ingredientTable tbody');
                    tbody.innerHTML = '<tr><td colspan="3">Loading...</td></tr>';

                    fetch('get_ingredients.php?product_id=' + productId)
                        .then(res => res.json())
                        .then(data => {
                            tbody.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(row => {
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                <td>${row.crop}</td>
                                <td>${row.active_ingredient}</td>
                                <td>${row.dosage}</td>
                            `;
                                    tbody.appendChild(tr);
                                });
                            } else {
                                tbody.innerHTML = '<tr><td colspan="3" class="text-muted">No ingredients found.</td></tr>';
                            }
                        })
                        .catch(() => {
                            tbody.innerHTML = '<tr><td colspan="3" class="text-danger">Failed to load ingredients.</td></tr>';
                        });
                });
            });
        });
    </script>

</body>

</html>
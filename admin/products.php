<?php
session_start();

// Block page for non-logged-in users
if (!isset($_SESSION['admin_username'])) {
    header("Location: ../admin/login.php");
    exit;
}

// Prevent browser caching after logout
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php
require_once '../config/database.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();

// Handle different actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle form submissions
if ($_POST) {
    if (isset($_POST['add_product'])) {
        addProduct($db);
    } elseif (isset($_POST['update_product'])) {
        updateProduct($db, $id);
    } elseif (isset($_POST['delete_product'])) {
        deleteProduct($db, $id);
    }
}

// Functions
function addProduct($db)
{
    try {
        $image_url = '';

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_url = uploadImage($_FILES['image']);
        }

        // Insert product
        $stmt = $db->prepare("INSERT INTO products (name, category, description, use_guide, image_url, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['category'],
            $_POST['description'],
            $_POST['use_guide'],
            $image_url,
            isset($_POST['is_active']) ? 1 : 0
        ]);

        // Get inserted product ID
        $product_id = $db->lastInsertId();

        // Insert ingredients
        if (!empty($_POST['ingredient_crop'])) {
            $cropList = $_POST['ingredient_crop'];
            $aiList = $_POST['ingredient_ai'];
            $dosageList = $_POST['ingredient_dosage'];

            $ingredientStmt = $db->prepare("INSERT INTO product_ingredients (product_id, crop, active_ingredient, dosage) VALUES (?, ?, ?, ?)");

            for ($i = 0; $i < count($cropList); $i++) {
                if (!empty($cropList[$i]) && !empty($aiList[$i]) && !empty($dosageList[$i])) {
                    $ingredientStmt->execute([$product_id, $cropList[$i], $aiList[$i], $dosageList[$i]]);
                }
            }
        }

        $_SESSION['success'] = "Product added successfully!";
        header("Location: products.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error adding product: " . $e->getMessage();
    }
}

function updateProduct($db, $id)
{
    try {
        $image_url = $_POST['existing_image'];

        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_url = uploadImage($_FILES['image']);

            // Delete old image if exists
            if (!empty($_POST['existing_image']) && file_exists('../uploads/' . $_POST['existing_image'])) {
                unlink('../uploads/' . $_POST['existing_image']);
            }
        }

        // Update product
        $stmt = $db->prepare("UPDATE products SET name=?, category=?, description=?, use_guide=?, image_url=?, is_active=? WHERE id=?");
        $stmt->execute([
            $_POST['name'],
            $_POST['category'],
            $_POST['description'],
            $_POST['use_guide'],
            $image_url,
            isset($_POST['is_active']) ? 1 : 0,
            $id
        ]);

        // Delete old ingredients
        $db->prepare("DELETE FROM product_ingredients WHERE product_id = ?")->execute([$id]);

        // Insert updated ingredients
        if (!empty($_POST['ingredient_crop'])) {
            $cropList = $_POST['ingredient_crop'];
            $aiList = $_POST['ingredient_ai'];
            $dosageList = $_POST['ingredient_dosage'];

            $ingredientStmt = $db->prepare("INSERT INTO product_ingredients (product_id, crop, active_ingredient, dosage) VALUES (?, ?, ?, ?)");

            for ($i = 0; $i < count($cropList); $i++) {
                if (!empty($cropList[$i]) && !empty($aiList[$i]) && !empty($dosageList[$i])) {
                    $ingredientStmt->execute([$id, $cropList[$i], $aiList[$i], $dosageList[$i]]);
                }
            }
        }

        $_SESSION['success'] = "Product updated successfully!";
        header("Location: products.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating product: " . $e->getMessage();
    }
}



function deleteProduct($db, $id)
{
    try {
        // Get image filename before deleting
        $stmt = $db->prepare("SELECT image_url FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        // Delete product
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        // Delete image file if exists
        if ($product && !empty($product['image_url']) && file_exists('../uploads/' . $product['image_url'])) {
            unlink('../uploads/' . $product['image_url']);
        }

        $_SESSION['success'] = "Product deleted successfully!";
        header("Location: products.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error deleting product: " . $e->getMessage();
    }
}

function uploadImage($file)
{
    $upload_dir = '../uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception("Invalid file type: " . $file['type']);
    }

    $max_size = 5 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        throw new Exception("File too large: " . $file['size']);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
    $target_path = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return $filename;
    } else {
        throw new Exception("Failed to upload image. Temp: " . $file['tmp_name'] . " Target: " . $target_path);
    }
}


// Get products for listing
if ($action == 'list') {
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;

    $search = $_GET['search'] ?? '';
    $category_filter = $_GET['category'] ?? '';

    $where_conditions = [];
    $params = [];

    if (!empty($search)) {
        $where_conditions[] = "(name LIKE ? OR active_ingredient LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if (!empty($category_filter)) {
        $where_conditions[] = "category = ?";
        $params[] = $category_filter;
    }

    $where_clause = empty($where_conditions) ? "" : "WHERE " . implode(" AND ", $where_conditions);

    // Get total count
    $count_stmt = $db->prepare("SELECT COUNT(*) as total FROM products $where_clause");
    $count_stmt->execute($params);
    $total_products = $count_stmt->fetch()['total'];
    $total_pages = ceil($total_products / $per_page);

    // Get products
    $stmt = $db->prepare("SELECT * FROM products $where_clause ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
    $stmt->execute($params);
    $products = $stmt->fetchAll();
}

// Get single product + ingredients for edit
if ($action == 'edit' && $id > 0) {
    // Fetch product details
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        $_SESSION['error'] = "Product not found!";
        header("Location: products.php");
        exit();
    }

    // ✅ Fetch ingredients for this product
    $ingredient_stmt = $db->prepare("SELECT * FROM product_ingredients WHERE product_id = ?");
    $ingredient_stmt->execute([$id]);
    $ingredients = $ingredient_stmt->fetchAll(PDO::FETCH_ASSOC);  // You'll use this in the form
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }

        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .table-actions {
            white-space: nowrap;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?php
                        switch ($action) {
                            case 'add':
                                echo 'Add New Product';
                                break;
                            case 'edit':
                                echo 'Edit Product';
                                break;
                            default:
                                echo 'Products Management';
                                break;
                        }
                        ?>
                    </h1>
                    <?php if ($action == 'list'): ?>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <a href="products.php?action=add" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New Product
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($action == 'list'): ?>
                    <!-- Products List -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All Products</h6>
                        </div>
                        <div class="card-body">
                            <!-- Search and Filter -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form method="GET" class="d-flex">
                                        <input type="text" name="search" class="form-control me-2"
                                            placeholder="Search products..."
                                            value="<?php echo htmlspecialchars($search); ?>">
                                        <button type="submit" class="btn btn-outline-secondary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form method="GET">
                                        <select name="category" class="form-select" onchange="this.form.submit()">
                                            <option value="">All Categories</option>
                                            <option value="insecticide" <?php echo $category_filter == 'insecticide' ? 'selected' : ''; ?>>Insecticide</option>
                                            <option value="herbicide" <?php echo $category_filter == 'herbicide' ? 'selected' : ''; ?>>Herbicide</option>
                                            <option value="fungicide" <?php echo $category_filter == 'fungicide' ? 'selected' : ''; ?>>Fungicide</option>
                                        </select>
                                        <?php if (!empty($search)): ?>
                                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>

                            <!-- Products Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Ingredient</th>
                                            <!-- <th>Price</th> -->
                                            <!-- <th>Featured</th> -->
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($products)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">No products found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($product['image_url'])): ?>
                                                            <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>"
                                                                alt="Product Image" class="product-image rounded">
                                                        <?php else: ?>
                                                            <div
                                                                class="product-image bg-light d-flex align-items-center justify-content-center rounded">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php
                                                        echo $product['category'] == 'insecticide' ? 'success' :
                                                            ($product['category'] == 'herbicide' ? 'info' : 'warning');
                                                        ?>">
                                                            <?php echo ucfirst($product['category']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($product['active_ingredient']); ?></td>
                                                    <td>
                                                        <?php if ($product['is_active']): ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="table-actions">
                                                        <a href="products.php?action=edit&id=<?php echo $product['id']; ?>"
                                                            class="btn btn-sm btn-outline-primary me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($total_pages > 1): ?>
                                <nav aria-label="Products pagination">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                <a class="page-link"
                                                    href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php elseif ($action == 'add' || $action == 'edit'): ?>
                    <!-- Add/Edit Product Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <?php echo $action == 'add' ? 'Add New Product' : 'Edit Product'; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="d-flex flex-wrap">
                                    <!-- Left: Image Preview -->
                                    <div class="me-4 mb-3" style="flex: 1; min-width: 250px;">
                                        <label for="image" class="form-label">Product Image</label>
                                        <input type="file" class="form-control mb-2" id="image" name="image"
                                            accept="image/*">

                                        <?php if ($action == 'edit' && !empty($product['image_url'])): ?>
                                            <input type="hidden" name="existing_image"
                                                value="<?php echo $product['image_url']; ?>">
                                            <div class="border p-2 rounded">
                                                <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>"
                                                    alt="Product Image" style="max-width: 100%; height: auto;">
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Right: Form Fields -->
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <!-- Product Name -->
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Product Name *</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="<?php echo $action == 'edit' ? htmlspecialchars($product['name']) : ''; ?>"
                                                    required>
                                            </div>

                                            <!-- Category -->
                                            <div class="col-md-6 mb-3">
                                                <label for="category" class="form-label">Category *</label>
                                                <select class="form-select" id="category" name="category" required>
                                                    <option value="">Select Category</option>
                                                    <option value="insecticide" <?php echo ($action == 'edit' && $product['category'] == 'insecticide') ? 'selected' : ''; ?>>
                                                        Insecticide</option>
                                                    <option value="herbicide" <?php echo ($action == 'edit' && $product['category'] == 'herbicide') ? 'selected' : ''; ?>>Herbicide
                                                    </option>
                                                    <option value="fungicide" <?php echo ($action == 'edit' && $product['category'] == 'fungicide') ? 'selected' : ''; ?>>Fungicide
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description"
                                                rows="3"><?php echo $action == 'edit' ? htmlspecialchars($product['description']) : ''; ?></textarea>
                                        </div>

                                        <!-- Product use guide -->
                                        <div class="mb-3">
                                            <label for="use_guide" class="form-label">Product Use Guide</label>
                                            <textarea class="form-control" id="use_guide" name="use_guide"
                                                rows="3"><?php echo $action == 'edit' ? htmlspecialchars($product['use_guide']) : ''; ?></textarea>
                                        </div>

                                        <!-- Placeholder for Ingredients Section (Step 2) -->
                                        <div id="ingredient-section">
                                            <!-- Ingredients Section -->
                                            <hr>
                                            <h5>Ingredients</h5>
                                            <div id="ingredient-wrapper">
                                                <!-- JS will add ingredient rows here -->
                                            </div>
                                            <button type="button" class="btn btn-outline-primary mb-3"
                                                onclick="addIngredientRow()">+ Add Ingredient</button>
                                        </div>

                                        <!-- Product Status -->
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                                <?php echo ($action == 'edit' && $product['is_active']) ? 'checked' : 'checked'; ?>>
                                            <label class="form-check-label" for="is_active">Active Product</label>
                                        </div>

                                        <!-- Submit Buttons -->
                                        <div class="d-flex justify-content-between">
                                            <a href="products.php" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left me-2"></i>Back to List
                                            </a>
                                            <button type="submit"
                                                name="<?php echo $action == 'add' ? 'add_product' : 'update_product'; ?>"
                                                class="btn btn-primary">
                                                <i
                                                    class="fas fa-save me-2"></i><?php echo $action == 'add' ? 'Add Product' : 'Update Product'; ?>
                                            </button>
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" id="delete_id" name="id" value="">
                        <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProduct(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>

    <script>
        function addIngredientRow(data = {}) {
            const wrapper = document.getElementById("ingredient-wrapper");

            const row = document.createElement("div");
            row.className = "row mb-2 ingredient-row";

            row.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="ingredient_crop[]" class="form-control" placeholder="Crop" value="${data.crop || ''}" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="ingredient_ai[]" class="form-control" placeholder="Active Ingredient" value="${data.ai || ''}" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="ingredient_dosage[]" class="form-control" placeholder="Dosage" value="${data.dosage || ''}" required>
        </div>
        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-danger" onclick="this.closest('.ingredient-row').remove()">Remove</button>
        </div>
    `;
            wrapper.appendChild(row);
        }

        // Auto-fill existing ingredients when editing
        <?php if ($action == 'edit' && !empty($ingredients)): ?>
            const ingredientsData = <?php echo json_encode($ingredients); ?>;
            ingredientsData.forEach(item => {
                addIngredientRow({
                    crop: item.crop,
                    ai: item.ai,
                    dosage: item.dosage
                });
            });
        <?php else: ?>
            addIngredientRow();
        <?php endif; ?>
    </script>

</body>

</html>
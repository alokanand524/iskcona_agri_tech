<?php
session_start();
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
function addProduct($db) {
    try {
        $image_url = '';
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_url = uploadImage($_FILES['image']);
        }
        
        $stmt = $db->prepare("INSERT INTO products (name, category, description, image_url, active_ingredient, dosage, target_pests, application_method, price, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $_POST['name'],
            $_POST['category'],
            $_POST['description'],
            $image_url,
            $_POST['active_ingredient'],
            $_POST['dosage'],
            $_POST['target_pests'],
            $_POST['application_method'],
            $_POST['price'],
            isset($_POST['is_featured']) ? 1 : 0,
            isset($_POST['is_active']) ? 1 : 0
        ]);
        
        $_SESSION['success'] = "Product added successfully!";
        header("Location: products.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error adding product: " . $e->getMessage();
    }
}

function updateProduct($db, $id) {
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
        
        $stmt = $db->prepare("UPDATE products SET name=?, category=?, description=?, image_url=?, active_ingredient=?, dosage=?, target_pests=?, application_method=?, price=?, is_featured=?, is_active=? WHERE id=?");
        
        $stmt->execute([
            $_POST['name'],
            $_POST['category'],
            $_POST['description'],
            $image_url,
            $_POST['active_ingredient'],
            $_POST['dosage'],
            $_POST['target_pests'],
            $_POST['application_method'],
            $_POST['price'],
            isset($_POST['is_featured']) ? 1 : 0,
            isset($_POST['is_active']) ? 1 : 0,
            $id
        ]);
        
        $_SESSION['success'] = "Product updated successfully!";
        header("Location: products.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating product: " . $e->getMessage();
    }
}

function deleteProduct($db, $id) {
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

function uploadImage($file) {
    $upload_dir = '../uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception("Invalid file type. Only JPG, PNG and GIF are allowed.");
    }
    
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $max_size) {
        throw new Exception("File too large. Maximum size is 5MB.");
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
    
    if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
        return $filename;
    } else {
        throw new Exception("Failed to upload image.");
    }
}

// Get products for listing
if ($action == 'list') {
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;
    
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $category_filter = isset($_GET['category']) ? $_GET['category'] : '';
    
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

// Get single product for edit
if ($action == 'edit' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        $_SESSION['error'] = "Product not found!";
        header("Location: products.php");
        exit();
    }
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
        .border-left-primary { border-left: 4px solid #4e73df !important; }
        .border-left-success { border-left: 4px solid #1cc88a !important; }
        .border-left-info { border-left: 4px solid #36b9cc !important; }
        .border-left-warning { border-left: 4px solid #f6c23e !important; }
        .product-image { width: 60px; height: 60px; object-fit: cover; }
        .table-actions { white-space: nowrap; }
        .card { box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important; }
    </style>
</head>
<body>
       <?php include 'includes/header.php'; ?>

    <div class="">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?php 
                        switch($action) {
                            case 'add': echo 'Add New Product'; break;
                            case 'edit': echo 'Edit Product'; break;
                            default: echo 'Products Management'; break;
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
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
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
                                        <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
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
                                                            <div class="product-image bg-light d-flex align-items-center justify-content-center rounded">
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
                                                    <!-- <td>₹<?php echo number_format($product['price'], 2); ?></td> -->
                                                    <!-- <td>
                                                        <?php if ($product['is_featured']): ?>
                                                            <span class="badge bg-warning">Featured</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">No</span>
                                                        <?php endif; ?>
                                                    </td> -->
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
                                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>">
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?php echo $action == 'edit' ? htmlspecialchars($product['name']) : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category *</label>
                                            <select class="form-select" id="category" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="insecticide" <?php echo ($action == 'edit' && $product['category'] == 'insecticide') ? 'selected' : ''; ?>>Insecticide</option>
                                                <option value="herbicide" <?php echo ($action == 'edit' && $product['category'] == 'herbicide') ? 'selected' : ''; ?>>Herbicide</option>
                                                <option value="fungicide" <?php echo ($action == 'edit' && $product['category'] == 'fungicide') ? 'selected' : ''; ?>>Fungicide</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"><?php echo $action == 'edit' ? htmlspecialchars($product['description']) : ''; ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="active_ingredient" class="form-label">Active Ingredient</label>
                                            <input type="text" class="form-control" id="active_ingredient" name="active_ingredient" 
                                                   value="<?php echo $action == 'edit' ? htmlspecialchars($product['active_ingredient']) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="dosage" class="form-label">Dosage</label>
                                            <input type="text" class="form-control" id="dosage" name="dosage" 
                                                   value="<?php echo $action == 'edit' ? htmlspecialchars($product['dosage']) : ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="mb-3">
                                    <label for="target_pests" class="form-label">Target Pests</label>
                                    <textarea class="form-control" id="target_pests" name="target_pests" rows="3"><?php echo $action == 'edit' ? htmlspecialchars($product['target_pests']) : ''; ?></textarea>
                                </div> -->

                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="application_method" class="form-label">Application Method</label>
                                            <textarea class="form-control" id="application_method" name="application_method" rows="2"><?php echo $action == 'edit' ? htmlspecialchars($product['application_method']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price (₹)</label>
                                            <input type="number" class="form-control" id="price" name="price" step="0.01" 
                                                   value="<?php echo $action == 'edit' ? $product['price'] : ''; ?>">
                                        </div>
                                    </div>
                                </div> -->

                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <?php if ($action == 'edit' && !empty($product['image_url'])): ?>
                                        <input type="hidden" name="existing_image" value="<?php echo $product['image_url']; ?>">
                                        <div class="mt-2">
                                            <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" 
                                                 alt="Current Product Image" style="max-width: 200px; height: auto;">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <!-- <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                   <?php echo ($action == 'edit' && $product['is_featured']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_featured">
                                                Featured Product
                                            </label>
                                        </div>
                                    </div> -->
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   <?php echo ($action == 'edit' && $product['is_active']) ? 'checked' : 'checked'; ?>>
                                            <label class="form-check-label" for="is_active">
                                                Active Product
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="products.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to List
                                    </a>
                                    <button type="submit" name="<?php echo $action == 'add' ? 'add_product' : 'update_product'; ?>" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i><?php echo $action == 'add' ? 'Add Product' : 'Update Product'; ?>
                                    </button>
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
</body>
</html>
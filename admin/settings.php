<?php
require_once '../config/database.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$successMessage = "";
$errorMessage = "";

// ✅ Create database connection
$db = new Database();
$conn = $db->connect();

// ✅ Define setting fetch function
function getSetting($conn, $section_key)
{
    $stmt = $conn->prepare("SELECT content FROM site_settings WHERE section_key = :key");
    $stmt->execute([':key' => $section_key]);
    return $stmt->fetchColumn() ?: ''; // fallback to empty string if not found
}

// ✅ Fetch current settings
$location = getSetting($conn, 'contact_location');
$phone = getSetting($conn, 'contact_phone');
$email = getSetting($conn, 'contact_email');
?>

<style>
    .setting {
        margin-left: 14rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    @media screen and (max-width: 768px) {
        .setting {
            margin-left: 0;
        }
        
    }
</style>

<div class="container mt-5 setting">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Contact Settings</h4>
        </div>
        <div class="card-body">

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Contact settings updated successfully!</div>
            <?php endif; ?>

            <form action="update_setting.php" method="POST">
                <div class="mb-3">
                    <label for="contact_location" class="form-label">Location</label>
                    <textarea name="contact_location" id="contact_location" rows="3"
                        class="form-control"><?= htmlspecialchars($location) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="contact_phone" class="form-label">Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="<?= htmlspecialchars($phone) ?>"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label for="contact_email" class="form-label">Email</label>
                    <input type="email" name="contact_email" id="contact_email" value="<?= htmlspecialchars($email) ?>"
                        class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Update Settings</button>
            </form>
        </div>
    </div>
</div>
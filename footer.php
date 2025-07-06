<?php
if (!isset($conn)) {
    require_once 'config/database.php';
    $db = new Database();
    $conn = $db->connect();
}

if (!function_exists('getSetting')) {
    function getSetting($conn, $section_key)
    {
        $stmt = $conn->prepare("SELECT content FROM site_settings WHERE section_key = :key");
        $stmt->execute([':key' => $section_key]);
        return $stmt->fetchColumn() ?: '';
    }
}

$footer_location = getSetting($conn, 'contact_location');
$footer_phone = getSetting($conn, 'contact_phone');
$footer_email = getSetting($conn, 'contact_email');
?>

<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="text-success mb-3">ISKCONA AGRI TECH PVT. LTD.</h5>
                <p class="text-light">Connecting you with nature's healing wisdom through traditional and modern
                    approaches to plant-based medicine.</p>
                <div class="social-links">
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-2">
                <h6 class="text-success mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="insecticides.php" class="text-light text-decoration-none">Insecticide</a></li>
                    <li><a href="fungicides.php" class="text-light text-decoration-none">Fungicides</a></li>
                    <li><a href="herbicides.php" class="text-light text-decoration-none">Herbicides</a></li>
                    <li><a href="about.php" class="text-light text-decoration-none">About</a></li>
                    <li><a href="contact.php" class="text-light text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="text-success mb-3">Services</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light text-decoration-none">Consultations</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Custom Formulations</a></li>
                    <!-- <li><a href="#" class="text-light text-decoration-none">Education</a></li> -->
                    <li><a href="#" class="text-light text-decoration-none">Workshops</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="text-success mb-3">Contact Info</h6>
                <p class="text-light mb-1"><i
                        class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($footer_location) ?></p>
                <p class="text-light mb-1"><i class="fas fa-phone me-2"></i><?= htmlspecialchars($footer_phone) ?></p>
                <p class="text-light"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($footer_email) ?></p>
            </div>
        </div>
        <hr class="my-4 border-secondary">
        <div class="row">
            <div class="col-md-6">
                <p class="text-light mb-0">&copy; <?= date('Y') ?> ISKCONA AGRI TECH PVT. LTD. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-light text-decoration-none">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
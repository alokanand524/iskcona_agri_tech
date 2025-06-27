<?php
require_once 'config/database.php';

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($message)) {
        $db = new Database();
        $conn = $db->connect();

        $sql = "INSERT INTO contact_messages (name, email, message, is_read)
                VALUES (:name, :email, :message, :is_read)";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute([
                ':name'    => $name,
                ':email'   => $email,
                ':message' => $message,
                ':is_read' => 0 // Default to unread
            ]);
            $successMessage = "✅ Message sent successfully!";
        } catch (PDOException $e) {
            $errorMessage = "❌ Failed to send message: " . $e->getMessage();
        }
    } else {
        $errorMessage = "❗ Please fill in all fields.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - ISKCONA AGRI TECH</title>

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

    <!-- Contact Section -->
    <section id="contact" class="py-5 contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="section-title">Get In Touch</h2>
                    <p class="text-muted">Ready to start your natural healing journey? Contact us today.</p>
                </div>
            </div>

            <div class="row g-4">

                <!-- Contact information -->
                <div class="col-lg-6">
                    <div class="contact-info slide-up">
                        <div class="contact-item mb-4">
                            <i class="fas fa-map-marker-alt text-success me-3"></i>
                            <div>
                                <h5>Location</h5>
                                <p class="text-muted">Panchkalguri, Patharghata, Nemai, Matigara, Darjeeling. West
                                    Bengal -734009</p>
                            </div>
                        </div>
                        <div class="contact-item mb-4">
                            <i class="fas fa-phone text-success me-3"></i>
                            <div>
                                <h5>Phone</h5>
                                <p class="text-muted">+19 1234567890</p>
                            </div>
                        </div>
                        <div class="contact-item mb-4">
                            <i class="fas fa-envelope text-success me-3"></i>
                            <div>
                                <h5>Email</h5>
                                <p class="text-muted">iskconaagritech@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="col-lg-6">

                    <?php if (!empty($successMessage)) : ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($errorMessage)) : ?>
                        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>

                  <form class="contact-form slide-up" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="message" rows="5" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Send Message</button>
                </form>

                </div>

            </div>

        </div>
    </section>

    <!-- find us on map -->
    <section class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Find Us on Map</h2>
            </div>
            <div class="col-12 ">
                <div class="ratio ratio-16x9" style="height: 300px;"> 
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.848054261425!2d90.40474631543207!3d23.750903294702543!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0000000000000000!2sISKCONA%20AGRI%20TECH%20PVT.%20LTD.!5e0!3m2!1sen!2sin!4v1623349200000!5m2!1sen!2sin"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
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
<?php
include 'header.php';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    if ($email) {
        // Insert into database
        try {
            $db = new PDO('sqlite:' . __DIR__ . '/database/portfolio.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $db->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, $subject, $message]);
            $success = true;
        } catch (Exception $e) {
            error_log('DB Error: ' . $e->getMessage());
        }
    }
}
?>

<!-- Contact Section -->
<section class="section contact">
    <div class="container">
        <div class="section-title">
            <h2>Hubungi Saya</h2>
            <p>Mari terhubung dan berkolaborasi</p>
        </div>

        <div class="contact-grid-new">
            <!-- Contact Form -->
            <div class="contact-form-new">
                <h3 class="contact-form-title">Kirim Pesan</h3>
                <form name="contact" method="POST" data-netlify="true">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subjek</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Pesan</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info-new">
                <h3 class="contact-info-title">Informasi Kontak</h3>
                <ul class="contact-list">
                    <li>
                        <i class="bx bx-map"></i>
                        <div>
                            <h4>Lokasi</h4>
                            <p>Kota, Indonesia</p>
                        </div>
                    </li>
                    <li>
                        <i class="bx bx-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>email@example.com</p>
                        </div>
                    </li>
                    <li>
                        <i class="bx bx-phone"></i>
                        <div>
                            <h4>Telepon</h4>
                            <p>+62 xxx-xxxx-xxxx</p>
                        </div>
                    </li>
                </ul>

                <!-- Values Section -->
                <div class="values-section">
                    <h3>Tarif Jasa & Value</h3>
                    <ul class="values-list">
                        <li>
                            <strong>Website Development</strong>
                            Rp 2.500.000 - 15.000.000 (tergantung kompleksitas)
                        </li>
                        <li>
                            <strong>Mobile App Development</strong>
                            Rp 5.000.000 - 25.000.000 (Android/iOS)
                        </li>
                        <li>
                            <strong>UI/UX Design</strong>
                            Rp 1.500.000 - 8.000.000 (per project)
                        </li>
                        <li>
                            <strong>Konsultasi IT</strong>
                            Rp 500.000/jam (minimum 2 jam)
                        </li>
                        <li>
                            <strong>Maintenance & Support</strong>
                            Rp 300.000 - 1.000.000/bulan
                        </li>
                    </ul>
                </div>

                <!-- Social Media Links -->
                <div class="social-links">
                    <h3>Media Sosial</h3>
                    <div class="contact-social">
                        <a href="https://github.com/yourusername" target="_blank" title="GitHub">
                            <i class="bx bxl-github"></i>
                        </a>
                        <a href="https://linkedin.com/in/yourusername" target="_blank" title="LinkedIn">
                            <i class="bx bxl-linkedin"></i>
                        </a>
                        <a href="https://facebook.com/yourusername" target="_blank" title="Facebook">
                            <i class="bx bxl-facebook"></i>
                        </a>
                        <a href="https://instagram.com/yourusername" target="_blank" title="Instagram">
                            <i class="bx bxl-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[name="contact"]');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Create notification
        const notification = document.createElement('div');
        notification.className = 'notification success';
        notification.textContent = 'Pesan telah terkirim! Terima kasih telah menghubungi.';

        // Add notification to page
        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);

        // Reset form
        form.reset();
    });
});
</script>
<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Fetch the page content
$sql = "SELECT * FROM static_content WHERE page_slug = 'about-us'";
$result = mysqli_query($conn, $sql);
$page = mysqli_fetch_assoc($result);

if (!$page) {
    // Or show a 404 page
    $page = [
        'title' => 'About Us',
        'content_body' => '<p>Content not found.</p>',
    ];
}

$page_title = $page['title'];

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="hero-content">
        <h1><?php echo htmlspecialchars($page['title']); ?></h1>
        <p>Bringing the world's finest tropical flavors to your business</p>
    </div>
</section>

<nav class="breadcrumb-nav">
    <div class="breadcrumb-content">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">About & Contact</li>
        </ol>
    </div>
</nav>

<!-- Page Tabs -->
<div class="page-tabs">
    <div class="tabs-container">
        <button class="tab-btn active" data-tab="about">About Us</button>
        <button class="tab-btn" data-tab="contact">Contact</button>
    </div>
</div>

<!-- ABOUT TAB CONTENT -->
<div id="about" class="tab-content active">
    <?php echo $page['content_body']; ?>
</div>

<!-- CONTACT TAB CONTENT -->
<div id="contact" class="tab-content">
    <section class="contact-section">
        <div class="section-container">
            <div class="section-header">
                <h2>Get In Touch</h2>
                <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    <p>Have questions about our products, pricing, or partnerships? Our team is here to help.</p>
                    <div class="info-item">
                        <div class="info-icon">📍</div>
                        <div class="info-content">
                            <h4>Address</h4>
                            <p>123 Tropical Avenue<br>São Paulo, SP 01310-100<br>Brazil</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📧</div>
                        <div class="info-content">
                            <h4>Email</h4>
                            <p><a href="mailto:info@tropicalfruitpulps.com">info@tropicalfruitpulps.com</a></p>
                            <p><a href="mailto:sales@tropicalfruitpulps.com">sales@tropicalfruitpulps.com</a></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📞</div>
                        <div class="info-content">
                            <h4>Phone</h4>
                            <p>+55 11 1234-5678</p>
                            <p>Mon-Fri: 9:00 AM - 6:00 PM (BRT)</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">⏰</div>
                        <div class="info-content">
                            <h4>Business Hours</h4>
                            <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 2:00 PM<br>Sunday: Closed</p>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <div class="success-message" id="successMessage">
                        ✓ Thank you! Your message has been sent successfully. We'll get back to you soon.
                    </div>
                    <form id="contactForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" name="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="company">Company Name</label>
                            <input type="text" id="company" name="company">
                        </div>
                        <div class="form-group">
                            <label for="inquiryType">Reason for Inquiry *</label>
                            <select id="inquiryType" name="inquiryType" required>
                                <option value="">Please select</option>
                                <option value="pricing">Pricing & Bulk Orders</option>
                                <option value="partnership">Partnership</option>
                                <option value="support">Support</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="map-section">
        <div class="map-container">
            <div class="section-header">
                <h2>Our Location</h2>
                <p>Come visit us at our headquarters in São Paulo</p>
            </div>
            <div class="map-placeholder">
                [ Interactive Map Placeholder ]
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

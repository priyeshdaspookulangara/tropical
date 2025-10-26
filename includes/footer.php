    <footer>
        <div class="footer-content">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>Tropical Fruit Pulps</h3>
                    <p>Your trusted partner for premium, sustainably sourced tropical fruit pulps for businesses worldwide.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php">Our Products</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="about.php#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <p>info@tropicalfruitpulps.com</p>
                    <p>+55 11 1234-5678</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Tropical Fruit Pulps. All Rights Reserved.</p>
                <div class="social-links">
                    <a href="#">FB</a>
                    <a href="#">IN</a>
                    <a href="#">TW</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            if (tabs.length > 0) {
                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        // Deactivate all tabs and content
                        tabs.forEach(t => t.classList.remove('active'));
                        tabContents.forEach(c => c.classList.remove('active'));

                        // Activate the clicked tab and its content
                        tab.classList.add('active');
                        document.getElementById(tab.dataset.tab).classList.add('active');
                    });
                });
            }

            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // In a real application, you'd handle form submission here (e.g., via AJAX)
                    const successMessage = document.getElementById('successMessage');
                    successMessage.classList.add('show');
                    contactForm.reset();
                    setTimeout(() => {
                        successMessage.classList.remove('show');
                    }, 4000);
                });
            }
        });
    </script>
</body>
</html>

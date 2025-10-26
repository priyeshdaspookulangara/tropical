
INSERT INTO `static_content` (`page_slug`, `title`, `content_body`, `last_updated_by`)
VALUES (
    'about-us',
    'About Tropical Fruit Pulps',
    '
    <!-- Our Story Section -->
    <section class=\"about-section\">
        <div class=\"section-container\">
            <div class=\"story-grid\">
                <div class=\"story-image\">🌴</div>
                <div class=\"story-content\">
                    <h3>Our Story</h3>
                    <p>Founded in 2010, Tropical Fruit Pulps began with a simple vision: to bring the authentic taste of tropical fruits to businesses worldwide. What started as a small family operation has grown into a leading supplier of premium fruit pulps.</p>
                    <p>We work directly with farmers across tropical regions, ensuring sustainable practices and fair trade. Our commitment to quality means every batch is carefully processed to preserve the natural flavors, nutrients, and vibrant colors that make tropical fruits so special.</p>
                    <p>Today, we proudly serve over 500 businesses in 30 countries, from juice manufacturers to gourmet restaurants, all sharing our passion for authentic tropical flavors.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class=\"about-section\">
        <div class=\"section-container\">
            <div class=\"section-header\">
                <h2>Our Core Values</h2>
                <p>The principles that guide everything we do</p>
            </div>
            <div class=\"values-grid\">
                <div class=\"value-card\">
                    <div class=\"value-icon\">🌱</div>
                    <h3>Sustainability</h3>
                    <p>We prioritize environmentally responsible practices, from farm to delivery. Our partnerships promote sustainable agriculture and minimize environmental impact.</p>
                </div>
                <div class=\"value-card\">
                    <div class=\"value-icon\">✨</div>
                    <h3>Quality First</h3>
                    <p>Every batch undergoes rigorous quality control. We never compromise on taste, nutrition, or safety standards.</p>
                </div>
                <div class=\"value-card\">
                    <div class=\"value-icon\">🤝</div>
                    <h3>Fair Trade</h3>
                    <p>We believe in fair compensation for farmers and workers. Our direct partnerships ensure equitable relationships and community support.</p>
                </div>
                <div class=\"value-card\">
                    <div class=\"value-icon\">🔬</div>
                    <h3>Innovation</h3>
                    <p>We continuously invest in technology and processes to deliver the freshest, most flavorful products while maintaining nutritional integrity.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class=\"about-section\">
        <div class=\"section-container\">
            <div class=\"section-header\">
                <h2>Our Impact</h2>
                <p>Numbers that tell our story</p>
            </div>
            <div class=\"stats-grid\">
                <div class=\"stat-card\">
                    <div class=\"stat-number\">500+</div>
                    <div class=\"stat-label\">Happy Clients</div>
                </div>
                <div class=\"stat-card\">
                    <div class=\"stat-number\">30</div>
                    <div class=\"stat-label\">Countries Served</div>
                </div>
                <div class=\"stat-card\">
                    <div class=\"stat-number\">50+</div>
                    <div class=\"stat-label\">Fruit Varieties</div>
                </div>
                <div class=\"stat-card\">
                    <div class=\"stat-number\">15+</div>
                    <div class=\"stat-label\">Years Experience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class=\"about-section\">
        <div class=\"section-container\">
            <div class=\"section-header\">
                <h2>Meet Our Team</h2>
                <p>The passionate people behind our success</p>
            </div>
            <div class=\"team-grid\">
                <div class=\"team-card\">
                    <div class=\"team-image\">👨‍💼</div>
                    <div class=\"team-info\">
                        <h3>Carlos Rodriguez</h3>
                        <div class=\"role\">Founder & CEO</div>
                        <p>With 20 years in the tropical fruit industry, Carlos leads our vision of sustainable quality.</p>
                    </div>
                </div>
                <div class=\"team-card\">
                    <div class=\"team-image\">👩‍🔬</div>
                    <div class=\"team-info\">
                        <h3>Dr. Maria Santos</h3>
                        <div class=\"role\">Quality Director</div>
                        <p>Food scientist ensuring every product meets our rigorous standards and certifications.</p>
                    </div>
                </div>
                <div class=\"team-card\">
                    <div class=\"team-image\">👨‍🌾</div>
                    <div class=\"team-info\">
                        <h3>João Silva</h3>
                        <div class=\"role\">Sourcing Manager</div>
                        <p>Building relationships with farmers across tropical regions for 15+ years.</p>
                    </div>
                </div>
                <div class=\"team-card\">
                    <div class=\"team-image\">👩‍💼</div>
                    <div class=\"team-info\">
                        <h3>Ana Martinez</h3>
                        <div class=\"role\">Operations Director</div>
                        <p>Ensuring smooth logistics and timely delivery to clients worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    ',
    'system'
)
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`),
    `content_body` = VALUES(`content_body`),
    `last_updated_by` = VALUES(`last_updated_by`);

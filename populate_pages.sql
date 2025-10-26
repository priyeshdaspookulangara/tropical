-- SQL script to populate the Services and Values pages with default content.
-- You can modify the 'title' and 'content_body' values before running this script.

INSERT INTO `static_content` (`page_slug`, `title`, `content_body`, `last_updated_by`)
VALUES (
    'services',
    'Our Services',
    '<section class=\"about-section\"><div class=\"section-container\"><div class=\"section-header\"><h2>Our Services</h2><p>Provide a brief description of the services you offer here.</p></div><div class=\"values-grid\"><div class=\"value-card\"><div class=\"value-icon\">📦</div><h3>Bulk Ordering</h3><p>Detailed information about your bulk ordering process, minimum quantities, and pricing tiers.</p></div><div class=\"value-card\"><div class=\"value-icon\">🚚</div><h3>Custom Sourcing</h3><p>Information on sourcing specific or rare tropical fruits for your business needs.</p></div><div class=\"value-card\"><div class=\"value-icon\">🧪</div><h3>Private Labeling</h3><p>Details about private labeling services for your own brand of fruit pulps.</p></div></div></div></section>',
    'system'
)
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`),
    `content_body` = VALUES(`content_body`),
    `last_updated_by` = VALUES(`last_updated_by`);

INSERT INTO `static_content` (`page_slug`, `title`, `content_body`, `last_updated_by`)
VALUES (
    'values',
    'Our Core Values',
    '<section class=\"about-section\"><div class=\"section-container\"><div class=\"section-header\"><h2>Our Core Values</h2><p>The principles that guide everything we do</p></div><div class=\"values-grid\"><div class=\"value-card\"><div class=\"value-icon\">🌱</div><h3>Sustainability</h3><p>We prioritize environmentally responsible practices, from farm to delivery. Our partnerships promote sustainable agriculture and minimize environmental impact.</p></div><div class=\"value-card\"><div class=\"value-icon\">✨</div><h3>Quality First</h3><p>Every batch undergoes rigorous quality control. We never compromise on taste, nutrition, or safety standards.</p></div><div class=\"value-card\"><div class=\"value-icon\">🤝</div><h3>Fair Trade</h3><p>We believe in fair compensation for farmers and workers. Our direct partnerships ensure equitable relationships and community support.</p></div><div class=\"value-card\"><div class=\"value-icon\">🔬</div><h3>Innovation</h3><p>We continuously invest in technology and processes to deliver the freshest, most flavorful products while maintaining nutritional integrity.</p></div></div></div></section>',
    'system'
)
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`),
    `content_body` = VALUES(`content_body`),
    `last_updated_by` = VALUES(`last_updated_by`);

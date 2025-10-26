<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Tropical Fruit Pulps'; ?> - Tropical Fruit Pulps</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; }
        /* Header Styles */
        header { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; }
        .header-nav { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; }
        .logo { text-decoration: none; }
        .nav-menu { display: flex; list-style: none; gap: 2rem; align-items: center; }
        .nav-link { color: #333; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .nav-link:hover, .nav-link.active { color: #3e940f; }
        .cta-buttons { display: flex; gap: 1rem; }
        .btn { padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; display: inline-block; }
        .btn-outline { border: 2px solid #3e940f; color: #3e940f; background: transparent; }
        .btn-outline:hover { background: #3e940f; color: #fff; }
        .btn-primary { background: linear-gradient(to bottom, #fbd91c, #eb7c12); color: #fff; border: none; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(235, 124, 18, 0.3); }
        /* Hero Section */
        .page-hero { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 6rem 2rem 4rem; text-align: center; position: relative; overflow: hidden; }
        .page-hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 20% 50%, rgba(62, 148, 15, 0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(251, 217, 28, 0.1) 0%, transparent 50%); z-index: 0; }
        .hero-content { max-width: 800px; margin: 0 auto; position: relative; z-index: 1; }
        .page-hero h1 { font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700; }
        .page-hero p { font-size: 1.25rem; opacity: 0.9; }
        /* Breadcrumb */
        .breadcrumb-nav { background: #f9fafb; padding: 1rem 0; border-bottom: 1px solid #e5e7eb; }
        .breadcrumb-content { max-width: 1400px; margin: 0 auto; padding: 0 2rem; }
        .breadcrumb { background: none; margin: 0; padding: 0; list-style: none; display: flex; gap: 0.5rem; }
        .breadcrumb-item a { color: #6b7280; text-decoration: none; }
        .breadcrumb-item a:hover { color: #3e940f; }
        .breadcrumb-item.active { color: #111827; font-weight: 600; }
        .breadcrumb-item + .breadcrumb-item::before { content: '/'; padding-right: 0.5rem; color: #9ca3af; }
        /* Page Navigation Tabs */
        .page-tabs { background: #fff; border-bottom: 2px solid #e5e7eb; position: sticky; top: 70px; z-index: 100; }
        .tabs-container { max-width: 1400px; margin: 0 auto; padding: 0 2rem; display: flex; gap: 2rem; }
        .tab-btn { padding: 1rem 1.5rem; background: none; border: none; border-bottom: 3px solid transparent; color: #6b7280; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .tab-btn:hover { color: #3e940f; }
        .tab-btn.active { color: #3e940f; border-bottom-color: #3e940f; }
        /* Tab Content */
        .tab-content { display: none; animation: fadeIn 0.5s ease-in; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        /* About Page Sections */
        .about-section { padding: 5rem 2rem; background: #fff; }
        .about-section:nth-child(even) { background: #f9fafb; }
        .section-container { max-width: 1200px; margin: 0 auto; }
        .section-header { text-align: center; margin-bottom: 3rem; }
        .section-header h2 { font-size: 2.5rem; color: #111827; margin-bottom: 1rem; }
        .section-header p { font-size: 1.1rem; color: #6b7280; max-width: 700px; margin: 0 auto; }
        /* Story Grid */
        .story-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
        .story-image { width: 100%; height: 400px; background: linear-gradient(135deg, #3e940f 0%, #2d7a0c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 3rem; }
        .story-content h3 { font-size: 2rem; color: #111827; margin-bottom: 1rem; }
        .story-content p { color: #6b7280; line-height: 1.8; margin-bottom: 1rem; }
        /* Values Grid */
        .values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 3rem; }
        .value-card { background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s; }
        .value-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
        .value-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #3e940f 0%, #2d7a0c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
        .value-card h3 { font-size: 1.5rem; color: #111827; margin-bottom: 0.75rem; }
        .value-card p { color: #6b7280; line-height: 1.7; }
        /* Stats Section */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem; margin-top: 3rem; }
        .stat-card { text-align: center; }
        .stat-number { font-size: 3.5rem; font-weight: 700; color: #3e940f; margin-bottom: 0.5rem; }
        .stat-label { font-size: 1.1rem; color: #6b7280; font-weight: 500; }
        /* Team Grid */
        .team-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem; }
        .team-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s; }
        .team-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
        .team-image { width: 100%; height: 280px; background: linear-gradient(135deg, #6b7280 0%, #374151 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 4rem; }
        .team-info { padding: 1.5rem; }
        .team-info h3 { font-size: 1.3rem; color: #111827; margin-bottom: 0.25rem; }
        .team-info .role { color: #3e940f; font-weight: 600; margin-bottom: 0.75rem; }
        .team-info p { color: #6b7280; font-size: 0.95rem; line-height: 1.6; }
        /* Contact Section */
        .contact-section { padding: 5rem 2rem; background: #fff; }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-top: 3rem; }
        .contact-info h3 { font-size: 1.8rem; color: #111827; margin-bottom: 1.5rem; }
        .contact-info p { color: #6b7280; line-height: 1.8; margin-bottom: 2rem; }
        .info-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
        .info-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #3e940f 0%, #2d7a0c 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; flex-shrink: 0; }
        .info-content h4 { font-size: 1.1rem; color: #111827; margin-bottom: 0.25rem; }
        .info-content p { color: #6b7280; margin: 0; }
        .info-content a { color: #3e940f; text-decoration: none; }
        .info-content a:hover { text-decoration: underline; }
        /* Contact Form */
        .contact-form { background: #f9fafb; padding: 2.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; color: #374151; font-weight: 600; margin-bottom: 0.5rem; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.875rem; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 1rem; transition: border-color 0.3s; font-family: inherit; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #3e940f; }
        .form-group textarea { resize: vertical; min-height: 120px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .btn-submit { width: 100%; padding: 1rem; background: linear-gradient(to bottom, #fbd91c, #eb7c12); color: #fff; border: none; border-radius: 6px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(235, 124, 18, 0.3); }
        /* Map Section */
        .map-section { padding: 5rem 2rem; background: #f9fafb; }
        .map-container { max-width: 1200px; margin: 0 auto; }
        .map-placeholder { width: 100%; height: 400px; background: linear-gradient(135deg, #6b7280 0%, #374151 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 2rem; margin-top: 2rem; }
        /* Footer */
        footer { background: #111827; color: #fff; padding: 4rem 2rem 2rem; }
        .footer-content { max-width: 1200px; margin: 0 auto; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem; margin-bottom: 3rem; }
        .footer-section h3 { font-size: 1.5rem; margin-bottom: 1.5rem; font-weight: bold; }
        .footer-section h4 { font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; color: #9ca3af; }
        .footer-section p { color: #9ca3af; line-height: 1.7; }
        .footer-section ul { list-style: none; padding: 0; }
        .footer-section ul li { margin-bottom: 0.5rem; }
        .footer-section ul li a { color: #d1d5db; text-decoration: none; transition: color 0.3s; }
        .footer-section ul li a:hover { color: #3e940f; }
        .footer-bottom { border-top: 1px solid #374151; padding-top: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .footer-bottom p { color: #9ca3af; font-size: 0.9rem; }
        .social-links { display: flex; gap: 1.5rem; }
        .social-links a { color: #9ca3af; text-decoration: none; font-size: 1.25rem; transition: color 0.3s; }
        .social-links a:hover { color: #3e940f; }
        /* Success Message */
        .success-message { display: none; background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; border: 1px solid #10b981; }
        .success-message.show { display: block; animation: slideDown 0.3s ease-out; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        /* Responsive Design */
        @media (max-width: 1024px) { .story-grid, .contact-grid { grid-template-columns: 1fr; } .story-image { height: 300px; } }
        @media (max-width: 768px) { .nav-menu { display: none; } .page-hero h1 { font-size: 2.5rem; } .section-header h2 { font-size: 2rem; } .form-row { grid-template-columns: 1fr; } .tabs-container { overflow-x: auto; } .footer-bottom { flex-direction: column; text-align: center; } }
    </style>
</head>
<body>
    <header>
        <nav class="header-nav">
            <a href="/" class="logo">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <circle cx="25" cy="25" r="23" fill="#3e940f"/>
                    <text x="25" y="32" font-size="20" fill="white" text-anchor="middle" font-weight="bold">TFP</text>
                </svg>
            </a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About Us</a></li>
                <li class="nav-item"><a href="values.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'values.php' ? 'active' : ''; ?>">Values</a></li>
                <li class="nav-item"><a href="index.php" class="nav-link">Products</a></li>
                <li class="nav-item"><a href="services.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">Services</a></li>
                <li class="nav-item"><a href="about.php#contact" class="nav-link">Contact Us</a></li>
            </ul>
            <div class="cta-buttons">
                <a href="#" class="btn btn-outline">Log In</a>
                <a href="#" class="btn btn-primary">Order Now</a>
            </div>
        </nav>
    </header>

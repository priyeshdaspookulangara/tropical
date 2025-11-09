CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fruits_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `selling_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruit_selling_types` (
  `fruit_id` int(11) NOT NULL,
  `selling_type_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`,`selling_type_id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `selling_type_id` (`selling_type_id`),
  CONSTRAINT `fruit_selling_types_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_selling_types_ibfk_2` FOREIGN KEY (`selling_type_id`) REFERENCES `selling_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruit_id` int(11) NOT NULL,
  `selling_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'New',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `selling_type_id` (`selling_type_id`),
  CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inquiries_ibfk_2` FOREIGN KEY (`selling_type_id`) REFERENCES `selling_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `static_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content_body` text,
  `last_updated_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_slug` (`page_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `static_content` (`page_slug`, `title`, `content_body`, `last_updated_by`) VALUES
('about', 'About Us', '<p>This is the default content for the About Us page. Please update it from the admin panel.</p>', 'system'),
('values', 'Our Values', '<p>This is the default content for the Our Values page. Please update it from the admin panel.</p>', 'system'),
('contact', 'Contact Us', '<p>This is the default content for the Contact Us page. Please update it from the admin panel.</p>', 'system'),
('services', 'Our Services', '<p>This is the default content for the Our Services page. Please update it from the admin panel.</p>', 'system');

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_logo', ''),
('site_name', 'Fruit B2B'),
('favicon', ''),
('banner_image', ''),
('banner_title', 'Welcome to our Fruit B2B'),
('banner_button1_text', 'Shop Now'),
('banner_button1_link', '#'),
('banner_button1_style', 'primary'),
('banner_button2_text', 'Contact Us'),
('banner_button2_link', '#'),
('banner_button2_style', 'secondary');

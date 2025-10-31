CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `storages` (
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
  `origin` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `storage_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `storage_id` (`storage_id`),
  CONSTRAINT `fruits_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruits_ibfk_2` FOREIGN KEY (`storage_id`) REFERENCES `storages` (`id`) ON DELETE SET NULL
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

CREATE TABLE `flavours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruit_flavours` (
  `fruit_id` int(11) NOT NULL,
  `flavour_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`,`flavour_id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `flavour_id` (`flavour_id`),
  CONSTRAINT `fruit_flavours_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_flavours_ibfk_2` FOREIGN KEY (`flavour_id`) REFERENCES `flavours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruit_colors` (
  `fruit_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`,`color_id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `color_id` (`color_id`),
  CONSTRAINT `fruit_colors_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_colors_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruit_suppliers` (
  `fruit_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`,`supplier_id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `fruit_suppliers_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_suppliers_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `product_lines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `product_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `packing_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruit_product_line_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruit_id` int(11) NOT NULL,
  `product_line_id` int(11) NOT NULL,
  `product_form_id` int(11) DEFAULT NULL,
  `packing_medium_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `product_line_id` (`product_line_id`),
  KEY `product_form_id` (`product_form_id`),
  KEY `packing_medium_id` (`packing_medium_id`),
  CONSTRAINT `fruit_product_line_configs_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_product_line_configs_ibfk_2` FOREIGN KEY (`product_line_id`) REFERENCES `product_lines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_product_line_configs_ibfk_3` FOREIGN KEY (`product_form_id`) REFERENCES `product_forms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fruit_product_line_configs_ibfk_4` FOREIGN KEY (`packing_medium_id`) REFERENCES `packing_media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `packagings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruit_packagings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruit_id` int(11) NOT NULL,
  `packaging_id` int(11) NOT NULL,
  `storage_id` int(11) NOT NULL,
  `shelf_life_months` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `packaging_id` (`packaging_id`),
  KEY `storage_id` (`storage_id`),
  CONSTRAINT `fruit_packagings_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_packagings_ibfk_2` FOREIGN KEY (`packaging_id`) REFERENCES `packagings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_packagings_ibfk_3` FOREIGN KEY (`storage_id`) REFERENCES `storages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `packaging_quantities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruit_packaging_id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fruit_packaging_id` (`fruit_packaging_id`),
  CONSTRAINT `packaging_quantities_ibfk_1` FOREIGN KEY (`fruit_packaging_id`) REFERENCES `fruit_packagings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruit_application` (
  `fruit_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`,`application_id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fruit_application_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_application_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `trends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fruit_trend` (
  `fruit_id` int(11) NOT NULL,
  `trend_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`,`trend_id`),
  KEY `fruit_id` (`fruit_id`),
  KEY `trend_id` (`trend_id`),
  CONSTRAINT `fruit_trend_ibfk_1` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fruit_trend_ibfk_2` FOREIGN KEY (`trend_id`) REFERENCES `trends` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for new tables
INSERT INTO `categories` (`name`) VALUES ('Fruits'), ('Vegetables'), ('Herbs');
INSERT INTO `flavours` (`name`) VALUES ('Sweet'), ('Tart'), ('Citrus'), ('Berry'), ('Tropical');
INSERT INTO `colors` (`name`) VALUES ('Red'), ('Yellow'), ('Green'), ('Orange'), ('Purple');
INSERT INTO `suppliers` (`name`) VALUES ('Global Fruit Co.'), ('Tropical Imports Inc.'), ('Organic Farms Ltd.'), ('Berry Best'), ('Citrus Grove');
INSERT INTO `product_lines` (`name`) VALUES ('Juice Concentrates'), ('Frozen Purees'), ('Dried Fruits'), ('Canned Goods'), ('Fresh Cuts');
INSERT INTO `storages` (`name`) VALUES ('Ambient'), ('Refrigerated'), ('Frozen');
INSERT INTO `product_forms` (`name`) VALUES ('Whole'), ('Sliced'), ('Diced'), ('Pureed');
INSERT INTO `packing_media` (`name`) VALUES ('In Juice'), ('In Syrup'), ('In Water');
INSERT INTO `packagings` (`name`) VALUES ('Can'), ('Pouch'), ('Jar'), ('Box');
INSERT INTO `applications` (`name`) VALUES ('Bakery'), ('Beverages'), ('Dairy'), ('Snacks');
INSERT INTO `trends` (`name`) VALUES ('Organic'), ('Non-GMO'), ('Fair Trade'), ('Sustainable');

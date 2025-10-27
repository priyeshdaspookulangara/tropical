-- This script updates the database schema to support advanced fruit features.

--
-- 1. Master Data Tables
-- These tables store the predefined options for various fruit attributes.
--

-- Master table for storage conditions
CREATE TABLE `storages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Master table for product lines (e.g., Canned, Puree)
CREATE TABLE `product_lines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Master table for product forms (e.g., Slices, Chunks)
CREATE TABLE `product_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Master table for packing media (e.g., Syrup, Juice)
CREATE TABLE `packing_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Master table for packaging types (e.g., Can, Aseptic Bag)
CREATE TABLE `packagings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Master table for applications (e.g., Beverages, Bakery)
CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Master table for trends (e.g., Natural, Sustainable)
CREATE TABLE `trends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- 2. Alter Existing `fruits` Table
-- Add new columns for origin, region, and storage.
--
ALTER TABLE `fruits`
ADD COLUMN `origin` varchar(255) DEFAULT NULL,
ADD COLUMN `region` varchar(255) DEFAULT NULL,
ADD COLUMN `storage_id` int(11) DEFAULT NULL,
ADD CONSTRAINT `fk_fruit_storage` FOREIGN KEY (`storage_id`) REFERENCES `storages` (`id`) ON DELETE SET NULL;


--
-- 3. Linking Tables
-- These tables create the relationships between fruits and their new attributes.
--

-- Table to link a fruit to its product line configurations, including forms and media.
-- A single fruit can have multiple rows here to represent complex combinations.
-- Example: (fruit_id: 1, line_id: 1, form_id: 1, medium_id: 1) for Slices in Syrup
--          (fruit_id: 1, line_id: 2, form_id: NULL, medium_id: NULL) for Puree
CREATE TABLE `fruit_product_line_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruit_id` int(11) NOT NULL,
  `product_line_id` int(11) NOT NULL,
  `product_form_id` int(11) DEFAULT NULL,
  `packing_medium_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fplc_fruit` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fplc_line` FOREIGN KEY (`product_line_id`) REFERENCES `product_lines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fplc_form` FOREIGN KEY (`product_form_id`) REFERENCES `product_forms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_fplc_medium` FOREIGN KEY (`packing_medium_id`) REFERENCES `packing_media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table to link a fruit to a specific packaging type, including storage and shelf life.
CREATE TABLE `fruit_packagings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruit_id` int(11) NOT NULL,
  `packaging_id` int(11) NOT NULL,
  `storage_id` int(11) NOT NULL,
  `shelf_life_months` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fp_fruit` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fp_packaging` FOREIGN KEY (`packaging_id`) REFERENCES `packagings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fp_storage` FOREIGN KEY (`storage_id`) REFERENCES `storages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table to store quantities for a specific fruit packaging instance.
CREATE TABLE `packaging_quantities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruit_packaging_id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pq_fruit_packaging` FOREIGN KEY (`fruit_packaging_id`) REFERENCES `fruit_packagings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pivot table for the many-to-many relationship between fruits and applications.
CREATE TABLE `fruit_application` (
  `fruit_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`, `application_id`),
  CONSTRAINT `fk_fa_fruit` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fa_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pivot table for the many-to-many relationship between fruits and trends.
CREATE TABLE `fruit_trend` (
  `fruit_id` int(11) NOT NULL,
  `trend_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`, `trend_id`),
  CONSTRAINT `fk_ft_fruit` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ft_trend` FOREIGN KEY (`trend_id`) REFERENCES `trends` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- 4. Pre-populate Master Data
-- Insert the default options provided in the prompt.
--
INSERT INTO `storages` (`name`) VALUES ('Ambient'), ('Chilled'), ('Frozen');

INSERT INTO `product_lines` (`name`) VALUES
('Canned Products'), ('Puree'), ('Pulps'), ('Concentrate'),
('IQF'), ('Freeze Dried'), ('Fruit Powder');

INSERT INTO `product_forms` (`name`) VALUES ('Slices'), ('Chunks'), ('Crushed');

INSERT INTO `packing_media` (`name`) VALUES ('Syrup'), ('Juice');

INSERT INTO `packagings` (`name`) VALUES
('Can'), ('Aseptic Bag in Drum'), ('PE Bag in Carton');

INSERT INTO `applications` (`name`) VALUES
  ('Premium Juices & Beverages'),
  ('Jams & Fruit Preparations'),
  ('Ice Cream, Dairy & Alternatives'),
  ('Bakery & Desserts'),
  ('Soups & Savoury'),
  ('Baby Food'),
  ('Confectionery'),
  ('Pet Food');

INSERT INTO `trends` (`name`) VALUES
  ('Natural'),
  ('Sustainable'),
  ('Plant Based'),
  ('Personalised Nutrition'),
  ('Taste & Texture'),
  ('Sugar Reduction'),
  ('Fermented'),
  ('Colouring');

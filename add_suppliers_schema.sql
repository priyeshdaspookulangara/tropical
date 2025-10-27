-- This script updates the database schema to add supplier management.

--
-- 1. Create `suppliers` table
-- This table will store information about each supplier.
--
CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 2. Create `fruit_supplier` pivot table
-- This table creates the many-to-many relationship between fruits and suppliers.
--
CREATE TABLE `fruit_supplier` (
  `fruit_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  PRIMARY KEY (`fruit_id`, `supplier_id`),
  CONSTRAINT `fk_fs_fruit` FOREIGN KEY (`fruit_id`) REFERENCES `fruits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fs_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 3. Pre-populate `suppliers` table with sample data
--
INSERT INTO `suppliers` (`name`, `country`) VALUES
('Global Fruit Corp', 'Brazil'),
('Asia Produce Inc.', 'Vietnam'),
('Tropical Farms Ltd.', 'India'),
('Sunshine Exporters', 'Thailand');

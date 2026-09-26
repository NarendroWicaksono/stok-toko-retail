-- Database: stok_toko
CREATE DATABASE IF NOT EXISTS `stok_toko` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `stok_toko`;

-- Tabel produk (data unik dari CSV)
CREATE TABLE IF NOT EXISTS `produk` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` VARCHAR(50) NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `sub_category` VARCHAR(100) NOT NULL,
    `harga` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `stok` INT NOT NULL DEFAULT 0,
    `stok_minimum` INT NOT NULL DEFAULT 5,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

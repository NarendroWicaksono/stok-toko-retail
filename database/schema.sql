-- ============================================
-- TABEL KATEGORI
-- ============================================
CREATE TABLE IF NOT EXISTS `kategori` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `deskripsi` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_nama_kategori` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL SUPPLIER
-- ============================================
CREATE TABLE IF NOT EXISTS `supplier` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(255) NOT NULL,
    `kontak` VARCHAR(100) NULL,
    `telepon` VARCHAR(30) NULL,
    `email` VARCHAR(100) NULL,
    `alamat` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_nama_supplier` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL PRODUK (data unik dari CSV)
-- ============================================
CREATE TABLE IF NOT EXISTS `produk` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` VARCHAR(50) NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `sub_category` VARCHAR(100) NOT NULL,
    `harga` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `stok` INT NOT NULL DEFAULT 0,
    `stok_minimum` INT NOT NULL DEFAULT 5,
    `kategori_id` INT NULL,
    `supplier_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_product_id` (`product_id`),
    KEY `fk_produk_kategori` (`kategori_id`),
    KEY `fk_produk_supplier` (`supplier_id`),
    CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_produk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

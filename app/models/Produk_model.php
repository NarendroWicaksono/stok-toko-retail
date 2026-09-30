<?php

class Produk_model {
    private $table = 'produk';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllProduk() {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY category, sub_category, product_name");
        return $this->db->resultSet();
    }

    public function getProdukById($id) {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function searchProduk($keyword) {
        $this->db->query("SELECT * FROM {$this->table} WHERE product_name LIKE :keyword OR product_id LIKE :keyword OR category LIKE :keyword OR sub_category LIKE :keyword ORDER BY category, product_name");
        $this->db->bind(':keyword', "%{$keyword}%");
        return $this->db->resultSet();
    }

    public function getByCategory($category) {
        $this->db->query("SELECT * FROM {$this->table} WHERE category = :category ORDER BY sub_category, product_name");
        $this->db->bind(':category', $category);
        return $this->db->resultSet();
    }

    public function getCategories() {
        $this->db->query("SELECT DISTINCT category FROM {$this->table} ORDER BY category");
        return $this->db->resultSet();
    }

    public function getSubCategories($category = null) {
        if ($category) {
            $this->db->query("SELECT DISTINCT sub_category FROM {$this->table} WHERE category = :category ORDER BY sub_category");
            $this->db->bind(':category', $category);
        } else {
            $this->db->query("SELECT DISTINCT sub_category FROM {$this->table} ORDER BY sub_category");
        }
        return $this->db->resultSet();
    }

    public function getStokRendah() {
        $this->db->query("SELECT * FROM {$this->table} WHERE stok <= stok_minimum ORDER BY stok ASC");
        return $this->db->resultSet();
    }

    public function getTotalProduk() {
        $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $result = $this->db->single();
        return $result['total'];
    }

    public function getTotalStokRendah() {
        $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE stok <= stok_minimum");
        $result = $this->db->single();
        return $result['total'];
    }

    public function getTotalKategori() {
        $this->db->query("SELECT COUNT(DISTINCT category) as total FROM {$this->table}");
        $result = $this->db->single();
        return $result['total'];
    }

    public function getNilaiStok() {
        $this->db->query("SELECT SUM(harga * stok) as total FROM {$this->table}");
        $result = $this->db->single();
        return $result['total'];
    }

    public function updateStok($id, $stok) {
        $this->db->query("UPDATE {$this->table} SET stok = :stok WHERE id = :id");
        $this->db->bind(':stok', $stok);
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function tambahStok($id, $jumlah) {
        $this->db->query("UPDATE {$this->table} SET stok = GREATEST(0, stok + :jumlah) WHERE id = :id");
        $this->db->bind(':jumlah', (int)$jumlah);
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function kurangStok($id, $jumlah) {
        return $this->tambahStok($id, -abs((int)$jumlah));
    }

    public function getStokByCategory() {
        $this->db->query("SELECT category, SUM(stok) as total_stok, COUNT(*) as total_produk, SUM(CASE WHEN stok <= stok_minimum THEN 1 ELSE 0 END) as stok_rendah FROM {$this->table} GROUP BY category ORDER BY category");
        return $this->db->resultSet();
    }

    public function getProdukPaged($offset, $limit, $category = null, $search = null) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        if ($category) {
            $sql .= " AND category = :category";
        }
        if ($search) {
            $sql .= " AND (product_name LIKE :search OR product_id LIKE :search)";
        }
        $sql .= " ORDER BY category, sub_category, product_name LIMIT :offset, :limit";
        
        $this->db->query($sql);
        if ($category) $this->db->bind(':category', $category);
        if ($search) $this->db->bind(':search', "%{$search}%");
        $this->db->bind(':offset', (int)$offset);
        $this->db->bind(':limit', (int)$limit);
        return $this->db->resultSet();
    }

    public function countProduk($category = null, $search = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        if ($category) {
            $sql .= " AND category = :category";
        }
        if ($search) {
            $sql .= " AND (product_name LIKE :search OR product_id LIKE :search)";
        }
        $this->db->query($sql);
        if ($category) $this->db->bind(':category', $category);
        if ($search) $this->db->bind(':search', "%{$search}%");
        $result = $this->db->single();
        return $result['total'];
    }

    public function tambahProduk($data) {
        $this->db->query("INSERT INTO {$this->table} (product_id, product_name, category, sub_category, harga, stok, stok_minimum, kategori_id, supplier_id) VALUES (:product_id, :product_name, :category, :sub_category, :harga, :stok, :stok_minimum, :kategori_id, :supplier_id)");
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':product_name', $data['product_name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':sub_category', $data['sub_category']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':stok', $data['stok']);
        $this->db->bind(':stok_minimum', $data['stok_minimum']);
        $this->db->bind(':kategori_id', $data['kategori_id']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateProduk($data) {
        $this->db->query("UPDATE {$this->table} SET product_name = :product_name, category = :category, sub_category = :sub_category, harga = :harga, stok_minimum = :stok_minimum, kategori_id = :kategori_id, supplier_id = :supplier_id WHERE id = :id");
        $this->db->bind(':product_name', $data['product_name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':sub_category', $data['sub_category']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':stok_minimum', $data['stok_minimum']);
        $this->db->bind(':kategori_id', $data['kategori_id']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusProduk($id) {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}


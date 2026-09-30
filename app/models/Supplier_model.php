<?php

class Supplier_model {
    private $table = 'supplier';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllSupplier() {
        $this->db->query("SELECT s.*, (SELECT COUNT(*) FROM produk p WHERE p.supplier_id = s.id) as jumlah_produk FROM {$this->table} s ORDER BY s.nama");
        return $this->db->resultSet();
    }

    public function getSupplierById($id) {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function tambahSupplier($data) {
        $this->db->query("INSERT INTO {$this->table} (nama, kontak, telepon, email, alamat) VALUES (:nama, :kontak, :telepon, :email, :alamat)");
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':kontak', $data['kontak']);
        $this->db->bind(':telepon', $data['telepon']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':alamat', $data['alamat']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateSupplier($data) {
        $this->db->query("UPDATE {$this->table} SET nama = :nama, kontak = :kontak, telepon = :telepon, email = :email, alamat = :alamat WHERE id = :id");
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':kontak', $data['kontak']);
        $this->db->bind(':telepon', $data['telepon']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':alamat', $data['alamat']);
        $this->db->bind(':id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusSupplier($id) {
        // Set produk.supplier_id to NULL before deleting
        $this->db->query("UPDATE produk SET supplier_id = NULL WHERE supplier_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getTotalSupplier() {
        $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $result = $this->db->single();
        return $result['total'];
    }

    public function searchSupplier($keyword) {
        $this->db->query("SELECT s.*, (SELECT COUNT(*) FROM produk p WHERE p.supplier_id = s.id) as jumlah_produk FROM {$this->table} s WHERE s.nama LIKE :keyword OR s.kontak LIKE :keyword OR s.telepon LIKE :keyword OR s.email LIKE :keyword ORDER BY s.nama");
        $this->db->bind(':keyword', "%{$keyword}%");
        return $this->db->resultSet();
    }

    public function getSupplierForDropdown() {
        $this->db->query("SELECT id, nama FROM {$this->table} ORDER BY nama");
        return $this->db->resultSet();
    }
}

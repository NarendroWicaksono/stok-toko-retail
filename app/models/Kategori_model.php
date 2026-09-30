<?php

class Kategori_model {
    private $table = 'kategori';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllKategori() {
        $this->db->query("SELECT k.*, (SELECT COUNT(*) FROM produk p WHERE p.kategori_id = k.id) as jumlah_produk FROM {$this->table} k ORDER BY k.nama");
        return $this->db->resultSet();
    }

    public function getKategoriById($id) {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function tambahKategori($data) {
        $this->db->query("INSERT INTO {$this->table} (nama, deskripsi) VALUES (:nama, :deskripsi)");
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':deskripsi', $data['deskripsi']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateKategori($data) {
        $this->db->query("UPDATE {$this->table} SET nama = :nama, deskripsi = :deskripsi WHERE id = :id");
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':deskripsi', $data['deskripsi']);
        $this->db->bind(':id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKategori($id) {
        // Set produk.kategori_id to NULL before deleting (FK will handle, but explicit is clearer)
        $this->db->query("UPDATE produk SET kategori_id = NULL WHERE kategori_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getTotalKategori() {
        $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $result = $this->db->single();
        return $result['total'];
    }

    public function searchKategori($keyword) {
        $this->db->query("SELECT k.*, (SELECT COUNT(*) FROM produk p WHERE p.kategori_id = k.id) as jumlah_produk FROM {$this->table} k WHERE k.nama LIKE :keyword OR k.deskripsi LIKE :keyword ORDER BY k.nama");
        $this->db->bind(':keyword', "%{$keyword}%");
        return $this->db->resultSet();
    }

    public function getKategoriForDropdown() {
        $this->db->query("SELECT id, nama FROM {$this->table} ORDER BY nama");
        return $this->db->resultSet();
    }
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Alternatif_model extends CI_Model
{

    //menampilkan data alternatif berdasarkan id kelas
    public function tampil($id_kelas)
    {
        $query = $this->db->query("SELECT * FROM alternatif where id_kelas = '$id_kelas'");
        return $query->result();
    }

    //menghitung data alternatif
    public function getTotal()
    {
        return $this->db->count_all('alternatif');
    }

    //menyimpan data alternatif
    public function insert($data = [])
    {
        $result = $this->db->insert('alternatif', $data);
        return $result;
    }

    //mengambil data alternatif by id_alternatif
    public function show($id_alternatif)
    {
        $this->db->where('id_alternatif', $id_alternatif);
        $query = $this->db->get('alternatif');
        return $query->row();
    }

    //mengupdate data alternatif
    public function update($id_alternatif, $data = [])
    {
        $ubah = array(
            'nama'  => $data['nama']
        );

        $this->db->where('id_alternatif', $id_alternatif);
        $this->db->update('alternatif', $ubah);
    }

    //menghapus data alternatif
    public function delete($id_alternatif)
    {
        $this->db->where('id_alternatif', $id_alternatif);
        $this->db->delete('alternatif');
    }

    //mengambil data kelas
    public function dataKelas($id_kelas)
    {
        $this->db->where('id_kelas', $id_kelas);
        $query = $this->db->get('kelas');
        return $query->row();
    }
}

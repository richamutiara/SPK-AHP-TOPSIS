<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kelas_model extends CI_Model
{
    public function tampil()
    {
        $query = $this->db->query("SELECT * FROM kelas");
        return $query->result();
    }

    public function getTotal()
    {
        return $this->db->count_all('kelas');
    }

    public function insert($data = [])
    {
        $result = $this->db->insert('kelas', $data);
        return $result;
    }

    public function show($id_kelas)
    {
        $this->db->where('id_kelas', $id_kelas);
        $query = $this->db->get('kelas');
        return $query->row();
    }

    public function update($id_kelas, $data = [])
    {
        $ubah = array(
            'nama'  => $data['nama']
        );

        $this->db->where('id_kelas', $id_kelas);
        $this->db->update('kelas', $ubah);
    }


    public function delete($id_kelas)
    {
        $this->db->where('id_kelas', $id_kelas);
        $this->db->delete('kelas');
    }
}

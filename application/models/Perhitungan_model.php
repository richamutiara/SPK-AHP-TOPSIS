<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perhitungan_model extends CI_Model
{

    public function get_kriteria()
    {
        $query = $this->db->get('kriteria');
        return $query->result();
    }
    public function get_alternatif()
    {
        $query = $this->db->get('alternatif');
        return $query->result();
    }

    public function data_nilai($id_alternatif, $id_kriteria)
    {
        $query = $this->db->query("SELECT * FROM penilaian JOIN sub_kriteria WHERE penilaian.nilai=sub_kriteria.id_sub_kriteria AND penilaian.id_alternatif='$id_alternatif' AND penilaian.id_kriteria='$id_kriteria';");
        return $query->row_array();
    }

    public function get_hasil($id_kelas)
    {
        $query = $this->db->query("SELECT * FROM hasil inner join alternatif on hasil.id_alternatif = alternatif.id_alternatif WHERE alternatif.id_kelas = '$id_kelas' order by hasil.nilai DESC ;");
        return $query->result();
    }

    public function get_hasil_alternatif($id_alternatif)
    {
        $query = $this->db->query("SELECT * FROM alternatif WHERE id_alternatif='$id_alternatif';");
        return $query->row_array();
    }

    public function dataKelas($id_kelas)
    {
        $this->db->where('id_kelas', $id_kelas);
        $query = $this->db->get('kelas');
        return $query->row();
    }


    public function insert_hasil($hasil_akhir = [])
    {
        $result = $this->db->insert('hasil', $hasil_akhir);
        return $result;
    }

    public function hapus_hasil()
    {
        $query = $this->db->query("TRUNCATE TABLE hasil;");
        return $query;
    }
}

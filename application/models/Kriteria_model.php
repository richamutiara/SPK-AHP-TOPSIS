<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Kriteria_model extends CI_Model {

        public function tampil()
        {
            $query = $this->db->get('kriteria');
            return $query->result();
        }

        public function get_all_kriteria($sort = 'asc')
		{
			$this->db->order_by('id_kriteria', $sort);
			return $this->db->get('kriteria');
		}

		public function get_kriteria($id_kriteria)
		{
			$this->db->where('id_kriteria', $id_kriteria);
			return $this->db->get('kriteria');
		}
		
		public function update_kriteria($id_kriteria, $params)
		{
			$this->db->where('id_kriteria', $id_kriteria);
			return $this->db->update('kriteria', $params);
		}
		
		public function update_prioritas($params)
    {
        return $this->db->update('kriteria', $params);
    }

        public function insert($data = [])
        {
            $result = $this->db->insert('kriteria', $data);
            return $result;
        }

        public function show($id_kriteria)
        {
            $this->db->where('id_kriteria', $id_kriteria);
            $query = $this->db->get('kriteria');
            return $query->row();
        }

        public function update($id_kriteria, $data = [])
        {
            $ubah = array(
                'keterangan' => $data['keterangan'],
                'kode_kriteria' => $data['kode_kriteria'],
                'jenis'  => $data['jenis']
            );

            $this->db->where('id_kriteria', $id_kriteria);
            $this->db->update('kriteria', $ubah);
        }

        public function delete($id_kriteria)
        {
            $this->db->where('id_kriteria', $id_kriteria);
            $this->db->delete('kriteria');
        }
    }
    
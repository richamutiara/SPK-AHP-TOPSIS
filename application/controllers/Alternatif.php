<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Alternatif extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Alternatif_model');

        if ($this->session->userdata('id_user_level') != "1") {
?>
            <script type="text/javascript">
                alert('Anda tidak berhak mengakses halaman ini!');
                window.location = '<?php echo base_url("Login/home"); ?>'
            </script>
<?php
        }
    }

    public function index()
    {
        $id_kelas = $this->session->userdata('id_kelas');
        $data = [
            'page' => "Alternatif",
            'list' => $this->Alternatif_model->tampil($id_kelas),
            'kelas' => $this->Alternatif_model->dataKelas($id_kelas)

        ];
        $this->load->view('alternatif/index', $data);
    }

    //menampilkan view create
    public function create()
    {
        $data['page'] = "Alternatif";
        $this->load->view('alternatif/create', $data);
    }

    //menambahkan data ke database
    public function store()
    {
        $data = [
            'nama' => $this->input->post('nama'),
            // 'id_kelas' => $this->input->post('kelas')
        ];

        $data['id_kelas'] = $this->session->userdata('id_kelas');

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        // $this->form_validation->set_rules('kelas', 'Kelas', 'required');

        if ($this->form_validation->run() != false) {
            $result = $this->Alternatif_model->insert($data);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
                redirect('alternatif');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>');
            redirect('alternatif/create');
        }
    }

    public function edit($id_alternatif)
    {
        $alternatif = $this->Alternatif_model->show($id_alternatif);
        $data = [
            'page' => "Alternatif",
            'alternatif' => $alternatif
        ];
        $this->load->view('alternatif/edit', $data);
    }

    public function update($id_alternatif)
    {
        $id_alternatif = $this->input->post('id_alternatif');
        $data = array(
            'nama' => $this->input->post('nama')
        );

        $this->Alternatif_model->update($id_alternatif, $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
        redirect('alternatif');
    }

    public function destroy($id_alternatif)
    {
        $this->Alternatif_model->delete($id_alternatif);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('alternatif');
    }
}

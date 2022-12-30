<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kelas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Kelas_model');

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
        $data = [
            'page' => "Kelas",
            'list' => $this->Kelas_model->tampil(),

        ];
        $this->load->view('kelas/index', $data);
    }

    //menampilkan view create
    public function create()
    {
        $data['page'] = "Kelas";
        $this->load->view('kelas/create', $data);
    }

    //menambahkan data ke database
    public function store()
    {
        $data = [
            'nama' => $this->input->post('nama'),
        ];

        $this->form_validation->set_rules('nama', 'Nama', 'required|is_unique[kelas.nama]');

        if ($this->form_validation->run() != false) {
            $result = $this->Kelas_model->insert($data);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
                redirect('kelas');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>');
            redirect('kelas/create');
        }
    }

    public function edit($id_kelas)
    {
        $kelas = $this->Kelas_model->show($id_kelas);
        $data = [
            'page' => "Kelas",
            'kelas' => $kelas
        ];
        $this->load->view('kelas/edit', $data);
    }

    public function update($id_kelas)
    {
        $id_kelas = $this->input->post('id_kelas');
        $data = array(
            'nama' => $this->input->post('nama')
        );

        $this->Kelas_model->update($id_kelas, $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
        redirect('kelas');
    }

    public function destroy($id_kelas)
    {
        $this->Kelas_model->delete($id_kelas);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('kelas');
    }
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('User_model');

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
            'page' => "User",
            'list' => $this->User_model->tampil(),
            'user_level' => $this->User_model->user_level(),

        ];
        $this->load->view('user/index', $data);
    }

    public function create()
    {
        $id_kelas = $this->session->userdata('id_kelas');
        $data['page'] = "User";
        $data['list_alternatif'] = $this->User_model->list_alternatif($id_kelas);
        $data['list_kelas'] = $this->User_model->list_kelas();
        $this->load->view('User/create', $data);
    }

    public function storeGuru()
    {

        $data = [
            'id_user_level' => 1,
            'id_kelas' => $this->input->post('gKelas'),
            'nama' => $this->input->post('gNama'),
            'email' => $this->input->post('gEmail'),
            'username' => $this->input->post('gUsername'),
            'password' => md5($this->input->post('gPassword'))
        ];

        $this->form_validation->set_rules('gEmail', 'email', 'required');
        $this->form_validation->set_rules('gKelas', 'ID Kelas', 'required');
        $this->form_validation->set_rules('gUsername', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('gPassword', 'Password', 'required');

        if ($this->form_validation->run() != false) {
            $result = $this->User_model->insert($data);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
                redirect('User');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>');
            redirect('User/create');
        }
    }
    public function storeAlternatif()
    {
        $alternatif = $this->User_model->dataAlternatif($this->input->post('aNama'));

        $data = [
            'id_user_level' => 2,
            'id_kelas' => $alternatif->id_kelas, //belum
            'nama' => $alternatif->nama, //belum
            'email' => $this->input->post('aEmail'),
            'username' => $this->input->post('aUsername'),
            'password' => md5($this->input->post('aPassword'))
        ];

        $this->form_validation->set_rules('aEmail', 'email', 'required');
        $this->form_validation->set_rules('aNama', 'ID Nama', 'required');
        $this->form_validation->set_rules('aUsername', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('aPassword', 'Password', 'required');

        if ($this->form_validation->run() != false) {
            $result = $this->User_model->insert($data);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
                redirect('User');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>');
            redirect('User/create');
        }
    }

    public function show($id_user)
    {
        $User = $this->User_model->show($id_user);
        $user_level = $this->User_model->user_level();
        $data = [
            'page' => "User",
            'data' => $User,
            'user_level' => $user_level
        ];
        $this->load->view('User/show', $data);
    }

    public function edit($id_user)
    {
        $User = $this->User_model->show($id_user);
        $user_level = $this->User_model->user_level();
        $data = [
            'page' => "User",
            'User' => $User,
            'user_level' => $user_level
        ];
        $this->load->view('User/edit', $data);
    }

    public function update($id_user)
    {
        // TODO: implementasi update data berdasarkan $id_user
        $id_user = $this->input->post('id_user');
        $data = array(
            'page' => "User",
            'id_user_level' => $this->input->post('privilege'),
            'nama' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password'))
        );

        $this->User_model->update($id_user, $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
        redirect('User');
    }

    public function destroy($id_user)
    {
        $this->User_model->delete($id_user);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('User');
    }
}
    
    /* End of file Kategori.php */

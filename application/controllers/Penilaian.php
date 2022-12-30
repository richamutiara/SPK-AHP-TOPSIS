<?php

defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penilaian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Penilaian_model');

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
            'page' => "Penilaian",
            'list' => $this->Penilaian_model->tampil(),
            'kriteria' => $this->Penilaian_model->get_kriteria(),
            'alternatif' => $this->Penilaian_model->get_alternatif($id_kelas),
            'kelas' => $this->Penilaian_model->dataKelas($id_kelas)
        ];
        $this->load->view('penilaian/index', $data);
    }


    public function tambah_penilaian()
    {
        $id_alternatif = $this->input->post('id_alternatif');
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');
        $i = 0;
        echo var_dump($nilai);
        foreach ($nilai as $key) {
            $this->Penilaian_model->tambah_penilaian($id_alternatif, $id_kriteria[$i], $key);
            $i++;
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
        redirect('penilaian');
    }

    public function update_penilaian()
    {
        $id_alternatif = $this->input->post('id_alternatif');
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');
        $i = 0;

        foreach ($nilai as $key) {
            $cek = $this->Penilaian_model->data_penilaian($id_alternatif, $id_kriteria[$i]);
            if ($cek == 0) {
                $this->Penilaian_model->tambah_penilaian($id_alternatif, $id_kriteria[$i], $key);
            } else {
                $this->Penilaian_model->edit_penilaian($id_alternatif, $id_kriteria[$i], $key);
            }
            $i++;
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
        redirect('penilaian');
    }

    //import file excel
    public function import_excel()
    {
        $ext = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);
        if ($ext == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet()->toArray();
            $sheet_count = count($sheet);
            if ($sheet_count > 1) {
                for ($i = 1; $i < $sheet_count; $i++) {
                    //id kriteria
                    $id_alternatif = $sheet[$i][0];

                    //mencari nilai c1
                    if ($sheet[$i][2] >= 95 && $sheet[$i][2] <= 100) {
                        $c1 = 18;
                    } elseif ($sheet[$i][2] >= 90 && $sheet[$i][2] <= 94.99) {
                        $c1 = 19;
                    } elseif ($sheet[$i][2] >= 85 && $sheet[$i][2] <= 89.99) {
                        $c1 = 20;
                    } elseif ($sheet[$i][2] > 0 && $sheet[$i][2] < 85) {
                        $c1 = 21;
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian C1 tidak sesuai, Mohon dicek kembali!</div>');
                        redirect('penilaian');
                    };

                    $data_c1[] = array(
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => 38,
                        'nilai' => $c1

                    );

                    //mencari data c2
                    if ($sheet[$i][3] == 'sangat baik') {
                        $c2 = 22;
                    } elseif ($sheet[$i][3] == 'baik') {
                        $c2 = 23;
                    } elseif ($sheet[$i][3] == 'cukup') {
                        $c2 = 24;
                    } elseif ($sheet[$i][3] == 'buruk') {
                        $c2 = 25;
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian C2 tidak sesuai, Mohon dicek kembali!</div>');
                        redirect('penilaian');
                    };

                    $data_c2[] = array(
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => 39,
                        'nilai' => $c2
                    );

                    //mencari data c3
                    if ($sheet[$i][4] == 'selalu hadir') {
                        $c3 = 26;
                    } elseif ($sheet[$i][4] == 'hadir') {
                        $c3 = 27;
                    } elseif ($sheet[$i][4] == 'kadang hadir') {
                        $c3 = 28;
                    } elseif ($sheet[$i][4] == 'jarang hadir') {
                        $c3 = 29;
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian C3 tidak sesuai, Mohon dicek kembali!</div>');
                        redirect('penilaian');
                    };

                    $data_c3[] = array(
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => 40,
                        'nilai' => $c3
                    );


                    //mencari data c4
                    if ($sheet[$i][5] == 'sangat aktif') {
                        $c4 = 30;
                    } elseif ($sheet[$i][5] == 'aktif') {
                        $c4 = 31;
                    } elseif ($sheet[$i][5] == 'cukup aktif') {
                        $c4 = 32;
                    } elseif ($sheet[$i][5] == 'jarang aktif') {
                        $c4 = 33;
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian C4 tidak sesuai, Mohon dicek kembali!</div>');
                        redirect('penilaian');
                    };

                    $data_c4[] = array(
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => 41,
                        'nilai' => $c4
                    );
                }

                $data = array_merge($data_c1, $data_c2, $data_c3, $data_c4);
                $insertData = $this->Penilaian_model->import_excel($data);
                if ($insertData) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
                    redirect('penilaian');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian tidak sesuai, Mohon dicek kembali!</div>');
                redirect('penilaian');
            }

            // echo '<pre>';
            // print_r($data);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">File Excel Xlsx saja!</div>');
        }
    }

    //format file excel
    public function format_excel()
    {
        $id_kelas = $this->session->userdata('id_kelas');
        //fetch data
        $list_alternatif = $this->Penilaian_model->get_alternatif($id_kelas);

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $filename = "FORMAT_PENILAIAN.xlsx";

        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID Alternatif');
        $sheet->setCellValue('B1', 'Nama ALternatif');
        $sheet->setCellValue('C1', 'Nilai Raport (C1)');
        $sheet->setCellValue('D1', 'Nilai Etika (C2)');
        $sheet->setCellValue('E1', 'Nilai Kehadiran (C3)');
        $sheet->setCellValue('F1', 'Nilai Ekstrakulikuler (C4)');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true);

        $i = 2;
        foreach ($list_alternatif as $alternatif) {
            $sheet->setCellValue('A' . $i, $alternatif->id_alternatif);
            $sheet->setCellValue('B' . $i, $alternatif->nama);
            $sheet->setCellValue('C' . $i, '1-100');
            $sheet->setCellValue('D' . $i, 'sangat baik/baik/cukup/buruk');
            $sheet->setCellValue('E' . $i, 'selalu hadir/hadir/kadang hadir/jarang hadir');
            $sheet->setCellValue('F' . $i, 'sangat aktif/aktif/cukup aktif/jarang aktif');
            $i++;
        }
        $write = new Xlsx($spreadsheet);

        return $write->save('php://output');
    }
}

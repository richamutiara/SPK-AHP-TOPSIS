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

        //pengecekan level user yang sedang login
        if ($this->session->userdata('id_user_level') != "1") {
?>
            <script type="text/javascript">
                alert('Anda tidak berhak mengakses halaman ini!');
                window.location = '<?php echo base_url("Login/home"); ?>'
            </script>
<?php
        }
    }

    // fungsi index
    public function index()
    {
        //mengambil id_kelas dari user yang sedang login
        $id_kelas = $this->session->userdata('id_kelas');

        //mengambil data dari models
        $data = [
            'page' => "Penilaian",
            'list' => $this->Penilaian_model->tampil(),
            'kriteria' => $this->Penilaian_model->get_kriteria(),
            'alternatif' => $this->Penilaian_model->get_alternatif($id_kelas),
            'kelas' => $this->Penilaian_model->dataKelas($id_kelas),
            'data_excel' => $this->Penilaian_model->tombol_excel($id_kelas)
        ];

        //mengarahkan ke views index penilaian
        $this->load->view('penilaian/index', $data);
    }
    // Batas fungsi index


    // Fungsi Tambah Penilaian
    public function tambah_penilaian()
    {
        //proses pengambilan data dari inputan pada fungsi tambah nilai
        $id_alternatif = $this->input->post('id_alternatif');
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');

        //proses insert data nilai ke tabel penilaian
        $i = 0;
        foreach ($nilai as $key) {
            $this->Penilaian_model->tambah_penilaian($id_alternatif, $id_kriteria[$i], $key);
            $i++;
        }

        //menampilkan notif alert
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');

        //mengarahkan aplikasi kembali ke halaman penilaian
        redirect('penilaian');
    }
    //Batas Fungsi Tambah Penilaian

    // Fungsi update data penilaian
    public function update_penilaian()
    {
        //proses pengambilan data dari inputan pada fungsi edit penilaian
        $id_alternatif = $this->input->post('id_alternatif');
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');

        //proses update data nilai yang ada di database dengan nilai inputan baru
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

        //menampilkan notif alert
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');

        //mengarahkan aplikasi kembali ke halaman penilaian
        redirect('penilaian');
    }
    // Batas Fungsi update data penilaian

    //import file excel
    public function import_excel()
    {
        //proses untuk mendapatkan ekstensi dari file yang diupload
        $ext = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);

        //pengecekan ektensi file yang diupload
        if ($ext == 'xlsx') {
            //fungsi untuk memanggil library untuk membaca file ekstendi xlsx
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            //membaca file
            $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
            //merubah isi data file menjadi array
            $sheet = $spreadsheet->getActiveSheet()->toArray();
            //menghitung jumlah data pada array
            $sheet_count = count($sheet);

            //mengecek data jika lebih dari 1 
            if ($sheet_count > 1) {
                for ($i = 1; $i < $sheet_count; $i++) {

                    //id kriteria
                    $id_alternatif = $sheet[$i][0];

                    //validasi data nilai c1
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

                    //menampung nilai c1 pada array
                    $data_c1[] = array(
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => 38,  //code id_kriteria c1
                        'nilai' => $c1

                    );

                    //validasi data nilai c2
                    if ($sheet[$i][3] == 'sangat baik') {
                        $c2 = 22;
                    } elseif ($sheet[$i][3] == 'baik') {
                        $c2 = 23;
                    } elseif ($sheet[$i][3] == 'cukup baik') {
                        $c2 = 24;
                    } elseif ($sheet[$i][3] == 'kurang baik') {
                        $c2 = 25;
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian C2 tidak sesuai, Mohon dicek kembali!</div>');
                        redirect('penilaian');
                    };

                    //menampung nilai c2 pada array
                    $data_c2[] = array(
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => 39, //code id_kriteria c2
                        'nilai' => $c2
                    );

                    //validasi data nilai c3 
                    if ($sheet[$i][4] == 'selalu hadir') {
                        $c3 = 26;
                    } elseif ($sheet[$i][4] == 'cukup hadir') {
                        $c3 = 27;
                    } elseif ($sheet[$i][4] == 'jarang hadir') {
                        $c3 = 28;
                    } elseif ($sheet[$i][4] == 'izin') {
                        $c3 = 29;
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian C3 tidak sesuai, Mohon dicek kembali!</div>');
                        redirect('penilaian');
                    };

                    //menampung nilai c3 pada array
                    $data_c3[] = array(
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => 40,  //code id_kriteria c3
                        'nilai' => $c3
                    );

                    //validasi data nilai c4
                    if ($sheet[$i][5] == 'sangat aktif') {
                        $c4 = 30;
                    } elseif ($sheet[$i][5] == 'aktif') {
                        $c4 = 31;
                    } elseif ($sheet[$i][5] == 'cukup aktif') {
                        $c4 = 32;
                    } elseif ($sheet[$i][5] == 'kurang aktif') {
                        $c4 = 33;
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian C4 tidak sesuai, Mohon dicek kembali!</div>');
                        redirect('penilaian');
                    };

                    //menampung nilai c4 pada array
                    $data_c4[] = array(
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => 41, //code id_kriteria c3
                        'nilai' => $c4
                    );
                }

                //menggabungkan data array
                $data = array_merge($data_c1, $data_c2, $data_c3, $data_c4);

                //melakukan insert / menyimpan data ke tabel penilaian
                $insertData = $this->Penilaian_model->import_excel($data);

                //mengecek apakah berhasil insert data
                if ($insertData) {
                    //jika berhasil, muncul notif
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data Penilaian dari File Excel berhasil disimpan!</div>');
                    //mengarahkan aplikasi kembali ke halaman penilaian
                    redirect('penilaian');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Format Penilaian tidak sesuai, Mohon dicek kembali!</div>');
                redirect('penilaian');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mohon pilih File Excel  dengan ekstensi .xlsx!</div>');
        }
    }

    // format download file excel
    public function format_excel()
    {
        //mengambil id_kelas dari user yang sedang login
        $id_kelas = $this->session->userdata('id_kelas');

        //mengambil data alternatif berdasarkan id kelas user yang sedang login
        $list_alternatif = $this->Penilaian_model->format_excel($id_kelas);

        //untuk mengaktifkan fungsi download
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //nama file excel yang akan didownload
        $filename = "FORMAT_PENILAIAN.xlsx";

        //menamai file yang didownload berdasarkan $filename
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        //mengakses library Spreadsheet
        $spreadsheet = new Spreadsheet();

        //mengaktifkan file
        $sheet = $spreadsheet->getActiveSheet();

        //mengisi row header / row pertama pada file
        $sheet->setCellValue('A1', 'ID Alternatif');
        $sheet->setCellValue('B1', 'Nama Alternatif');
        $sheet->setCellValue('C1', 'Nilai Raport (C1)');
        $sheet->setCellValue('D1', 'Nilai Etika (C2)');
        $sheet->setCellValue('E1', 'Nilai Kehadiran (C3)');
        $sheet->setCellValue('F1', 'Nilai Ekstrakulikuler (C4)');

        //mengaktifkan autosize berdasarkan data pada setiap kolomnya
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        //fungsi untuk menebalkan huruf pada row pertama
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true);

        // mengisi row dengan data alternatif
        $i = 2;
        foreach ($list_alternatif as $alternatif) {
            $sheet->setCellValue('A' . $i, $alternatif->id_alternatif);
            $sheet->setCellValue('B' . $i, $alternatif->nama);
            $sheet->setCellValue('C' . $i, '1-100');
            $sheet->setCellValue('D' . $i, 'sangat baik/baik/cukup baik/kurang baik');
            $sheet->setCellValue('E' . $i, 'selalu hadir/cukup hadir/jarang hadir/izin');
            $sheet->setCellValue('F' . $i, 'sangat aktif/aktif/cukup aktif/kurang aktif');
            $i++;
        }

        //membuat file excel
        $write = new Xlsx($spreadsheet);

        //melakukan proses download file excel
        return $write->save('php://output');
    }
}

<?php
$this->load->view('layouts/header_admin');

//Matrix Keputusan (X)
$matriks_x = array();
foreach ($alternatifs as $alternatif) :
	foreach ($kriterias as $kriteria) :

		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;

		$data_pencocokan = $this->Perhitungan_model->data_nilai($id_alternatif, $id_kriteria);
		if (!is_null($data_pencocokan)) {
			$nilai = $data_pencocokan['nilai'];
			$matriks_x[$id_kriteria][$id_alternatif] = $nilai;
		} else {

			$matriks_x[$id_kriteria][$id_alternatif] = 0;
		}
	endforeach;
endforeach;

//Matriks Ternormalisasi (R)
$matriks_r = array();
foreach ($matriks_x as $id_kriteria => $penilaians) :

	$jumlah_kuadrat = 0;
	foreach ($penilaians as $penilaian) :
		$jumlah_kuadrat += pow($penilaian, 2);
	endforeach;
	$akar_kuadrat = sqrt($jumlah_kuadrat);

	foreach ($penilaians as $id_alternatif => $penilaian) :
		$matriks_r[$id_kriteria][$id_alternatif] = $penilaian / $akar_kuadrat;
	endforeach;

endforeach;

//Matriks Y
$matriks_y = array();
foreach ($alternatifs as $alternatif) :
	foreach ($kriterias as $kriteria) :

		$bobot = $kriteria->bobot;
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;

		$nilai_r = $matriks_r[$id_kriteria][$id_alternatif];
		$matriks_y[$id_kriteria][$id_alternatif] = $bobot * $nilai_r;

	endforeach;
endforeach;

//Solusi Ideal Positif & Negarif
$solusi_ideal_positif = array();
$solusi_ideal_negatif = array();
foreach ($kriterias as $kriteria) :

	$id_kriteria = $kriteria->id_kriteria;
	$type_kriteria = $kriteria->jenis;

	$nilai_max = @(max($matriks_y[$id_kriteria]));
	$nilai_min = @(min($matriks_y[$id_kriteria]));

	if ($type_kriteria == 'Benefit') :
		$s_i_p = $nilai_max;
		$s_i_n = $nilai_min;
	elseif ($type_kriteria == 'Cost') :
		$s_i_p = $nilai_min;
		$s_i_n = $nilai_max;
	endif;

	$solusi_ideal_positif[$id_kriteria] = $s_i_p;
	$solusi_ideal_negatif[$id_kriteria] = $s_i_n;

endforeach;

//Jarak Ideal Positif & Negatif
$jarak_ideal_positif = array();
$jarak_ideal_negatif = array();
foreach ($alternatifs as $alternatif) :

	$id_alternatif = $alternatif->id_alternatif;
	$jumlah_kuadrat_jip = 0;
	$jumlah_kuadrat_jin = 0;

	// Mencari penjumlahan kuadrat
	foreach ($matriks_y as $id_kriteria => $penilaians) :

		$hsl_pengurangan_jip = $penilaians[$id_alternatif] - $solusi_ideal_positif[$id_kriteria];
		$hsl_pengurangan_jin = $penilaians[$id_alternatif] - $solusi_ideal_negatif[$id_kriteria];

		$jumlah_kuadrat_jip += pow($hsl_pengurangan_jip, 2);
		$jumlah_kuadrat_jin += pow($hsl_pengurangan_jin, 2);

	endforeach;

	// Mengakarkan hasil penjumlahan kuadrat
	$akar_kuadrat_jip = sqrt($jumlah_kuadrat_jip);
	$akar_kuadrat_jin = sqrt($jumlah_kuadrat_jin);

	// Memasukkan ke array matriks jip & jin
	$jarak_ideal_positif[$id_alternatif] = $akar_kuadrat_jip;
	$jarak_ideal_negatif[$id_alternatif] = $akar_kuadrat_jin;

endforeach;

//Kedekatan Relatif Terhadap Solusi Ideal (V)
$kedekatan_relatif = array();
foreach ($alternatifs as $alternatif) :

	$s_negatif = $jarak_ideal_negatif[$alternatif->id_alternatif];
	$s_positif = $jarak_ideal_positif[$alternatif->id_alternatif];

	$nilai_v = @($s_negatif / ($s_positif + $s_negatif));

	$kedekatan_relatif[$alternatif->id_alternatif]['id_alternatif'] = $alternatif->id_alternatif;
	$kedekatan_relatif[$alternatif->id_alternatif]['nama'] = $alternatif->nama;
	$kedekatan_relatif[$alternatif->id_alternatif]['nilai'] = $nilai_v;

endforeach;
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-calculator"></i> Data Perhitungan</h1>
</div>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Matrix Keputusan (X)</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria) : ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($alternatifs as $alternatif) : ?>
						<tr align="center">
							<td><?= $no; ?></td>
							<td align="left"><?= $alternatif->nama ?></td>
							<?php
							foreach ($kriterias as $kriteria) :
								$id_alternatif = $alternatif->id_alternatif;
								$id_kriteria = $kriteria->id_kriteria;
								echo '<td>';
								echo $matriks_x[$id_kriteria][$id_alternatif];
								echo '</td>';
							endforeach
							?>
						</tr>
					<?php
						$no++;
					endforeach
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Bobot Kriteria (W)</h6>
	</div>

	<div class="card-body">
		<div class="alert alert-danger text-justify">
			Bobot kriteria didapatkan dari perhitungan menggunakan metode <b>AHP</b>. Silahkan menuju ke halaman <a href="<?= base_url('') ?>Kriteria/prioritas" class="btn btn-info">Kriteria</a> untuk melihat proses perhitungan.
		</div>
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<?php foreach ($kriterias as $kriteria) : ?>
							<th><?= $kriteria->kode_kriteria ?> (<?= $kriteria->jenis ?>)</th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<?php foreach ($kriterias as $kriteria) : ?>
							<td>
								<?php
								echo $kriteria->bobot;
								?>
							</td>
						<?php endforeach ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Matriks Ternormalisasi (R)</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria) : ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($alternatifs as $alternatif) : ?>
						<tr align="center">
							<td><?= $no; ?></td>
							<td align="left"><?= $alternatif->nama ?></td>
							<?php
							foreach ($kriterias as $kriteria) :
								$id_alternatif = $alternatif->id_alternatif;
								$id_kriteria = $kriteria->id_kriteria;
								echo '<td>';
								echo $matriks_r[$id_kriteria][$id_alternatif];
								echo '</td>';
							endforeach;
							?>
						</tr>
					<?php
						$no++;
					endforeach
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Matriks Y</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria) : ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($alternatifs as $alternatif) : ?>
						<tr align="center">
							<td><?= $no; ?></td>
							<td align="left"><?= $alternatif->nama ?></td>
							<?php
							foreach ($kriterias as $kriteria) :
								$id_alternatif = $alternatif->id_alternatif;
								$id_kriteria = $kriteria->id_kriteria;
								echo '<td>';
								echo $matriks_y[$id_kriteria][$id_alternatif];
								echo '</td>';
							endforeach;
							?>
						</tr>
					<?php
						$no++;
					endforeach
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Solusi Ideal Positif (A+)</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<?php foreach ($kriterias as $kriteria) : ?>
							<th><?php echo $kriteria->keterangan; ?> (<?php echo $kriteria->kode_kriteria; ?>)</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<?php foreach ($kriterias as $kriteria) : ?>
							<td>
								<?php
								$id_kriteria = $kriteria->id_kriteria;
								echo $solusi_ideal_positif[$id_kriteria];
								?>
							</td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Solusi Ideal Negatif (A-)</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<?php foreach ($kriterias as $kriteria) : ?>
							<th><?php echo $kriteria->keterangan; ?> (<?php echo $kriteria->kode_kriteria; ?>)</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<?php foreach ($kriterias as $kriteria) : ?>
							<td>
								<?php
								$id_kriteria = $kriteria->id_kriteria;
								echo $solusi_ideal_negatif[$id_kriteria];
								?>
							</td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Jarak Ideal Positif (S<sub>i</sub>+)</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Nama Alternatif</th>
						<th width="30%">Jarak Ideal Positif</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($alternatifs as $alternatif) : ?>
						<tr align="center">
							<td><?php echo $no; ?></td>
							<td align="left"><?php echo $alternatif->nama; ?></td>
							<td>
								<?php
								$id_alternatif = $alternatif->id_alternatif;
								echo $jarak_ideal_positif[$id_alternatif];
								?>
							</td>
						</tr>
					<?php
						$no++;
					endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Jarak Ideal Negatif (S<sub>i</sub>-)</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Nama Alternatif</th>
						<th width="30%">Jarak Ideal Negatif</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($alternatifs as $alternatif) : ?>
						<tr align="center">
							<td><?php echo $no; ?></td>
							<td align="left"><?php echo $alternatif->nama; ?></td>
							<td>
								<?php
								$id_alternatif = $alternatif->id_alternatif;
								echo $jarak_ideal_negatif[$id_alternatif];
								?>
							</td>
						</tr>
					<?php
						$no++;
					endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Kedekatan Relatif Terhadap Solusi Ideal (V)</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Nama Alternatif</th>
						<th width="30%">Nilai</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					$this->Perhitungan_model->hapus_hasil();
					foreach ($kedekatan_relatif as $alternatif) : ?>
						<tr align="center">
							<td><?php echo $no; ?></td>
							<td align="left"><?php echo $alternatif['nama']; ?></td>
							<td><?php echo $alternatif['nilai']; ?></td>
						</tr>
					<?php
						$no++;
						$hasil_akhir = [
							'id_alternatif' => $alternatif['id_alternatif'],
							'nilai' => $alternatif['nilai']
						];
						$this->Perhitungan_model->insert_hasil($hasil_akhir);
					endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
$this->load->view('layouts/footer_admin');
?>
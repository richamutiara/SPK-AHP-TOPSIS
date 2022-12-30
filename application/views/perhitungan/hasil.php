<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-chart-area"></i> Data Hasil Akhir Kelas <td><?= $kelas->nama; ?></td>
	</h1>

	<!-- <a href="<?= base_url('Laporan/cetak_laporan_hasil'); ?>" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a> -->
</div>

<?php if ($this->session->userdata('id_user_level') == '1') : ?>
	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Hasil Akhir Perankingan</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered dataTableHasil" width="100%" cellspacing="0">
					<thead class="bg-info text-white">
						<tr align="center">
							<th>Alternatif</th>
							<th>Nilai</th>
							<th width="15%">Ranking</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($hasil as $keys) : ?>
							<tr align="center">
								<td align="left">
									<?php
									$nama_alternatif = $this->Perhitungan_model->get_hasil_alternatif($keys->id_alternatif);
									echo $nama_alternatif['nama'];
									?>

								</td>
								<td><?= $keys->nilai ?></td>
								<td><?= $no; ?></td>
							</tr>
						<?php
							$no++;
						endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php elseif ($this->session->userdata('id_user_level') == '2') : ?>
	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Hasil Akhir Perankingan</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered dataTableHasil" width="100%" cellspacing="0">
					<thead class="bg-info text-white">
						<tr align="center">
							<th>Nama Lengkap</th>
							<th>Total Perhitungan Nilai</th>
							<th width="15%">Ranking</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($hasil as $keys) : ?>
							<?php
							$nama_alternatif = $this->Perhitungan_model->get_hasil_alternatif($keys->id_alternatif);
							if ($this->session->userdata('nama') == $nama_alternatif['nama']) :

							?>
								<tr align="center">
									<td align="left">
										<?= $nama_alternatif['nama']; ?>
									</td>
									<td><?= $keys->nilai ?></td>
									<td><?= $no; ?> dari <?= count($hasil); ?> </td>
								</tr>
						<?php
							endif;
							$no++;
						endforeach ?>
					</tbody>

				</table>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
$this->load->view('layouts/footer_admin');
?>
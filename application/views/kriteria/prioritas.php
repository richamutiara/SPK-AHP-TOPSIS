<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Data Kriteria</h1>

	<a href="<?= base_url('Kriteria'); ?>" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<?= $this->session->flashdata('message'); ?> 

<div class="alert alert-info">
	Silahkan isi terlebih dahulu nilai kriteria menggunakan perbandingan berpasangan berdasarkan skala perbandingan 1-9 (sesuai teori) kemudian klik <b>SIMPAN</b>. Setelah itu klik <b>CEK KONSISTENSI</b> untuk melakukan pembobotan preferensi dengan menggunakan metode AHP.
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-table"></i> Perbandingan Data Antar Kriteria</h6>
    </div>
	
	<?= form_open('Kriteria/prioritas') ?>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="text-right" width="25%">Nama Kriteria</th>
							<th class="text-center" width="50%">Skala Perbandingan</th>
							<th class="text-left" width="25%">Nama Kriteria</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$i = 0;
						foreach ($kriteria as $row1) :
							$ii = 0;
							foreach ($kriteria as $row2) :
								if ($i < $ii) :
									$nilai = $kriteria_ahp[$row1->id_kriteria][$row2->id_kriteria];
						?>
									<tr>
										<td class="text-right">(<?= $row1->kode_kriteria ?>) <?= $row1->keterangan ?></td>
										<td class="text-center">
											<div class="btn-group btn-group-toggle" data-toggle="buttons">
												<label class="btn btn-info <?= $nilai == -9 ? "active" : "" ?>"><input type="radio" id="radio_a_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="-9" <?= $nilai == -9 ? "checked" : "" ?>>9</label>
												<label class="btn btn-info <?= $nilai == -8 ? "active" : "" ?>"><input type="radio" id="radio_b_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="-8" <?= $nilai == -8 ? "checked" : "" ?>>8</label>
												<label class="btn btn-info <?= $nilai == -7 ? "active" : "" ?>"><input type="radio" id="radio_c_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="-7" <?= $nilai == -7 ? "checked" : "" ?>>7</label>
												<label class="btn btn-info <?= $nilai == -6 ? "active" : "" ?>"><input type="radio" id="radio_d_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="-6" <?= $nilai == -6 ? "checked" : "" ?>>6</label>
												<label class="btn btn-info <?= $nilai == -5 ? "active" : "" ?>"><input type="radio" id="radio_e_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="-5" <?= $nilai == -5 ? "checked" : "" ?>>5</label>
												<label class="btn btn-info <?= $nilai == -4 ? "active" : "" ?>"><input type="radio" id="radio_f_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="-4" <?= $nilai == -4 ? "checked" : "" ?>>4</label>
												<label class="btn btn-info <?= $nilai == -3 ? "active" : "" ?>"><input type="radio" id="radio_g_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="-3" <?= $nilai == -3 ? "checked" : "" ?>>3</label>
												<label class="btn btn-info <?= $nilai == -2 ? "active" : "" ?>"><input type="radio" id="radio_h_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="-2" <?= $nilai == -2 ? "checked" : "" ?>>2</label>
												<label class="btn btn-info <?= $nilai == 1 ? "active" : "" ?>"><input type="radio" id="radio_i_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="1" <?= $nilai == 1 ? "checked" : "" ?>>1</label>
												<label class="btn btn-info <?= $nilai == 2 ? "active" : "" ?>"><input type="radio" id="radio_j_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="2" <?= $nilai == 2 ? "checked" : "" ?>>2</label>
												<label class="btn btn-info <?= $nilai == 3 ? "active" : "" ?>"><input type="radio" id="radio_k_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="3" <?= $nilai == 3 ? "checked" : "" ?>>3</label>
												<label class="btn btn-info <?= $nilai == 4 ? "active" : "" ?>"><input type="radio" id="radio_l_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="4" <?= $nilai == 4 ? "checked" : "" ?>>4</label>
												<label class="btn btn-info <?= $nilai == 5 ? "active" : "" ?>"><input type="radio" id="radio_m_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="5" <?= $nilai == 5 ? "checked" : "" ?>>5</label>
												<label class="btn btn-info <?= $nilai == 6 ? "active" : "" ?>"><input type="radio" id="radio_n_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="6" <?= $nilai == 6 ? "checked" : "" ?>>6</label>
												<label class="btn btn-info <?= $nilai == 7 ? "active" : "" ?>"><input type="radio" id="radio_o_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="7" <?= $nilai == 7 ? "checked" : "" ?>>7</label>
												<label class="btn btn-info <?= $nilai == 8 ? "active" : "" ?>"><input type="radio" id="radio_p_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="8" <?= $nilai == 8 ? "checked" : "" ?>>8</label>
												<label class="btn btn-info <?= $nilai == 9 ? "active" : "" ?>"><input type="radio" id="radio_q_<?= $no ?>" name="nilai_<?= $row1->id_kriteria . '_' . $row2->id_kriteria ?>" value="9" <?= $nilai == 9 ? "checked" : "" ?>>9</label>
											</div>
										</td>
										<td class="text-left">(<?= $row2->kode_kriteria ?>) <?= $row2->keterangan ?></td>
									</tr>
						<?php
									$no++;
								endif;
								$ii++;
							endforeach;
							$i++;
						endforeach;
						?>
						<tr>
							<td class="text-center" colspan="3">
								<button type="submit" name="save" class="btn btn-primary"><i class="fas fa-fw fa-save mr-1"></i> Simpan</button>
								<button type="submit" name="check" class="btn btn-success"><i class="fas fa-fw fa-check mr-1"></i> Cek Konsistensi</button>
								<a href="#" data-href="<?= base_url('kriteria/reset') ?>" data-toggle="modal" data-target="#confirm-reset" class="btn btn-danger"><i class="fas fa-fw fa-sync mr-1"></i> Reset</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>	
	<?= form_close() ?>
</div>

<?php if (isset($_POST['check']) and empty($this->session->flashdata('pesan_error'))) : ?>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-table"></i> Matriks Perbandingan Berpasangan</h6>
		</div>
		
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<?= $list_data ?>
				</table>
			</div>
		</div>
	</div>
	
	
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-table"></i> Matriks Nilai Kriteria (Normalisasi)</h6>
		</div>
		
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<?= $list_data2 ?>
				</table>
			</div>
		</div>
	</div>
	
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-table"></i> Matriks Penjumlahan Setiap Baris</h6>
		</div>
		
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<?= $list_data3 ?>
				</table>
			</div>
		</div>
	</div>
	
	
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-table"></i> Perhitungan Rasio Konsistensi</h6>
		</div>
		
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<?= $list_data4 ?>
				</table>
				<?= $list_data5 ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('#confirm-reset').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
    });
</script>

<div class="modal fade" id="confirm-reset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p>Anda yakin akan mengatur ulang semua nilai perbandingan kriteria ini ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fas fa-fw fa-times mr-1"></i> Batal</button>
                <a class="btn btn-danger btn-ok" href="<?= base_url('kriteria/reset') ?>"><i class="fas fa-fw fa-check mr-1"></i> Reset</a>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('layouts/footer_admin'); ?>
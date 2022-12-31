<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-edit"></i> Data Penilaian Kelas <?= $kelas->nama; ?></h1>
	<?php if ($data_excel > 0) : ?>
		<div class="flex">
			<a href="<?= base_url('Penilaian/format_excel'); ?>" class="btn btn-primary btn-icon-split ">
				<span class="icon text-white-50">
					<i class="fas fa-file-download"></i>
				</span>
				<span class="text">Format Excel </span>
			</a>
			<button type="button" class="btn btn-success btn-icon-split " data-toggle="modal" data-target="#modalImport" <?php if ($data_excel == 0) { ?> disabled <?php   } ?>>
				<span class="icon text-white-50">
					<i class="fas fa-file-upload"></i>
				</span>
				<span class="text">Import Excel </span>
			</button>
		</div>
	<?php endif; ?>
</div>

<?= $this->session->flashdata('message'); ?>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Daftar Data Penilaian</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Alternatif</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($alternatif as $keys) : ?>
						<tr align="center">
							<td><?= $no ?></td>
							<td align="left"><?= $keys->nama ?></td>
							<?php $cek_tombol = $this->Penilaian_model->untuk_tombol($keys->id_alternatif); ?>
							<td>
								<?php if ($cek_tombol == 0) { ?>
									<a data-toggle="modal" href="#set<?= $keys->id_alternatif ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Input</a>
								<?php } else { ?>
									<a data-toggle="modal" href="#edit<?= $keys->id_alternatif ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
								<?php } ?>
							</td>
						</tr>

						<!-- Modal -->
						<div class="modal fade" id="set<?= $keys->id_alternatif ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Input Penilaian</h5>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									</div>
									<?= form_open('Penilaian/tambah_penilaian') ?>

									<div class="modal-body">
										<?php foreach ($kriteria as $key) : ?>
											<?php
											$sub_kriteria = $this->Penilaian_model->data_sub_kriteria($key->id_kriteria);
											?>
											<?php if ($sub_kriteria != NULL) : ?>
												<input type="text" name="id_alternatif" value="<?= $keys->id_alternatif ?>" hidden>
												<input type="text" name="id_kriteria[]" value="<?= $key->id_kriteria ?>" hidden>
												<div class="form-group">
													<label class="font-weight-bold" for="<?= $key->id_kriteria ?>"><?= $key->keterangan ?></label>
													<select name="nilai[]" class="form-control" id="<?= $key->id_kriteria ?>" required>
														<option value="">--Pilih--</option>
														<?php foreach ($sub_kriteria as $subs_kriteria) : ?>
															<option value="<?= $subs_kriteria['id_sub_kriteria'] ?>"><?= $subs_kriteria['deskripsi'] ?> </option>
														<?php endforeach ?>
													</select>
												</div>
											<?php endif ?>
										<?php endforeach ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
										<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
									</div>
									</form>
								</div>
							</div>
						</div>

						<!-- Modal -->
						<div class="modal fade" id="edit<?= $keys->id_alternatif ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Edit Penilaian</h5>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									</div>
									<?= form_open('Penilaian/update_penilaian') ?>
									<div class="modal-body">
										<?php foreach ($kriteria as $key) : ?>
											<?php
											$sub_kriteria = $this->Penilaian_model->data_sub_kriteria($key->id_kriteria);
											?>
											<?php if ($sub_kriteria != NULL) : ?>
												<input type="text" name="id_alternatif" value="<?= $keys->id_alternatif ?>" hidden>
												<input type="text" name="id_kriteria[]" value="<?= $key->id_kriteria ?>" hidden>
												<div class="form-group">
													<label class="font-weight-bold" for="<?= $key->id_kriteria ?>"><?= $key->keterangan ?></label>
													<select name="nilai[]" class="form-control" id="<?= $key->id_kriteria ?>" required>
														<option value="">--Pilih--</option>
														<?php foreach ($sub_kriteria as $subs_kriteria) : ?>
															<?php $s_option = $this->Penilaian_model->data_penilaian($keys->id_alternatif, $subs_kriteria['id_kriteria']); ?>
															<option value="<?= $subs_kriteria['id_sub_kriteria'] ?>" <?php if ($subs_kriteria['id_sub_kriteria'] == $s_option['nilai']) {
																															echo "selected";
																														} ?>><?= $subs_kriteria['deskripsi'] ?> </option>
														<?php endforeach ?>
													</select>
												</div>
											<?php endif ?>
										<?php endforeach ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
										<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
									</div>
									</form>
								</div>
							</div>
						</div>
					<?php
						$no++;
					endforeach
					?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImport" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalImport">Import File Excel</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?= form_open_multipart('Penilaian/import_excel') ?>
				<div class="modal-body px-5">
					<label>File Excel (.xls atau .xlsx)</label>
					<div class="custom-file">
						<input type="file" name="file_excel" id="file_excel" class="form-control custom-file_input" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
					<button type="submit" class="btn btn-success">Import</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Batas Modal -->

	<?php $this->load->view('layouts/footer_admin'); ?>
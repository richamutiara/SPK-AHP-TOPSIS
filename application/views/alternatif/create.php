<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Alternatif</h1>

	<a href="<?= base_url('Alternatif'); ?>" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<?= $this->session->flashdata('message'); ?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-plus"></i> Tambah Data Alternatif</h6>
	</div>

	<?php echo form_open('Alternatif/store'); ?>
	<div class="card-body">
		<div class="row">
			<div class="form-group col-md-6">
				<label class="font-weight-bold">Nama Alternatif</label>
				<input autocomplete="off" type="text" name="nama" required class="form-control" />
			</div>
			<div class="form-group col-md-6">
				<label class="font-weight-bold">Kelas</label>
				<select class="form-control" name="kelas" required>
					<option value="">--Pilih Kelas--</option>
					<?php foreach ($list_kelas as $k) : ?>
						<option value="<?php echo $k->id_kelas ?>"><?php echo $k->nama ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="card-footer text-right">
		<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
		<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
	</div>
	<?php echo form_close() ?>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>
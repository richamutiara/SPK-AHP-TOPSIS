<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users-cog"></i> Data User</h1>

	<a href="<?= base_url('User'); ?>" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<?= $this->session->flashdata('message'); ?>



<div class="card shadow mb-4">
	<div class="card-header py-3">
		<ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fas fa-fw fa-plus"></i> Tambah Data Admin Guru</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><i class="fas fa-fw fa-plus"></i> Tambah Data User Alternatif</a>
			</li>
		</ul>
	</div>

	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
			<?php echo form_open('User/storeGuru'); ?>
			<div class="card-body">
				<div class="row">

					<div class="form-group col-md-6">
						<label class="font-weight-bold">Nama Lengkap</label>
						<input autocomplete="off" type="text" name="gNama" required class="form-control" />
					</div>
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Kelas</label>
						<select class="form-control" name="gKelas" required>
							<option value="">--Pilih Kelas--</option>
							<?php foreach ($list_kelas as $k) : ?>
								<option value="<?php echo $k->id_kelas ?>"><?php echo $k->nama ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Username</label>
						<input autocomplete="off" type="text" name="gUsername" required class="form-control" />
					</div>
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Password</label>
						<input autocomplete="off" type="password" name="gPassword" required class="form-control" />
					</div>
					<div class="form-group col-md-6">
						<label class="font-weight-bold">E-Mail</label>
						<input autocomplete="off" type="email" name="gEmail" required class="form-control" />
					</div>
				</div>
			</div>
			<div class="card-footer text-right">
				<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
				<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
			</div>
			<?php echo form_close() ?>
		</div>



		<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
			<?php echo form_open('User/storeAlternatif'); ?>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Nama Lengkap</label>
						<select class="form-control" name="aNama" required>
							<option value="">--Pilih Alternatif --</option>
							<?php foreach ($list_alternatif as $a) : ?>
								<option value="<?php echo $a->id_alternatif ?>"><?php echo $a->nama ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label class="font-weight-bold">E-Mail</label>
						<input autocomplete="off" type="email" name="aEmail" required class="form-control" />
					</div>

					<div class="form-group col-md-6">
						<label class="font-weight-bold">Username</label>
						<input autocomplete="off" type="text" name="aUsername" required class="form-control" />
					</div>

					<div class="form-group col-md-6">
						<label class="font-weight-bold">Password</label>
						<input autocomplete="off" type="password" name="aPassword" required class="form-control" />
					</div>

				</div>
			</div>
			<div class="card-footer text-right">
				<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
				<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
			</div>
			<?php echo form_close() ?>
		</div>
	</div>


</div>

<?php $this->load->view('layouts/footer_admin'); ?>
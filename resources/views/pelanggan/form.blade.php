<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form action="" method="post" class="form-horizontal">
        @csrf
        @method('post')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Pelanggan</h5>
            <!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-solid fa-xmark"></i></button> -->
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="nama_member" class="col-md-2 col-md-offset-1 control-label">Nama</label>
              <div class="col-md-8">
                <input type="text" name="nama_member" id="nama_member" class="form-control" required="" autofocus="">
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="alamat_member" class="col-md-2 col-md-offset-1 control-label">alamat</label>
              <div class="col-md-8">
                <input type="text" name="alamat_member" id="alamat_member" class="form-control" required="" autofocus="">
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="jenis_kelamin" class="col-md-2 col-md-offset-1 control-label">Jenis Kelamin</label>
              <div class="col-md-8">
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required="">
                  <option>Pilih Jenis Kelamin</option>
                  <option value="pria">Pria</option>
                  <option value="wanita">Wanita</option>
                </select>
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="telepon" class="col-md-2 col-md-offset-1 control-label">Kontak</label>
              <div class="col-md-8">
                <input type="text" name="telepon" id="telepon" class="form-control" required="" autofocus="">
                <span class="help-block with-errors"></span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-flat btn-secondary btn-close" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-sm btn-flat btn-primary">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
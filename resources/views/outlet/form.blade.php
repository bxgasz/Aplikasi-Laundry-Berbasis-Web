<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form action="" method="post" class="form-horizontal">
        @csrf
        @method('post')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Outlet</h5>
            <!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-solid fa-xmark"></i></button> -->
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="name" class="col-md-2 col-md-offset-1 control-label">Outlet</label>
              <div class="col-md-8">
                <input type="text" name="name" id="name" class="form-control" required="" autofocus="">
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="alamat" class="col-md-2 col-md-offset-1 control-label">Alamat</label>
              <div class="col-md-8">
                <input type="text" name="alamat" id="alamat" class="form-control" required="" autofocus="">
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="kontak" class="col-md-2 col-md-offset-1 control-label">Kontak</label>
              <div class="col-md-8">
                <input type="text" name="kontak" id="kontak" class="form-control" required="" autofocus="">
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
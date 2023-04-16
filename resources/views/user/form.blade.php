<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="" method="post" class="form-horizontal">
      @csrf
      @method('post')
      <!-- <input type="hidden" name="_method" value="PUT"> -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah User</h5>
          <!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-solid fa-xmark"></i></button> -->
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="name" class="col-md-2 col-md-offset-1 control-label">Nama</label>
            <div class="col-md-8">
              <input type="text" name="name" id="name" class="form-control" required="" autofocus="">
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="username" class="col-md-2 col-md-offset-1 control-label">Username</label>
            <div class="col-md-8">
              <input type="text" name="username" id="username" class="form-control" required="" autofocus="">
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="role" class="col-md-2 col-md-offset-1 control-label">Role</label>
            <div class="col-md-8">
              <select name="role" id="role" class="form-control" required="">
                <option>Role User</option>
                  <option value="kasir">Kasir</option>
                  <option value="owner">Owner</option>
              </select>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="id_outlet" class="col-md-2 col-md-offset-1 control-label">Outlet</label>
            <div class="col-md-8">
              <select name="id_outlet" id="id_outlet" class="form-control" required="">
                <option>Pilih Outlet</option>
                @foreach($outlet as $key => $item)
                  <option value="{{ $key }}">{{ $item }}</option>
                @endforeach
              </select>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="email" class="col-md-2 col-md-offset-1 control-label">Email</label>
            <div class="col-md-8">
              <input type="text" name="email" id="email" class="form-control" required="" autofocus="">
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="password" class="col-md-2 col-md-offset-1 control-label">Passoword</label>
            <div class="col-md-8">
              <input type="text" name="password" id="password" class="form-control" required="" minlength="6">
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="password_confirmation" class="col-md-2 col-md-offset-1 control-label">Konfirmasi Password</label>
            <div class="col-md-8">
              <input type="text" name="password_confirmation" id="password_confirmation" class="form-control" required="" data-match="#password">
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
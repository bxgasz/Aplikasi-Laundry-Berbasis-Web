<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form action="" method="post" class="form-horizontal">
        @csrf
        @method('post')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Paket</h5>
            <!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-solid fa-xmark"></i></button> -->
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="nama_paket" class="col-md-2 col-md-offset-1 control-label">Nama</label>
              <div class="col-md-8">
                <input type="text" name="nama_paket" id="nama_paket" class="form-control" required="" autofocus="">
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="alamat" class="col-md-2 col-md-offset-1 control-label">Outlet</label>
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
              <label for="jenis" class="col-md-2 col-md-offset-1 control-label">Jenis</label>
              <div class="col-md-8">
                <select name="jenis" id="jenis" class="form-control" required="">
                  <option>Pilih Jenis</option>
                  <option value="kiloan">Kiloan</option>
                  <option value="selimut">Selimut</option>
                  <option value="bedcover">Bedcover</option>
                  <option value="kaos">Kaos</option>
                  <option value="lain">Lain</option>
                </select>
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="harga" class="col-md-2 col-md-offset-1 control-label">Harga</label>
              <div class="col-md-8">
                <input type="text" name="harga" id="harga" class="form-control" required="" autofocus="">
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
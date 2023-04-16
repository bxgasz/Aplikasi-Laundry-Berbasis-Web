@extends('layouts.master')

@section('title')
    Edit Profil
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Profil</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <form action="{{ route('user.update_pr') }}" method="post" data-toggle="validator" enctype="multipart/form-data" class="form-profil">
                    @csrf
                    <div class="box-body">
                        <div class="alert alert-info alert-dismissible" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" ario-hidden="true">&times;</button>
                            <i class="icon fa fa-check"></i> Perubahan berhasil
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-lg-2 col-lg-offset-1 control-label">Nama</label>
                            <div class="col-lg-6">
                                <input type="text" name="name" id="name" class="form-control" required autofocus value="{{ $profil->name }}">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-lg-2 col-lg-offset-1 control-label">Username</label>
                            <div class="col-lg-6">
                                <input type="text" name="username" id="username" class="form-control" required autofocus value="{{ $profil->username }}">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="foto" class="col-lg-2 col-lg-offset-1 control-label">Profile</label>
                            <div class="col-lg-4">
                                <input type="file" name="foto" id="foto" class="form-control" 
                                onchange="preview('.tampil_foto', this.files[0])">
                                <span class="help-block with-errors"></span>
                                <br>
                                <div class="tampil_foto"><img src="{{ url($profil->foto ?? '/') }}" width="200"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="old_password" class="col-lg-2 col-lg-offset-1 control-label">Passoword Lama</label>
                            <div class="col-lg-8">
                              <input type="text" name="old_password" id="old_password" class="form-control" minlength="6">
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-lg-2 col-lg-offset-1 control-label">Passoword</label>
                            <div class="col-lg-8">
                              <input type="text" name="password" id="password" class="form-control" minlength="6">
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-lg-2 col-lg-offset-1 control-label">Konfirmasi Password</label>
                            <div class="col-lg-8">
                              <input type="text" name="password_confirmation" id="password_confirmation" class="form-control" data-match="#password">
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#old_password').on('keyup', function(){
                if ($(this).val() != "") {
                    $('#password, #password-confirmation').attr('required', true);
                } else {
                    $('#password, #password-confirmation').attr('required', false);
                }
            })

            $('.form-profil').validator().on('submit', function (e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('.form-profil').attr('action'),
                        type: $('.form-profil').attr('method'),
                        data: new FormData($('.form-profil')[0]),
                        async: false,
                        processData: false,
                        contentType: false
                    })
                    .done(response => {
                        $('[name=name]').val(response.name);
                        $('.tampil-foto').html(`<img src="{{ url('/') }}${response.foto}" width="200">`);
                        $('.img-profil').attr('src', `{{ url('/') }}/${response.foto}`);

                        $('.alert').fadeIn();

                        setTimeout(() => {
                            $('.alert').fadeOut();
                        }, 3000);
                    })
                    .fail(errors => {
                        if (errors.status == 422) {
                            alert(errors.response.responseJSON);
                        }
                        else {
                            alert('Tidak dapat menyimpan data');
                        }
                        return;
                    })
                }
            });
        });
    </script>
@endpush
@extends('layouts.master')

@section('title')
    Daftar Pelanggan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pelanggan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm( '{{ route('pelanggan.store') }}' )" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
                <button onclick="cetakMember( '{{ route('pelanggan.cetak') }}' )" class="btn btn-info btn-xs btn-flat"><i class="fa fa-id-card"></i> Cetak</button>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('pelanggan.form')

@endsection

@push('scripts')
<script>
    let table;
    $(function () {
        table = $('.table').DataTable({
            processing:true,
            autoWidth:false,
            ajax: {
                url: '{{ route('pelanggan.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable:false},
                {data: 'DT_RowIndex', searchable: false, sortable:false},
                {data: 'nama_member'},
                {data: 'jenis_kelamin'},
                {data: 'alamat_member'},
                {data: 'telepon'},
                {data: 'aksi', searchable: false, sortable:false},
            ]
        });
        // validator
        $('#modal-form').validator().on('submit', function(e) {
            if (!e.preventDefault()){
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                .done((response) => {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Data Gagal Disimpan!!');
                    return;
                });
            }
        });
        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });

    function addForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Pelanggan');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }

    function editForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Pelanggan');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=name]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_member]').val(response.nama_member);
                $('#modal-form [name=alamat_member]').val(response.alamat_member);
                $('#modal-form [name=jenis_kelamin]').val(response.jenis_kelamin);
                $('#modal-form [name=telepon]').val(response.telepon);
            })
            .fail((errors) => {
                alert('Tidak Dapat Menampilkan Data');
                return;
            })
    }

    function deleteData(url){
        if (confirm('Yakin Ingin Menghapus Data??')) {
            $.post(url, {
            '_token':$('[name=csrf-token]').attr('content'),
            '_method':'delete'
            })
            .done((response) => {
                table.ajax.reload();
            })
            .fail((errors) => {
                alert('Tidak Dapat Menghapus Data');
                return;
            })
        }
    }

    function cetakMember(url) {
        if($('input:checked').length < 1){
            alert('Pilih Member untuk Dicetak');
            return;
        } else {
            $('.form-member').attr('action', url).attr('target', '_blank').submit();
        }
    }
</script>
@endpush
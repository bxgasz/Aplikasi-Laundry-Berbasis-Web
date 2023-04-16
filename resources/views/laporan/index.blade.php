@extends('layouts.master')

@section('title')
    Daftar Outlet
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Outlet</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm( '{{ route('outlet.store') }}' )" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Outlet</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('outlet.form')

@endsection

@push('scripts')
<script>
    let table;
    $(function () {
        table = $('.table').DataTable({
            processing:true,
            autoWidth:false,
            ajax: {
                url: '{{ route('outlet.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable:false},
                {data: 'nama_outlet'},
                {data: 'alamat_outlet'},
                {data: 'telp_outlet'},
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
    });

    function addForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Outlet');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }

    function editForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Outlet');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=name]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=name]').val(response.nama_outlet);
                $('#modal-form [name=alamat]').val(response.alamat_outlet);
                $('#modal-form [name=kontak]').val(response.telp_outlet);
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
</script>
@endpush
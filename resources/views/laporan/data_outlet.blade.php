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

@endsection

@push('scripts')
<script>
    let table;
    $(function () {
        table = $('.table').DataTable({
            processing:true,
            autoWidth:false,
            ajax: {
                url: '{{ route('outlets.laporan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable:false},
                {data: 'nama_outlet'},
                {data: 'alamat_outlet'},
                {data: 'telp_outlet'},
                {data: 'aksi', searchable: false, sortable:false},
            ]
        });
    });
</script>
@endpush
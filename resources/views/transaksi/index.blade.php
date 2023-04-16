@extends('layouts.master')

@section('title')
    Daftar Transaksi
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Transaksi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                {{-- <button onclick="baruForm()" class="btn btn-danger btn-xs btn-flat">Baru</button>
                <button onclick="prosesForm()" class="btn btn-info btn-xs btn-flat">Proses</button>
                <button onclick="selesaiForm()" class="btn btn-warning btn-xs btn-flat">Selesai</button> --}}
                <button id="all" class="btn btn-xs btn-success btn-flat"><i class="fa fa-thumbs-up"></i> All</button>
                <button id="baru" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-level-up"></i> Baru</button>
                <button id="proses" class="btn btn-xs btn-info btn-flat"><i class="fa fa-hourglass-half"></i>Proses</button>
                <button id="selesai" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-check"></i> Selesai</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>statuss</th>
                        <th>Tanggal</th>
                        <th>Nama Member</th>
                        <th>Pajak</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Kasir</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('transaksi.detail')

@endsection

@push('scripts')
<script>
    let table, table1;
    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'statusaatini', visible:false},
                {data: 'tanggal'},
                {data: 'nama_member'},
                {data: 'pajak'},
                {data: 'total_bayar'},
                {data: 'status'},
                {data: 'kasir'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $(document).on('change', '.status', function () {
            let id = $(this).data('id');
            let status = $(this).val();

            $.post(`{{ url('/penjualan/status') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'status': status
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });

        $('#all').on('click', function () {
            table.columns(1).search("", true, false, true).draw();
        });

        $('#baru').on('click', function () {
            table.columns(1).search("baru", true, false, true).draw();
        });

        $('#proses').on('click', function () {
            table.columns(1).search("proses", true, false, true).draw();
        }); 

        $('#selesai').on('click', function () {
            table.columns(1).search("selesai", true, false, true).draw();
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable:false},
                {data: 'nama_paket'},
                {data: 'qty'},
                {data: 'harga'},
                {data: 'subtotal'},
            ]
        });

    });

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

</script>
@endpush
@extends('layouts.master')

@section('title')
    Daftar Transaksi Belum Dibayar
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Belum Dibayar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>statuss</th>
                        <th>Tanggal</th>
                        <th>Nama Member</th>
                        <th>Pajak</th>
                        <th>Total Bayar</th>
                        <th>Kasir</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('belum_bayar.detail')

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
                url: '{{ route('belum_bayar.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'statusaatini', visible:false},
                {data: 'tanggal'},
                {data: 'nama_member'},
                {data: 'pajak'},
                {data: 'total_bayar'},
                {data: 'kasir'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $(document).on('change', '.status', function () {
            let id = $(this).data('id');
            let status = $(this).val();

            $.post(`{{ url('/belombayar/status') }}/${id}`, {
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
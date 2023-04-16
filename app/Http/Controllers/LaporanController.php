<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Transaksi;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // untuk memberi tahu bahwa ini bukan laporan untuk outlet
        $outlet = null;

        $tanggalAwal = date('Y-m-d', mktime(0,0,0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != '' && $request->has('tanggal_akhir') && $request->tanggal_akhir != '') {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }
        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir', 'outlet'));
    }

    public function getData($awal, $akhir, $id)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        while(strtotime($awal) <= strtotime($akhir)){
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));
            
            if ($id == null) {
                $total_penjualan = Transaksi::where([
                    ['created_at', 'LIKE', "%$tanggal%"],
                    ['tanggal_bayar', '!=', '']
                ])->sum('total_bayar');
    
                if (auth()->user()->role == 'kasir' || auth()->user()->role == 'owner') {
                    $total_penjualan = Transaksi::where([
                        ['created_at', 'LIKE', "%$tanggal%"],
                        ['tanggal_bayar', '!=', ''],
                        ['id_outlet', '=', auth()->user()->id_outlet]
                    ])->sum('total_bayar');            
                }
            } else {
                $total_penjualan = Transaksi::where([
                    ['created_at', 'LIKE', "%$tanggal%"],
                    ['tanggal_bayar', '!=', ''],
                    ['id_outlet', '=', $id]
                ])->sum('total_bayar');
            }

            $pendapatan = $total_penjualan;
            $total_pendapatan += $pendapatan;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            $row['pendapatan'] = format_uang($pendapatan);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => 'Total Pendapatan',
            'pendapatan' => format_uang($total_pendapatan),
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal,$akhir, null);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPdf($awal, $akhir, $id)
    {
        // mengambil data dari tanggal awal - akhir
        $data = $this->getData($awal, $akhir, $id);
        // ekspor ke pdf
        $pdf = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('Laporan-pendapatan-' . date('Y-m-d-his') . '.pdf');
    }

    // laporan peroutlet
    public function indexOutlet()
    {
        return view('laporan.data_outlet');
    }

    public function dataOutlet()
    {
        $outlet = Outlet::orderBy('id_outlet', 'desc')->get();

        return datatables()
                ->of($outlet)
                ->addIndexColumn()
                ->addColumn('aksi', function($outlet){
                    return '
                    <div class="btn-group">
                        <a href="'.route("outlet.laporan", $outlet->id_outlet).'" class="btn btn-xs btn-info btn-flat"><i class="fa fa-file-pdf-o"></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function laporanOutlet(Request $request, $id)
    {
        $outlet = Outlet::find($id);
        $tanggalAwal = date('Y-m-d', mktime(0,0,0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != '' && $request->has('tanggal_akhir') && $request->tanggal_akhir != '') {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }
        return view('laporan.index_outlet', compact('tanggalAwal', 'tanggalAkhir', 'outlet'));
    }
}

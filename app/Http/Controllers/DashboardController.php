<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $outlet = outlet::count();
        $paket = Paket::count();
        $pelanggan = Pelanggan::count();
        $transaksi = Transaksi::count();

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();

        // menghitung hari dari tanggal awal sampai akhir
        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Transaksi::where([
                ['created_at', 'LIKE', "%$tanggal_awal%"],
                ['tanggal_bayar', '!=', '']
            ])->sum('total_bayar');

            $pendapatan = $total_penjualan;
            $data_pendapatan[] += $pendapatan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        if (auth()->user()->role == 'admin') {
            return view('admin.index', compact('outlet', 'paket', 'pelanggan', 'transaksi', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        } else if (auth()->user()->role == 'owner') {
            return view('owner.index');
        } else {
            return view('kasir.dashboard');
        }

    }
}

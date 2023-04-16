<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Setting;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;

class DetailTransaksiController extends Controller
{
    public function bayar($id)
    {
        $paket = Paket::orderBy('nama_paket')->get();
        if (auth()->user()->role == 'kasir') {
            $paket = Paket::where('id_outlet', '=', auth()->user()->id_outlet)->orderBy('nama_paket')->get();
        }
        $pelanggan = Pelanggan::orderBy('nama_member')->get();
        $diskon = Setting::first()->diskon ?? 0;

        $id_transaksi = $id;
        $transaksi = Transaksi::find($id_transaksi);
        $memberSelected = $transaksi->member ?? new Pelanggan();
        return view('detail_transaksi.index', compact('paket', 'pelanggan', 'id_transaksi', 'diskon','transaksi', 'memberSelected'));
        
   }
   
    public function index()
    {
        $paket = Paket::orderBy('nama_paket')->get();
        if (auth()->user()->role == 'kasir') {
            $paket = Paket::where('id_outlet', '=', auth()->user()->id_outlet)->orderBy('nama_paket')->get();
        }
        $pelanggan = Pelanggan::orderBy('nama_member')->get();
        $diskon = Setting::first()->diskon ?? 0;

        if ($id_transaksi = session('id_transaksi')) {
            $transaksi = Transaksi::find($id_transaksi);
            $memberSelected = $transaksi->member ?? new Pelanggan();
            return view('detail_transaksi.index', compact('paket', 'pelanggan', 'id_transaksi', 'diskon','transaksi', 'memberSelected'));
        } else {
            if(auth()->user()->role == 'admin'){
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id)
    {
        // untuk menampilkan paket
        $detail = DetailTransaksi::with('paket')
            ->where('id_transaksi', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            // memasukan data ke dalam array
            $row = array();
            $row['nama_paket']  = $item->paket['nama_paket'];
            $row['jenis']       = $item->paket['jenis'];
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_detail .'" value="'. $item->qty .'">';
            $row['harga']       = 'Rp. '. format_uang($item->paket['harga']);
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaksi.destroy', $item->id_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            // total semua harga dan item
            $total += $item->subtotal;
            $total_item += $item->jumlah;
        }
        // untuk memasukan data total dan total item
        $data[] = [
            'nama_paket' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'jenis'  => '',
            'jumlah'      => '',
            'harga'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'nama_paket', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $paket = Paket::where('id_paket', $request->id_paket)->first();
        if (! $paket) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new DetailTransaksi();
        $detail->id_transaksi = $request->id_transaksi;
        $detail->id_paket     = $request->id_paket;
        $detail->qty          = 1;
        $detail->keterangan   = 'sukses';
        $detail->subtotal     = $paket->harga;
        $detail->save();

        return response()->json('Data berhasil Disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = DetailTransaksi::find($id);
        $harga = Paket::find($detail->id_paket);
        $detail->qty = $request->jumlah;
        $detail->subtotal = $harga->harga * $request->jumlah;
        $detail->update();
    }
    public function show()
    {
        # code...
    }

    public function destroy($id)
    {
        $detail = DetailTransaksi::find($id);
        $detail->delete();

        return response(null,204);
    }
    
    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        //menghitung dari data yang dikirimkan
        $bayar  = $total - ($diskon / 100 * $total) + (11/100 * $total);
        $pajak  = 11/100 * $bayar;
        $kembali= ($diterima != 0) ? $diterima - $bayar : 0;
        $data   = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'pajak' => $pajak,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). 'Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). 'Rupiah')
        ];

        //mengembalikan data berbentuk response
        return response()->json($data);
    }
}

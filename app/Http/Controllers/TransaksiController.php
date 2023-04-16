<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Outlet;
use App\Models\Setting;

class TransaksiController extends Controller
{
    public function index()
    {
        return view('transaksi.index');
    }

    public function data()
    {
        // untuk menampilkan data
        $transaksi = Transaksi::where('status_bayar', '=', 'dibayar')->orderBy('id_transaksi', 'desc')->get();
       
        if (auth()->user()->role == 'kasir') {
            $transaksi = Transaksi::where([['status_bayar', '=', 'dibayar'], ['id_outlet', '=', auth()->user()->id_outlet]])->orderBy('id_transaksi', 'desc')->get();
        }
        
        return datatables()
            ->of($transaksi)
            ->addIndexColumn()
            ->addColumn('statusaatini', function ($transaksi) {
                return '
                    <div hidden>'.$transaksi->status.'</div>
                ';
            })
            ->addColumn('total_bayar', function ($transaksi) {
                return 'Rp. '. format_uang($transaksi->total_bayar);
            })
            ->addColumn('pajak', function ($transaksi) {
                return 'Rp. '. format_uang($transaksi->pajak);
            })
            ->addColumn('tanggal', function ($transaksi) {
                return tanggal_indonesia($transaksi->created_at, false);
            })
            ->addColumn('nama_member', function ($transaksi) {
                return $transaksi->member->nama_member ?? '';
            })
            ->addColumn('status', function ($transaksi) {
                if ($transaksi->status == 'baru') {
                    return '
                    <select name="status" id="status" class="form-control status" style="border-color: #dd4b39; color: #dd4b39;" data-id="'.$transaksi->id_transaksi.'" required="">
                        <option value="baru" class="text-danger">baru</option>
                        <option value="proses" class="text-info">proses</option>
                        <option value="selesai" class="text-primary">selesai</option>
                        <option value="diambil" class="text-success">diambil</option>
                    </select>
                    ';
                } 
                if ($transaksi->status == 'proses') {
                    return '
                    <select name="status" id="status" class="form-control status" style="border-color: #00acd6; color: #00acd6;" data-id="'.$transaksi->id_transaksi.'" required="">
                        <option value="proses" class="text-info">proses</option>
                        <option value="selesai" class="text-primary">selesai</option>
                        <option value="diambil" class="text-success">diambil</option>
                    </select>
                    ';
                } 
                if ($transaksi->status == 'selesai') {
                    return '
                    <select name="status" id="status" class="form-control status" style="border-color: #3c8dbc; color: #3c8dbc;" data-id="'.$transaksi->id_transaksi.'" required="">
                        <option value="selesai" class="text-primary">selesai</option>
                        <option value="diambil" class="text-success">diambil</option>
                    </select>
                    ';
                } 
                if ($transaksi->status == 'diambil') {
                    return '<span class="label label-success">'. $transaksi->status .'</span>';
                }
            })
            ->editColumn('kasir', function ($transaksi) {
                return $transaksi->user->name ?? '';
            })
            ->addColumn('aksi', function ($transaksi) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('penjualan.show', $transaksi->id_transaksi) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $transaksi->id_transaksi) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'nama_member', 'status', 'statusaatini'])
            ->make(true);

           
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->status = $request->status;
        $transaksi->save();

        return response(null,204);
    }

    public function show($id)
    {
        $detail = DetailTransaksi::with('paket')->where('id_transaksi', $id)->get();

        if (auth()->user()->role == 'kasir') {
            $detail = DetailTransaksi::with('paket')->where([['id_transaksi', '=', $id], ['id_outlet', '=', auth()->user()->id_outlet]])->get();
        }

        return datatables()
               ->of($detail)
               ->addIndexColumn()
               ->addColumn('nama_paket', function($detail) {
                    return $detail->paket->nama_paket;
               })
               ->addColumn('harga', function ($detail) {
                    return 'Rp. ' . format_uang($detail->paket->harga);
               })
               ->addColumn('subtotal', function ($detail) {
                    return 'Rp. ' . format_uang($detail->subtotal);
               })
               ->rawColumns(['harga'])
               ->make(true);
    }
    public function create()
    {
        $outlet = Outlet::where('id_outlet', '=', auth()->user()->id_outlet)->first();
        $kode_belakang = random_int(1,99);

        $transaksi = new Transaksi();
        $transaksi->kd_invoice = date('dmY').'LNC'.$kode_belakang;
        $transaksi->id_member = null;
        if (auth()->user()->id_outlet != null) {
            $transaksi->id_outlet = $outlet->id_outlet;
        } else {
            $transaksi->id_outlet = null;
        }
        $transaksi->batas_waktu = null;
        $transaksi->biaya_tambahan = 0;
        $transaksi->diskon = 0;
        $transaksi->pajak = 0;
        $transaksi->status = 'baru';
        $transaksi->status_bayar = 'belum_dibayar';
        $transaksi->id_user = auth()->id();
        $transaksi->save();

        session(['id_transaksi' => $transaksi->id_transaksi]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        // return $request;
        $transaksi = Transaksi::findOrfail($request->id_transaksi);
        $transaksi->id_member       = $request->id_member;
        $transaksi->batas_waktu     = $request->bataswaktu;
        $transaksi->tanggal_bayar   = $request->tanggalbayar;
        $transaksi->biaya_tambahan  = $request->biayatambahan;
        $transaksi->diskon          = $request->diskon;
        $transaksi->pajak           = $request->pajak;
        $transaksi->total_bayar     = $request->bayar;
        $transaksi->status          = 'baru';
        if ($request->tanggalbayar != null || $request->diterima != null) {
            if ($request->diterima != null) {
                $transaksi->tanggal_bayar = date('Y-m-d');
            }
            $transaksi->status_bayar    = 'dibayar';
        } else {
            $transaksi->status_bayar    = 'belum_dibayar';
        }
        
        $transaksi->update();

        return redirect()->route('transaksi.selesai');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);
        $detail    = DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)->get();
        foreach ($detail as $item) {
            $item->delete();
        }

        $transaksi->delete();

        return response(null,204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('transaksi.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();

        $transaksi = Transaksi::find(session('id_transaksi'));
        if (! $transaksi) {
            abort(404);
        }

        $detail = DetailTransaksi::with('paket')
                ->where('id_transaksi' , session('id_transaksi'))
                ->get();

        return view('transaksi.nota_kecil', compact('setting', 'transaksi', 'detail'));
    }

    // halaman belom bayar
    public function indexb()
    {
        // $transaksi = Transaksi::where('status_bayar', '=', 'belum_dibayar')->orderBy('id_transaksi', 'desc')->get();

        return view('belum_bayar.index');
    }

    public function datab()
    {
         // untuk menampilkan data
         $transaksi = Transaksi::where('status_bayar', '=', 'belum_dibayar')->orderBy('id_transaksi', 'desc')->get();
         
         if (auth()->user()->role == 'kasir') {
            $transaksi = Transaksi::where([['status_bayar', '=', 'belum_dibayar'], ['id_outlet', '=', auth()->user()->id_outlet]])->orderBy('id_transaksi', 'desc')->get();
        }
         
         foreach($transaksi as $waktu){
             if (date('Y-m-d') > $waktu->batas_waktu) {
                $transaksi->delete()->where(date('Y-m-d'), '>', $waktu->batas_waktu);
             }
         }
        
         return datatables()
             ->of($transaksi)
             ->addIndexColumn()
             ->addColumn('statusaatini', function ($transaksi) {
                 return '
                     <div hidden>'.$transaksi->status_bayar.'</div>
                 ';
             })
             ->addColumn('total_bayar', function ($transaksi) {
                 return 'Rp. '. format_uang($transaksi->total_bayar);
             })
             ->addColumn('pajak', function ($transaksi) {
                 return 'Rp. '. format_uang($transaksi->pajak);
             })
             ->addColumn('tanggal', function ($transaksi) {
                 return tanggal_indonesia($transaksi->created_at, false);
             })
             ->addColumn('nama_member', function ($transaksi) {
                 return $transaksi->member->nama_member ?? '';
             })
            //  ->addColumn('status', function ($transaksi) {
            //      if ($transaksi->status_bayar == 'belum_dibayar') {
            //          return '
            //          <select name="status" id="status" class="form-control status" style="border-color: #dd4b39; color: #dd4b39;" data-id="'.$transaksi->id_transaksi.'" required="">
            //              <option value="belum_dibayar" class="text-danger">Belum_dibayar</option>
            //              <option value="dibayar" class="text-info">Dibayar</option>
            //          </select>
            //          ';
            //      }  
            //      if ($transaksi->status_bayar == 'dibayar') {
            //         $this->destroy($transaksi->id_transaksi);
            //         //  return '<span class="label label-success">'. $transaksi->status .'</span>';
            //      }
            //  })
             ->editColumn('kasir', function ($transaksi) {
                 return $transaksi->user->name ?? '';
             })
             ->addColumn('aksi', function ($transaksi) {
                 return '
                 <div class="btn-group">
                     <button onclick="showDetail(`'. route('penjualan.show', $transaksi->id_transaksi) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                     <a href="'. route('belum_bayar.bayar', $transaksi->id_transaksi) .'`"><button class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button></a>
                     <button onclick="deleteData(`'. route('penjualan.destroy', $transaksi->id_transaksi) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                 </div>
                 ';
             })
             ->rawColumns(['aksi', 'nama_member', 'statusaatini'])
             ->make(true);
 
    }

    public function updateb(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->status_bayar = $request->status;
        $transaksi->tanggal_bayar = date('Y-m-d');
        $transaksi->save();

        return response(null,204);
    }
}

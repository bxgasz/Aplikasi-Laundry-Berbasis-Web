<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Setting;
use PDF;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pelanggan.index');
    }

    public function data()
    {
        $pelanggan = Pelanggan::orderBy('id_member', 'desc')->get();

        return datatables()
                ->of($pelanggan)
                ->addIndexColumn()
                ->addColumn('select_all', function ($produk) {
                    // untuk membuat select box
                    return '
                        <input type="checkbox" name="id_member[]" value="'.$produk->id_member.'">
                    ';
                })
                ->addColumn('aksi', function($pelanggan){
                    return '
                    <div class="btn-group">
                        <button type=button onclick="editForm(`'.route('pelanggan.update', $pelanggan->id_member).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                        <button type=button onclick="deleteData(`'.route('pelanggan.destroy', $pelanggan->id_member).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                })
                ->rawColumns(['aksi', 'select_all'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pelanggan = Pelanggan::create($request->all());

        return response()->json('data berhasil disimpan',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);

        return response()->json($pelanggan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);
        $pelanggan->update($request->all());

        return response()->json('data berhasil disimpan',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);
        $pelanggan->delete();

        return response()->json(null,204);
    }

    public function cetakMember(Request $request)
    {
        $datamember = collect(array());

        foreach ($request->id_member as $id) {
            $pelanggan = Pelanggan::find($id);
            $datamember[] = $pelanggan;
        };

        $datamember = $datamember->chunk(2);
        $setting = Setting::first();

        $no =1;
        $pdf = PDF::loadView('pelanggan.cetak', compact('datamember','no','setting'));
        $pdf->setPaper(array(0,0,566.93,850.39,), 'potrait');
        return $pdf->stream('pelanggan.pdf');
    }
}

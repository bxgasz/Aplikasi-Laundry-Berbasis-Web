<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Paket;

class PaketController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outlet = Outlet::all()->pluck('nama_outlet', 'id_outlet');

        return view('paket.index', compact('outlet'));
    }

    public function data()
    {
        $paket = Paket::leftJoin('outlet', 'outlet.id_outlet', 'paket.id_outlet')
                 ->select('paket.*', 'nama_outlet')
                 ->orderBy('id_paket', 'asc')
                 ->get();

        return datatables()
                ->of($paket)
                ->addIndexColumn()
                ->addColumn('harga', function ($paket) {
                    return format_uang($paket->harga);
                })
                ->addColumn('aksi', function($paket){
                    return '
                    <div class="btn-group">
                        <button type=button onclick="editForm(`'.route('paket.update', $paket->id_paket).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                        <button type=button onclick="deleteData(`'.route('paket.destroy', $paket->id_paket).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
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
        $paket = Paket::create($request->all());

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
        $paket = Paket::find($id);

        return response()->json($paket);
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
        $paket = Paket::find($id);
        $paket->update($request->all());

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
        $paket = Paket::find($id);
        $paket->delete();

        return response()->json(null,204);
    }
}

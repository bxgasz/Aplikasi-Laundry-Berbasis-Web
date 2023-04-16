<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('outlet.index');
    }

    public function data()
    {
        $outlet = Outlet::orderBy('id_outlet', 'desc')->get();

        return datatables()
                ->of($outlet)
                ->addIndexColumn()
                ->addColumn('aksi', function($outlet){
                    return '
                    <div class="btn-group">
                        <button type=button onclick="editForm(`'.route('outlet.update', $outlet->id_outlet).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                        <button type=button onclick="deleteData(`'.route('outlet.destroy', $outlet->id_outlet).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $outlet = new Outlet();
        $outlet->nama_outlet = $request->name;
        $outlet->alamat_outlet = $request->alamat;
        $outlet->telp_outlet = $request->kontak;
        $outlet->save();

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
        $outlet = Outlet::find($id);

        return response()->json($outlet);
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
        $outlet = Outlet::find($id);
        $outlet->nama_outlet = $request->name;
        $outlet->alamat_outlet = $request->alamat;
        $outlet->telp_outlet = $request->kontak;
        $outlet->update();

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
        $outlet = Outlet::find($id);
        $outlet->delete();

        return response()->json(null,204);
    }
}

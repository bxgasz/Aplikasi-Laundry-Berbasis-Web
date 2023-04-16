<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outlet = Outlet::all()->pluck('nama_outlet', 'id_outlet');
        return view('user.index', compact('outlet'));
    }

    public function data ()
    {
        $user = User::leftJoin('outlet', 'outlet.id_outlet', 'users.id_outlet')->select('users.*', 'nama_outlet')->isNotAdmin()->orderBy('id','desc')->get();

        return datatables()
            ->of($user)
            ->addIndexColumn()
            ->addColumn('aksi',function($user){
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'.route('user.update', $user->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteData(`'.route('user.destroy', $user->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
        ;
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
        //untuk menambahkan data baru yang memiliki level 2
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->id_outlet = $request->id_outlet;
        $user->foto = '/img/profil.png';
        $user->save();

        return response()->json('Data berhasil disimpan',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //menampilkan data
        $user = User::find($id);

        return response()->json($user);
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
        //update data user
        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->has('password') && $request->password != "") {
            $user->password = bcrypt($request->password);
        }
        $user->update();

        return response()->json('Data berhasil disimpan',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //menghapus data
        $user = User::find($id);
        $user->delete();

        return response(null, 204);
    }

    public function profil()
    {
        //menampilkan profil sesuai auth user yang login
        $profil = auth()->user();
        return view('user.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        //update profil
        $user = auth()->user();

        $user->name = $request->name;
        $user->username = $request->username;
        if($request->has('password') && $request->password != ''){
            if(Hash::check($request->old_password, $user->password)){
                if ($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                } else {
                    return response()->json('Konfirmasi Password tidak sesuai', 422);
                }
            } else {
                return response()->json('Password lama tidak sesuai', 422);
            }
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'profil-' . date('Y-m-dHis') .'.'. $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            $user->foto = "/img/$nama";
        }

        $user->update();

        return response()->json($user, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PegawaiAjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Pegawai::orderBy('nama', 'asc');
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data){
            return view('pegawai.tombol')->with('data', $data);
        })
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
        ],[
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak sesuai',
        ]);

        if($validasi->fails()){
            return response()->json(['errors' => $validasi->errors()]);
        }else{
            $data = [
                'nama' => $request->nama,
                'email' => $request->email,
            ];
            Pegawai::create($data);
            return response()->json(['success' => 'Data berhasil ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Pegawai::find($id);
        return response()->json(['result' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validasi = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
        ],[
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak sesuai',
        ]);

        if($validasi->fails()){
            return response()->json(['errors' => $validasi->errors()]);
        }else{
            $data = [
                'nama' => $request->nama,
                'email' => $request->email,
            ];
            Pegawai::find($id)->update($data);
            return response()->json(['success' => 'Data berhasil diubah']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pegawai::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus']);
    }
}

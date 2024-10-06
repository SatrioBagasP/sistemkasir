<?php

namespace App\Http\Controllers;

use App\Models\MenuModel;
use App\Models\NotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class NotaController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(NotaModel::latest()->get())->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = encrypt($row->id);
                    $route = route('home.printnota', ['id' => $row->id]);
                    return '<a href="' . $route . '"  data-id="' . $id . '" class="btn btn-primary" id="edit">Detail</a>';
                })
                ->rawColumns(['action', 'cover'])->make(true);
        }
        return view('data.nota');
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'nama' => 'required|max:255',
            'harga_pokok' => 'required|numeric',
            'deskripsi' => 'required',
            'image' => 'required|image|file|max:2048'
        ]);
        // dd($request->all());
        // Store Gambar
        if ($request->file('image')) {
            // $validated['image'] = $request->file('image')->store('menu-images');
            $imagePath = $request->file('image')->store('menu-images', 'public'); // Store in storage/app/public/images
        }

        // Store Data
        $menu = new MenuModel();
        $menu->nama = $validated['nama'];
        $menu->harga_pokok = $validated['harga_pokok'];
        $menu->deskripsi = $validated['deskripsi'];
        $menu->gambar =  $imagePath;
        $menu->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function delete($id)
    {
        $nota = NotaModel::find(decrypt($id));
        if ($nota) {
            $nota->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function show($id)
    {
        $menu = MenuModel::find(decrypt($id));
        return response()->json([
            'nama' => $menu->nama,
            'deskripsi' => $menu->deskripsi,
            'harga_pokok' => $menu->harga_pokok,
            'gambar' => $menu->gambar,
        ]);
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'harga_pokok' => 'required|numeric',
            'deskripsi' => 'required',
        ]);
        $menu = MenuModel::find(decrypt($id));
        if ($request->file('image')) {
            Storage::delete('public/' . $menu->gambar);
            $imagePath = $request->file('image')->store('menu-images', 'public');
            $menu->gambar =  $imagePath;
        }


        $menu->nama = $validated['nama'];
        $menu->harga_pokok = $validated['harga_pokok'];
        $menu->deskripsi = $validated['deskripsi'];
        $menu->update();
        return response()->json([
            'msg' => 'Success',
        ]);
    }
}

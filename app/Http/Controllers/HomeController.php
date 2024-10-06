<?php

namespace App\Http\Controllers;

use App\Models\DetailNotaModel;
use App\Models\MenuModel;
use App\Models\NotaModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
    public function kasir()
    {
        $menu = MenuModel::all();
        return view('kasir.index', compact('menu'));
    }
    public function data()
    {
        return view('data.index');
    }
    public function input(Request $request)
    {
        $menudata = $request->menu_data;
        if ($menudata == null) {
            return response()->json([
                'status' => 'error',
                'error' => 'Pilih Menu!'
            ], 400);
        }
        $nota = new NotaModel; // Buat nota
        $nota->total_harga = 0;
        $nota->save();

        foreach ($menudata as $menu) {
            $jumlah = $menu['jumlah']; // Ambil jumlah yang dipesan
            $idMenu = $menu['id_menu']; // Ambil id_menu

            for ($i = 0; $i < $jumlah; $i++) {
                // Memasukan ke array untuk batch insert agar tidak query 1 1
                $detailNotaData[] = [
                    'nota_id' => $nota->id, // Mengaitkan dengan nota yang baru dibuat
                    'menu_id' => $idMenu, // Menyimpan id_menu
                    'harga_tertera' => $menu['harga'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        // Batch insert
        DetailNotaModel::insert($detailNotaData);

        // masukan total harga ke nota
        $nota->total_harga = DetailNotaModel::where('nota_id', $nota->id)
            ->sum('harga_tertera');
        $nota->save();



        return response()->json([
            'status' => 'success',
            'id_nota' => $nota->id,
            'message' => 'Data berhasil disimpan'
        ]);
    }
    public function printNota($id)
    {
        $nota = NotaModel::find($id);
        $detailNota = DetailNotaModel::where('nota_id', $id)->get();


        $aggregatedData = $detailNota->groupBy('menu_id')->map(function ($items) {
            return [
                'jumlah' => $items->count(), // Total jumlah per barang
                'harga' => $items->first()->harga_tertera, // Ambil harga dari barang pertama
                'subtotal' => $items->count() * $items->first()->harga_tertera,
                'nama_barang' => $items->first()->menu->nama // Ambil nama barang dari barang pertama
            ];
        });

        // dd($aggregatedData);

        return view('kasir.print', compact('nota', 'aggregatedData'));


        // return view('kasir.print'); // Untuk menampilkan view print nota
    }
}

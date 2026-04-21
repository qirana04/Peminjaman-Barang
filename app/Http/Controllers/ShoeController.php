<?php
namespace App\Http\Controllers;

use App\Models\Shoe;
use Illuminate\Http\Request;


class ShoeController extends Controller {
    public function index() {
        $shoes = Shoe::all();
        return view('shoes.index', compact('shoes'));
    }

    public function store(Request $request) {
        Shoe::create($request->all());
        return redirect()->back()->with('success', 'Sepatu berhasil ditambah');
    }

    public function update(Request $request, $id) {
        $shoe = Shoe::find($id);
        $shoe->update($request->all());
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy($id) {
        Shoe::destroy($id);
        return redirect()->back()->with('success', 'Sepatu berhasil dihapus');
    }
}

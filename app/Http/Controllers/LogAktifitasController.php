<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktifitas;
use App\Models\User;

class LogAktifitasController extends Controller
{
    public function index()
    {
        $items = LogAktifitas::with('user')->orderBy('id_log','desc')->paginate(15);
        return view('log_aktivitas.index', compact('items'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('log_aktivitas.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|exists:tb_user,id',
            'aktivitas' => 'required|string|max:255',
            'waktu_aktivitas' => 'required|date_format:Y-m-d H:i:s',
        ]);

        LogAktifitas::create($data);
        return redirect()->route('log-aktivitas.index')->with('success','Log aktivitas berhasil dibuat');
    }

    public function show($id)
    {
        $item = LogAktifitas::with('user')->findOrFail($id);
        return view('log_aktivitas.show', compact('item'));
    }

    public function edit($id)
    {
        $item = LogAktifitas::findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('log_aktivitas.edit', compact('item', 'users'));
    }

    public function update(Request $request, $id)
    {
        $item = LogAktifitas::findOrFail($id);

        $data = $request->validate([
            'id_user' => 'required|exists:tb_user,id',
            'aktivitas' => 'required|string|max:255',
            'waktu_aktivitas' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $item->update($data);
        return redirect()->route('log-aktivitas.index')->with('success','Log aktivitas berhasil diupdate');
    }

    public function destroy($id)
    {
        LogAktifitas::destroy($id);
        return redirect()->route('log-aktivitas.index')->with('success','Log aktivitas berhasil dihapus');
    }
}


<?php

namespace App\Modules\Asesor\Controllers;
use App\Http\Controllers\MainController;
use App\Models\Asesor;
use App\DataTables\AsesorDataTable;
use Illuminate\Http\Request;

class AsesorController extends MainController
{

    public function index(Request $request, AsesorDataTable $dataTable)
    { 
        // Jika ini permintaan data tabel (ada parameter draw), kembalikan JSON saja
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        // Jika buka halaman biasa / lewat menu AJAX, kembalikan tampilan lengkap
        return $dataTable->render('Asesor::index');
    } 
    
    public function create()
    {
        return view('Asesor::create');
    }

    public function store(Request $request)
    {
        // Silakan sesuaikan aturan validasi di bawah ini
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Asesor::create($validated);

        return redirect()->route('asesors.index')
            ->with('success', 'Data Asesor berhasil ditambahkan.');
    }

    public function edit(Asesor $asesor)
    {
        return view('Asesor::edit', compact('asesor'));
    }

    public function update(Request $request, Asesor $asesor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $asesor->update($validated);

        return redirect()->route('asesors.index')
            ->with('success', 'Data Asesor berhasil diperbarui.');
    }

    public function destroy(Asesor $asesor)
    {
        $asesor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Asesor berhasil dihapus.'
        ]);
    }
}

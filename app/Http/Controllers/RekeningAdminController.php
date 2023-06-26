<?php

namespace App\Http\Controllers;

use App\Models\RekeningAdmin;
use App\Http\Requests\StoreRekeningAdminRequest;
use App\Http\Requests\UpdateRekeningAdminRequest;
use App\Models\Bank;

class RekeningAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekening_admin = RekeningAdmin::all();
        return response()->json([
            'data' => $rekening_admin
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRekeningAdminRequest $request)
    {
        $request->validate([
            'nama_admin' => 'required',
            'nomor_rekening' => 'required',
            'nama_bank' => 'required',
        ]);

        // Cek apakah bank sudah ada dalam database
        $bank = Bank::where('name_bank', $request->nama_bank)->first();

        if (!$bank) {
            return response()->json([
                'error' => 'Bank tidak ditemukan.',
            ], 422);
        }

        $id_bank = $bank->id;

        $rekeningAdmin = new RekeningAdmin;
        $rekeningAdmin->id_bank = $id_bank;
        $rekeningAdmin->nama_admin = $request->nama_admin;
        $rekeningAdmin->nomor_rekening = $request->nomor_rekening;
        $rekeningAdmin->nama_bank = $bank->name_bank;
        $rekeningAdmin->save();

        return response()->json([
            'message' => 'Rekening admin berhasil ditambahkan.',
            'data' => $rekeningAdmin,
        ]);
    }
}

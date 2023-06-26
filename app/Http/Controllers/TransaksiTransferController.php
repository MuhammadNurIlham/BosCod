<?php

namespace App\Http\Controllers;

use App\Models\TransaksiTransfer;
use App\Http\Requests\StoreTransaksiTransferRequest;
use App\Http\Requests\UpdateTransaksiTransferRequest;
use App\Models\Bank;
use App\Models\RekeningAdmin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransaksiTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi_transfer = TransaksiTransfer::all();
        return response()->json([
            'data' => $transaksi_transfer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransaksiTransferRequest $request)
    {
        $request->validate([
            'nilai_transfer' => 'required',
            'bank_tujuan' => 'required',
            'rekening_tujuan' => 'required',
            'atas_nama_tujuan' => 'required',
            'bank_pengirim' => 'required',
        ]);

        // Generate id_transaksi_transfer
        $id_transaksi = 'TF' . Carbon::now()->format('ymd') . mt_rand(10000, 99999);

        // Generate for kode unik
        $kode_unik = mt_rand(100, 999);

        // Hitung total transfer
        $total_transfer = $request->nilai_transfer + $kode_unik;

        // Rekening Perantara
        $rekening_perantara = '12700283733';

        // Hitung berlaku hingga
        $berlaku_hingga = Carbon::now()->addDays(3)->format('Y-m-d H:i:s');

        // Mendapatkan ID pengguna yang sedang login
        $id_pengirim = auth()->user()->id;

       try {
            // Cek apakah bank tujuan sudah ada dalam database
            $bankTujuan = Bank::where('name_bank', $request->bank_tujuan)->firstOrFail();

            // Cek apakah rekening tujuan sudah ada dalam database rekening_admins
            $rekeningTujuan = RekeningAdmin::where('nomor_rekening', $request->rekening_tujuan)->firstOrFail();

            // Cek apakah atas_nama_tujuan sudah ada dalam database user atau rekening_admins
            $penerima = User::where('name', $request->atas_nama_tujuan)->first();
            if (!$penerima) {
                $penerima = RekeningAdmin::where('nama_admin', $request->atas_nama_tujuan)->firstOrFail();
        }

            // Cek apakah atas_nama_tujuan sudah ada dalam database
            // $penerima = User::where('name', $request->atas_nama_tujuan)->firstOrFail();

            $id_penerima = $penerima->id;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Failed',
                'message' => 'Data Bank: ' . $request->bank_tujuan . ' atau Rekening Tujuan: ' . $request->rekening_tujuan . ' atau Atas Nama: ' . $request->atas_nama_tujuan . ' tidak ditemukan',
            ], 404);
        }

        $transfer = new TransaksiTransfer;
        $transfer->id_pengirim = $id_pengirim;
        $transfer->id_penerima = $id_penerima;
        $transfer->id_transaksi = $id_transaksi;
        $transfer->nilai_transfer = $request->nilai_transfer;
        $transfer->bank_tujuan = $bankTujuan->name_bank;
        $transfer->rekening_tujuan = $rekeningTujuan->nomor_rekening;
        $transfer->atas_nama_tujuan = $request->atas_nama_tujuan;
        $transfer->bank_pengirim = $request->bank_pengirim;
        $transfer->kode_unik = $kode_unik;
        $transfer->biaya_admin = 0;
        $transfer->total_transfer = $total_transfer;
        $transfer->bank_perantara = $request->bank_pengirim;
        $transfer->rekening_perantara = $rekening_perantara;
        $transfer->berlaku_hingga = $berlaku_hingga;
        $transfer->save();

        return response()->json([
            'id_transaksi' => $id_transaksi,
            'nilai_transfer' => $request->nilai_transfer,
            'kode_unik' => $kode_unik,
            'biaya_admin' => $transfer->biaya_admin,
            'total_transfer' => $transfer->total_transfer,
            'bank_perantara' => $transfer->bank_perantara,
            'rekening_perantara' => $transfer->rekening_perantara,
            'berlaku_hingga' => $transfer->berlaku_hingga,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransaksiTransfer $transaksiTransfer, $id)
    {
        $transfer = TransaksiTransfer::findOrFail($id);
        return response()->json([
            'data' => $transfer
        ]);
    }
}

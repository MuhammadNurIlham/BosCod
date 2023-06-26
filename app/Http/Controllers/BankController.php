<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bank = Bank::all();
        return response()->json(['data' => $bank]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankRequest $request)
    {
        $request->validate([
            'name_bank' => 'required',
        ]);

        $bank = new Bank;
        $bank->name_bank = $request->name_bank;
        $bank->save();

        return response()->json([
            'data' => $bank,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bank = Bank::findOrFail($id);
        return response()->json([
            'data' => $bank,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankRequest $request, Bank $bank, $id)
    {
        $request->validate([
            'name_bank' => 'required',
        ]);

        $bank = Bank::findOrFail($id);
        $bank->update($request->all());

        return response()->json([
            'data-Update' => $bank,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank, $id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return response()->json([
            'data-Delete' => $bank,
            'message' => 'Delete Bank Successfully'
        ]);
    }
}

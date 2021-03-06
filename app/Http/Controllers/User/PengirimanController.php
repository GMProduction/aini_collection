<?php

namespace App\Http\Controllers\User;

use App\Helper\CustomController;
use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends CustomController
{
    //
    public function index()
    {
        $pesanan = Pesanan::where('id_user', '=', Auth::id())->where('status_pesanan', '=', 3)->orWhere('status_pesanan', '=', 5)->orWhere('status_pesanan', '=', 6)->get();
        if ($this->request->isMethod('POST')) {
            $pesanan->find($this->request->get('id'));
            $pesanan[0]->update(['status_pesanan' => 4]);

            return response()->json('berhasil');
        }

        return view('user.pengiriman')->with(['data' => $pesanan]);
    }

    public function retur()
    {
        $pesanan = Pesanan::find($this->request->get('id'));
        $pesanan->update(
            [
                'status_pesanan' => 5,
                'alasan_retur'   => $this->request->get('alasan_retur'),
            ]
        );

        return response()->json('berhasil');
    }
}

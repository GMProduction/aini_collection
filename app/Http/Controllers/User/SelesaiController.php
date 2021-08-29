<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelesaiController extends Controller
{
    //

    public function index(){
        $pesanan = Pesanan::with('getKeranjang.rating')->where([['id_user', '=', Auth::id()],['status_pesanan','=',4]])->get();
        return view('user.selesai')->with(['data' => $pesanan]);
    }

    function rating(){
       if (\request('id')){
           $rating = Rating::find(\request('id'));
           $rating->update([
               'id_produk' => \request('id_produk'),
               'id_user' => Auth::id(),
               'ulasan' => \request('ulasan'),
               'rating' => \request('rating'),
               'id_keranjang' => \request('id_keranjang')
           ]);
       }else{
           Rating::create([
               'id_produk' => \request('id_produk'),
               'id_user' => Auth::id(),
               'ulasan' => \request('ulasan'),
               'rating' => \request('rating'),
               'id_keranjang' => \request('id_keranjang')

           ]);
       }

        return response()->json('berhasil');
    }
}

@extends('user.dashboard')

@section('contentUser')



    <section class="container">
        @forelse($data as $d)
            <div class="row item-box mb-4">
                <div class="col-12">
                    <div class="col">
                        <p class="title mb-0">Nomor Pesanan : {{$d->no_pemesanan}}</p>
                        <p class="qty">{{date('d F Y', strtotime($d->tanggal_pesanan))}}</p>
                        <hr>
                        <div class="row">
                            <div class="row col-6">
                                <div class="col">
                                    <p class="mb-0 fw-bold">Alamat Pengiriman</p>
                                    <p class="mb-0">{{auth()->user()->nama}}</p>
                                    <p>{{auth()->user()->no_hp}}</p>
                                </div>
                                <div class="col pt-4"><p class="keterangan">{{$d->getExpedisi->nama_kota}} - {{$d->getExpedisi->nama_propinsi}}</p>
                                    <p class="keterangan">{{$d->alamat_pengiriman}}</p></div>
                            </div>
                            <div class="row col-6">
                                <div class="col">
                                    <p class="mb-0 fw-bold">Pembayaran</p>
                                    <p class="mb-0">{{$d->getBank->nama_bank}} an. {{$d->getBank->holder_bank}}</p>
                                    <p class="mb-0">Nomor Rekening : {{$d->getBank->norek}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                </div>

                <div class="col-12">
                    <table class="table border-0" id="tabelKeranjang">
                        <thead>
                        <tr>
                            <td colspan="2" class="fw-bold">Produk Dipesan</td>
                            <td class="text-center fw-bold">Harga Satuan</td>
                            <td class="text-center fw-bold">Jumlah</td>
                            <td class="text-end fw-bold">Subtotal Produk</td>
                            <td class="text-center fw-bold">Aksi</td>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($d->getKeranjang as $k)
                            <tr class="item-box border-0 mt-1">
                                <td class="border-0"><img src="{{count($k->getProduk->getImage) > 0 ? $k->getProduk->getImage[0]->url_foto : asset('/static-image/noimage.jpg')}}"/></td>
                                <td class="border-0"><p class="title">{{$k->getProduk->nama_produk}}</p>
                                    <p class="keterangan mb-0">{{$k->keterangan}}</p></td>
                                <td class="border-0 text-center"><p class="qty">Rp. {{number_format($k->getProduk->harga,0)}}</p></td>
                                <td class="border-0 text-center"><p class="qty">{{$k->qty}}</p></td>
                                <td class="border-0 text-end"><p class="totalHarga mb-3" style="font-size: 1rem; color: black">Rp. {{number_format($k->total_harga,0)}}</p></td>
                                <td class="border-0 text-end"><a data-id="{{$k->getProduk->id}}" data-id-rating="{{$k->rating ? $k->rating->id : ''}}" data-ulasan="{{$k->rating ? $k->rating->ulasan : ''}}" data-rating="{{$k->rating ? $k->rating->rating : ''}}" data-keranjang="{{$k->id}}" class="btn btn-warning btn-sm" id="showRating">Beri Rating</a></td>
                            </tr>
                        @empty
                            <h5 class="text-center">Tidak ada data pembayaran</h5>
                        @endforelse
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-end">
                        <table class=" mt-3" width="30%">
                            <tr style="border: none">
                                <td class="border-0"><p>Total Harga</p></td>
                                <td class="border-0"><p>:</p></td>
                                <td class="text-end border-0"><p>Rp. {{number_format($d->total_harga - $d->biaya_pengiriman,0)}}</p></td>
                            </tr>
                            <tr>
                                <td class="border-bottom"><p>Ongkir</p></td>
                                <td class="border-bottom"><p>:</p></td>
                                <td class="text-end border-bottom"><p>Rp. {{number_format($d->biaya_pengiriman,0)}}</p></td>
                            </tr>
                            <tr>
                                <td class="border-0"><p>Grand Total</p></td>
                                <td class="border-0"><p>:</p></td>
                                <td class="totalHarga text-end border-0 fw-bold"><p>Rp. {{number_format($d->total_harga, 0)}}</p></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <h4 class="text-center">Tidak ada data pesanan</h4>
        @endforelse

        <div class="modal fade" id="modalRating" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Beri Rating</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form" onsubmit="return saveRating()">
                            @csrf
                            <input id="id_produk" name="id_produk" hidden>
                            <div class="mb-3 d-flex justify-content-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <a style="cursor: pointer" id="star{{$i}}" data-id="{{$i}}" class="star"><i class='bx bx-star' style="font-size: 3rem !important; color: orangered"></i></a>
                                @endfor
                            </div>
                            <div class="mb-3">
                                <label for="ulasan" class="form-label">Beri Ulasan</label>
                                <textarea class="form-control" id="ulasan" name="ulasan"></textarea>
                            </div>
                            <div class="mb-4"></div>
                            <button type="submit" class="btn bt-primary">Save</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>


@endsection

@section('scriptUser')

    <script>
        var rating = 1, idKeranjang, idRating;
        $(document).ready(function () {

            $("#selesai").addClass("active");

        });

        $(document).on('click', '#showRating', function () {
            $('#modalRating #id_produk').val($(this).data('id'))
            $('#modalRating #ulasan').val('')
            rating = 1;
            idRating = $(this).data('id-rating')

            if (idRating){
                rating = parseInt($(this).data('rating'))
                $('#modalRating #ulasan').val($(this).data('ulasan'))

            }
            console.log(rating)
            $('.star i').removeClass('bxs-star').addClass('bx-star');
            $('#star1 i').removeClass('bx-star').addClass('bxs-star');
            for (var i = 1; i <= rating; i++) {
                $('#star' + i + ' i').removeClass('bx-star').addClass('bxs-star');
            }
            idKeranjang = $(this).data('keranjang')

            $('#modalRating').modal('show')
        })

        $(document).on('click', '.star', function () {
            var id = $(this).data('id');
            console.log(id)
            rating = parseInt(id);
            if ($('#star' + id + ' i').hasClass('bx-star')) {
                for (var i = 1; i <= id; i++) {
                    $('#star' + i + ' i').removeClass('bx-star').addClass('bxs-star');
                }
            } else {
                for (var i = parseInt(id + 1); i <= 5; i++) {
                    $('#star' + i + ' i').removeClass('bxs-star').addClass('bx-star');
                }
            }
        })

        function saveRating() {
            var formData = {
                '_token': '{{csrf_token()}}',
                'id': idRating,
                'id_produk': $('#modalRating #id_produk').val(),
                'ulasan': $('#modalRating #ulasan').val(),
                'rating': rating,
                'id_keranjang': idKeranjang
            }
            console.log(formData)
            saveDataObject('Simpan Ulasan', formData, '/user/selesai/beri-rating')
            return false;
        }
        function after() {

        }
    </script>

@endsection

<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Obat;
use App\Support\DistanceCalculator;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $cartItems = CartItem::with('obat')
            ->where('user_id', $user->id)
            ->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $item->obat->harga);

        $jarakKm = null;
        $ongkir = null;
        $bisaDiantar = false;
        $alamatLengkap = $user->alamat && $user->latitude && $user->longitude;

        if ($alamatLengkap) {
            $rute = DistanceCalculator::route(
                config('apotek.latitude'),
                config('apotek.longitude'),
                $user->latitude,
                $user->longitude
            );

            $jarakKm = $rute['jarak_km'];
            $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);

            // Area layanan sekarang divalidasi berdasarkan kecamatan (bukan
            // radius jarak). Alamat baru sudah dicegah tersimpan kalau di
            // luar area lewat AlamatController, tapi dicek ulang di sini
            // untuk berjaga-jaga terhadap alamat lama yang tersimpan
            // sebelum validasi ini ada.
            $bisaDiantar = DistanceCalculator::areaDilayaniUntukUser($user->kecamatan, $user->alamat);
        }

        // Minimal total belanja dihitung dari SUBTOTAL produk saja (tidak
        // termasuk ongkir), supaya konsisten walau ongkir belum bisa
        // dihitung (mis. alamat belum diisi).
        $minimumBelanja = config('apotek.minimum_belanja', 0);
        $memenuhiMinimum = $subtotal >= $minimumBelanja;

        $summary = [
            'item_count'       => $cartItems->sum('quantity'),
            'subtotal'         => $subtotal,
            'jarak_km'         => $jarakKm,
            'ongkir'           => $ongkir,
            'bisa_diantar'     => $bisaDiantar,
            'total'            => $subtotal + ($ongkir ?? 0),
            'minimum_belanja'  => $minimumBelanja,
            'memenuhi_minimum' => $memenuhiMinimum,
        ];

        return view('customer.cart', compact('cartItems', 'summary', 'user', 'alamatLengkap'));
    }

    public function store(Request $request, Obat $obat)
    {
        $user = $request->user();

        if ($obat->stok <= 0) {
            return response()->json(['message' => 'Stok obat ini sudah habis.'], 422);
        }

        // Obat keras / obat yang butuh resep TIDAK diblokir dari keranjang.
        // Verifikasi dokumen (resep/KTP) dilakukan nanti saat checkout,
        // lalu diverifikasi apoteker sebelum pesanan diproses.
        if ($obat->kategori === 'Kontrasepsi') {
            $age = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->age : null;
            if ($age === null || $age < 21) {
                return response()->json(['message' => 'Produk ini khusus usia 21 tahun ke atas.'], 403);
            }
        }

        $cartItem = CartItem::firstOrNew([
            'user_id' => $user->id,
            'obat_id' => $obat->id,
        ]);

        $newQty = ($cartItem->exists ? $cartItem->quantity : 0) + 1;

        if ($newQty > $obat->stok) {
            return response()->json(['message' => 'Jumlah melebihi stok yang tersedia.'], 422);
        }

        $cartItem->quantity = $newQty;
        $cartItem->save();

        return response()->json([
            'message'    => 'Produk ditambahkan ke keranjang.',
            'cart_count' => CartItem::countForUser($user->id),
            'redirect'   => route('cart.index'),
        ]);
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $this->authorizeOwnership($cartItem, $request);

        $request->validate(['action' => 'required|in:increase,decrease']);

        $newQty = $cartItem->quantity + ($request->action === 'increase' ? 1 : -1);

        if ($newQty < 1) {
            $cartItem->delete();
            return response()->json(['deleted' => true, 'cart_count' => CartItem::countForUser($request->user()->id)]);
        }

        if ($newQty > $cartItem->obat->stok) {
            return response()->json(['message' => 'Jumlah melebihi stok yang tersedia.'], 422);
        }

        $cartItem->update(['quantity' => $newQty]);

        return response()->json([
            'quantity'   => $cartItem->quantity,
            'subtotal'   => $cartItem->quantity * $cartItem->obat->harga,
            'cart_count' => CartItem::countForUser($request->user()->id),
        ]);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $this->authorizeOwnership($cartItem, $request);

        $cartItem->delete();

        return response()->json(['cart_count' => CartItem::countForUser($request->user()->id)]);
    }

    private function authorizeOwnership(CartItem $cartItem, Request $request): void
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);
    }
}
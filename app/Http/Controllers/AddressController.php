<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Tampilkan daftar alamat
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('profile.addresses', compact('addresses'));
    }

    // Simpan alamat baru
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $user = Auth::user();

        // Jika ini alamat pertama, jadikan default
        $isFirstAddress = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'label' => $request->label,
            'recipient' => $request->recipient,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_default' => $isFirstAddress,
        ]);

        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    // Update alamat
    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        
        $address->update([
            'label' => $request->label,
            'recipient' => $request->recipient,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Alamat berhasil diperbarui!');
    }

    // Hapus alamat
    public function destroy($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        
        $wasDefault = $address->is_default;
        $address->delete();

        // Jika yang dihapus adalah default, set alamat pertama jadi default
        if ($wasDefault) {
            $firstAddress = Auth::user()->addresses()->first();
            if ($firstAddress) {
                $firstAddress->update(['is_default' => true]);
            }
        }

        return redirect()->back()->with('success', 'Alamat berhasil dihapus!');
    }

    // Set sebagai alamat default
    public function setDefault($id)
    {
        $user = Auth::user();
        
        // Reset semua alamat jadi non-default
        $user->addresses()->update(['is_default' => false]);
        
        // Set alamat ini jadi default
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);
        $address->update(['is_default' => true]);

        return redirect()->back()->with('success', 'Alamat utama berhasil diubah!');
    }
}

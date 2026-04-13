<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // Halaman Data Customer (tabel)
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    // Halaman form tambah customer (metode 1: blob)
    public function createBlob()
    {
        return view('customer.create_blob');
    }

    // Proses simpan customer (metode 1: blob)
    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email' => 'nullable|email',
            'foto' => 'required|string',
        ]);

        // Ambil base64 langsung (jangan di-decode)
        $fotoBase64 = $request->foto; // string base64

        Customer::create([
            'nama_customer' => $request->nama_customer,
            'email' => $request->email,
            'foto' => $fotoBase64, // simpan sebagai teks base64
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan');
    }

    // Menampilkan foto blob
    public function showFoto($id)
    {
        $customer = Customer::findOrFail($id);
        
        if (!$customer->foto) {
            abort(404);
        }
        
        return response($customer->foto)->header('Content-Type', 'image/jpeg');
    }

    // Halaman form tambah customer (metode 2: file)
    public function createFile()
    {
        return view('customer.create_file');
    }

    // Proses simpan customer (metode 2: file)
    public function storeFile(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email' => 'nullable|email',
            'foto' => 'required|string', // base64 dari kamera
        ]);

        // Decode base64 jadi image
        $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $request->foto);
        $imageData = base64_decode($imageData);
        
        // Generate nama file unik
        $filename = 'customer_' . time() . '_' . uniqid() . '.jpg';
        $path = 'uploads/customers/' . $filename;
        
        // Simpan ke storage/app/public/uploads/customers/
        Storage::disk('public')->put($path, $imageData);
        
        Customer::create([
            'nama_customer' => $request->nama_customer,
            'email' => $request->email,
            'foto_path' => $path,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (foto disimpan sebagai file)');
    }

    // Hapus customer
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        
        // Hapus file jika ada
        if ($customer->foto_path && Storage::disk('public')->exists($customer->foto_path)) {
            Storage::disk('public')->delete($customer->foto_path);
        }
        
        $customer->delete();
        
        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class TamuController extends Controller
{
    /**
     * Menampilkan halaman Beranda
     */
    public function home()
    {
        return view('tamu.buku-tamu', ['isBeranda' => true]);
    }

    /**
     * Menampilkan halaman Buku Tamu
     */
    public function index()
    {
        $totalBulanIni = Tamu::whereBetween('hari_tanggal', [
            now('Asia/Jayapura')->startOfMonth()->toDateString(),
            now('Asia/Jayapura')->endOfMonth()->toDateString(),
        ])->count();

        return view('tamu.buku-tamu', [
            'totalBulanIni' => $totalBulanIni,
            'isBeranda' => false,
        ]);
    }

    /**
     * Menyimpan data Buku Tamu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',

            'instansi' => 'required|string|max:255',

            'alamat' => 'required|string',

            'keperluan' => 'required|string',

            'tujuan' => 'required|string|max:255',

            'no_handphone' => 'required|string|max:20',

            'paraf' => 'required|string',

            'dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',

            'instansi.required' => 'Instansi wajib diisi.',

            'alamat.required' => 'Alamat wajib diisi.',

            'keperluan.required' => 'Keperluan wajib diisi.',

            'tujuan.required' => 'Tujuan wajib dipilih.',

            'no_handphone.required' => 'Nomor handphone wajib diisi.',

            'paraf.required' => 'Paraf wajib dibuat.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tanggal dan jam menggunakan waktu server
        |--------------------------------------------------------------------------
        |
        | Kita tidak menggunakan tanggal/jam dari browser sebagai sumber utama.
        | Ini membuat data lebih aman dan konsisten.
        |
        */

        $now = Carbon::now('Asia/Jayapura');

        $validated['hari_tanggal'] = $now->toDateString();

        $validated['jam'] = $now->format('H:i:s');

        /*
        |--------------------------------------------------------------------------
        | Simpan ke database
        |--------------------------------------------------------------------------
        */

        $dokumentasi = $request->file('dokumentasi')?->store('tamus/dokumentasi', 'local');

        if ($request->hasFile('dokumentasi') && ! $dokumentasi) {
            throw new RuntimeException('Foto dokumentasi gagal disimpan.');
        }

        $validated['gambar'] = $dokumentasi;
        $validated['dokumentasi'] = $dokumentasi;

        try {
            Tamu::create($validated);
        } catch (Throwable $exception) {
            $disk = Storage::disk('local');
            if ($dokumentasi && $disk->exists($dokumentasi) && ! $disk->delete($dokumentasi)) {
                throw new RuntimeException('Foto dokumentasi gagal dibersihkan setelah penyimpanan data gagal.', 0, $exception);
            }

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | Kembali ke halaman Buku Tamu
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data kunjungan berhasil disimpan.',
            ]);
        }

        return redirect()
            ->route('tamu.berhasil')
            ->with('success', 'Data kunjungan berhasil disimpan.');
    }
}

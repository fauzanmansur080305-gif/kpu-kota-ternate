<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkDeleteTamuRequest;
use App\Models\Tamu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class TamuController extends Controller
{
    /**
     * Menampilkan seluruh data tamu.
     */
    public function index(Request $request): View|JsonResponse
    {
        $today = now('Asia/Jayapura')->toDateString();
        $totalTamu = Tamu::count();
        $totalTamuHariIni = Tamu::whereDate('hari_tanggal', $today)->count();
        $filter = $request->query('filter', 'all');
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['q'] ?? '');

        abort_unless(in_array($filter, ['all', 'today', 'month'], true), 404);

        $query = Tamu::query();

        if ($filter === 'today') {
            $query->whereDate('hari_tanggal', $today);
        } elseif ($filter === 'month') {
            $query->whereBetween('hari_tanggal', [
                now('Asia/Jayapura')->startOfMonth()->toDateString(),
                now('Asia/Jayapura')->endOfMonth()->toDateString(),
            ]);
        }

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $searchTerm = '%'.$search.'%';

                $query->where('nama', 'like', $searchTerm)
                    ->orWhere('instansi', 'like', $searchTerm)
                    ->orWhere('tujuan', 'like', $searchTerm)
                    ->orWhere('keperluan', 'like', $searchTerm)
                    ->orWhere('no_handphone', 'like', $searchTerm)
                    ->orWhere('alamat', 'like', $searchTerm);
            });
        }

        $tamus = $query->latest()->paginate(10)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('admin.tamu.partials.search-results', compact('tamus', 'search'))->render(),
                'currentPage' => $tamus->currentPage(),
                'lastPage' => $tamus->lastPage(),
            ]);
        }

        return view('admin.tamu.index', compact(
            'tamus',
            'totalTamu',
            'totalTamuHariIni',
            'filter',
            'search'
        ));
    }

    /**
     * Menampilkan form tambah tamu.
     */
    public function create()
    {
        return view('admin.tamu.create');
    }

    /**
     * Menyimpan data tamu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $this->validateSignature($validated['paraf'] ?? null);
        $gambar = $request->file('gambar')?->store('tamus/dokumentasi', 'local');

        if ($request->hasFile('gambar') && ! $gambar) {
            throw new RuntimeException('Gambar dokumentasi gagal disimpan.');
        }

        try {
            $validated['gambar'] = $gambar;
            $validated['dokumentasi'] = $gambar;
            Tamu::create($validated);
        } catch (Throwable $exception) {
            $this->deleteStoredImage($gambar);

            throw $exception;
        }

        return redirect()
            ->route('admin.tamu.index')
            ->with('success', 'Data tamu berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail tamu.
     */
    public function show(Tamu $tamu)
    {
        return view('admin.tamu.show', compact('tamu'));
    }

    /**
     * Menampilkan form edit tamu.
     */
    public function edit(Tamu $tamu)
    {
        return view('admin.tamu.edit', compact('tamu'));
    }

    /**
     * Mengupdate data tamu.
     */
    public function update(Request $request, Tamu $tamu)
    {
        $validated = $request->validate($this->rules());
        $this->validateSignature($validated['paraf'] ?? null);
        $gambarLama = $tamu->documentationPath();
        $gambarBaru = $request->file('gambar')?->store('tamus/dokumentasi', 'local');

        if ($request->hasFile('gambar') && ! $gambarBaru) {
            throw new RuntimeException('Gambar dokumentasi gagal disimpan.');
        }

        try {
            if ($gambarBaru) {
                $validated['gambar'] = $gambarBaru;
                $validated['dokumentasi'] = $gambarBaru;
            }

            $tamu->update($validated);
        } catch (Throwable $exception) {
            $this->deleteStoredImage($gambarBaru);

            throw $exception;
        }

        if ($gambarBaru && $gambarLama) {
            $this->deleteStoredImage($gambarLama);
        }

        return redirect()
            ->route('admin.tamu.index')
            ->with('success', 'Data tamu berhasil diperbarui.');
    }

    /**
     * Menghapus data tamu.
     */
    public function destroy(Tamu $tamu)
    {
        $gambar = $tamu->documentationPath();
        $tamu->delete();

        $this->deleteStoredImage($gambar);

        return redirect()
            ->route('admin.tamu.index')
            ->with('success', 'Data tamu berhasil dihapus.');
    }

    public function destroyMatching(BulkDeleteTamuRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $deleted = DB::transaction(function () use ($validated): array {
            $query = Tamu::query()->orderBy('id')->lockForUpdate();

            if ($validated['target'] === 'range') {
                $query->whereBetween('hari_tanggal', [
                    $validated['date_from'],
                    $validated['date_to'],
                ]);
            }

            $tamus = $query->get(['id', 'gambar', 'dokumentasi']);
            Tamu::query()->whereKey($tamus->modelKeys())->delete();

            return [
                'count' => $tamus->count(),
                'images' => $tamus->map(
                    fn (Tamu $tamu): ?string => $tamu->documentationPath()
                )->all(),
            ];
        });

        foreach ($deleted['images'] as $image) {
            $this->deleteStoredImage($image);
        }

        $message = match (true) {
            $deleted['count'] === 0 => 'Tidak ada data tamu yang sesuai untuk dihapus.',
            $validated['target'] === 'all' => 'Semua data tamu berhasil dihapus.',
            default => 'Data tamu pada rentang tanggal berhasil dihapus.',
        };

        return redirect()
            ->route('admin.tamu.index')
            ->with('success', $message);
    }

    /**
     * Aturan validasi data tamu untuk tambah dan edit.
     */
    private function rules(): array
    {
        return [
            'hari_tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'nama' => ['required', 'string', 'max:255'],
            'instansi' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'keperluan' => ['required', 'string'],
            'tujuan' => ['required', 'string', 'max:255'],
            'no_handphone' => ['required', 'string', 'max:20'],
            'paraf' => ['nullable', 'string', 'max:2097152', 'regex:/^data:image\/(png|jpeg);base64,[A-Za-z0-9+\/]+={0,2}$/'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
        ];
    }

    private function validateSignature(?string $signature): void
    {
        if (! $signature) {
            return;
        }

        [, $encodedImage] = explode(',', $signature, 2);
        $image = base64_decode($encodedImage, true);
        $details = $image === false ? false : getimagesizefromstring($image);

        if (! $details || ! in_array($details['mime'], ['image/png', 'image/jpeg'], true)) {
            throw ValidationException::withMessages([
                'paraf' => 'Paraf harus berupa gambar PNG atau JPEG yang valid.',
            ]);
        }
    }

    private function deleteStoredImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = Storage::disk('local');

        if ($disk->exists($path) && ! $disk->delete($path)) {
            throw new RuntimeException('Gambar dokumentasi gagal dihapus dari penyimpanan.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Admin\GuestWorkbookExporter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExportTamuRequest;
use App\Models\Tamu;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class LaporanController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.laporan.index', [
            'totalTamu' => Tamu::count(),
            'totalTamuHariIni' => Tamu::whereDate(
                'hari_tanggal',
                now('Asia/Jayapura')->toDateString()
            )->count(),
        ]);
    }

    public function exportTamu(ExportTamuRequest $request, GuestWorkbookExporter $exporter): BinaryFileResponse
    {
        $validated = $request->validated();
        $tamus = Tamu::query()->latest();
        $filename = 'laporan-data-tamu-'.now('Asia/Jayapura')->format('Y-m-d').'.xlsx';

        if ($validated['jenis'] === 'bulan') {
            [$year, $month] = explode('-', $validated['bulan']);
            $tamus->whereYear('hari_tanggal', (int) $year)
                ->whereMonth('hari_tanggal', (int) $month);
            $filename = 'laporan-data-tamu-'.$validated['bulan'].'.xlsx';
        }

        try {
            $file = $exporter->export($tamus->cursor());
        } catch (Throwable $exception) {
            report($exception);

            throw $exception;
        }

        return response()
            ->download(
                $file,
                $filename,
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            )
            ->deleteFileAfterSend(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; // <-- JANGAN LUPA TAMBAHKAN INI

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        // Tetap dipertahankan: Ini adalah query scope untuk memfilter data di tabel
        if (Auth::user()->role === 'admin') {
            $reports = Report::latest()->paginate(10);
        } else {
            $reports = Report::where('user_id', Auth::id())->latest()->paginate(10);
        }

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type'    => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $this->reportService->createReport(Auth::id(), $validatedData);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim. Tim kami akan segera meninjaunya.');
    }

    public function show($id)
    {
        $report = Report::findOrFail($id);

        // KODE BARU: Memanggil Policy untuk mengecek izin akses (Otomatis 403 jika gagal)
        Gate::authorize('view', $report);

        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:process,resolved'
        ]);

        $report = Report::findOrFail($id);

        // KODE BARU: Memastikan yang mengubah status adalah Admin lewat Policy
        Gate::authorize('updateStatus', $report);

        $this->reportService->updateStatus($report, $request->status);

        return redirect()->back()
            ->with('success', 'Status laporan berhasil diperbarui.');
    }
}

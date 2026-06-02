<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $reportService;

    // Dependency Injection (SOLID)
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        // Jika admin, tampilkan semua laporan. Jika pengguna, tampilkan laporannya sendiri
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
        // 1. Validasi Input
        $validatedData = $request->validate([
            'type'    => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 2. Oper pembuatan laporan ke Service
        $this->reportService->createReport(Auth::id(), $validatedData);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim. Tim kami akan segera meninjaunya.');
    }

    public function show($id)
    {
        $report = Report::findOrFail($id);

        // Proteksi Ekstra: Cegah pengguna membaca laporan orang lain
        if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk melihat laporan ini.');
        }

        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi Input (Hanya boleh process atau resolved)
        $request->validate([
            'status' => 'required|in:process,resolved'
        ]);

        // 2. Oper pembaruan status ke Service
        $report = Report::findOrFail($id);
        $this->reportService->updateStatus($report, $request->status);

        return redirect()->back()
            ->with('success', 'Status laporan berhasil diperbarui.');
    }
}

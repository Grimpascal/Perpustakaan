<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\User;
use App\Models\Denda;
use Carbon\Carbon;

class peminjamanController extends Controller
{
    // Ganti nama method dari showPeminjaman menjadi index
    public function index(Request $request)  // Nama method HARUS index untuk resource
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $dateRange = $request->get('date_range');
        
        $query = Peminjaman::with(['user', 'book'])
            ->orderBy('created_at', 'desc');
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function($bookQuery) use ($search) {
                    $bookQuery->where('judul', 'like', "%{$search}%")
                             ->orWhere('penulis', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($status) {
            if ($status == 'terlambat') {
                $query->where('status', 'dipinjam')
                      ->where('tanggal_harus_kembali', '<', Carbon::today());
            } else {
                $query->where('status', $status);
            }
        }
        
        // Apply date range filter
        if ($dateRange) {
            if ($dateRange == 'today') {
                $query->whereDate('tanggal_pinjam', Carbon::today());
            } elseif ($dateRange == 'week') {
                $query->whereBetween('tanggal_pinjam', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($dateRange == 'month') {
                $query->whereMonth('tanggal_pinjam', Carbon::now()->month);
            } elseif ($dateRange == 'overdue') {
                $query->where('status', 'dipinjam')
                      ->where('tanggal_harus_kembali', '<', Carbon::today());
            }
        }
        
        $peminjamans = $query->paginate(20);
        
        // Calculate statistics
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_harus_kembali', '<', Carbon::today())
            ->count();
        
        $title = "Manajemen Peminjaman";
        
        return view('admin/peminjamanAdmin', compact('title', 'peminjamans', 'totalPeminjaman', 'peminjamanAktif', 'peminjamanTerlambat'));
    }
    
    public function detail($id)
    {
        $peminjaman = Peminjaman::with(['user', 'book.kategori', 'book.penerbit'])->findOrFail($id);
        
        $hariTelat = 0;
        $dendaPerHari = 10000;
        
        if ($peminjaman->status == 'dipinjam' && Carbon::now()->greaterThan($peminjaman->tanggal_harus_kembali)) {
            $hariTelat = Carbon::now()->diffInDays($peminjaman->tanggal_harus_kembali);
        }
        
        $dendas = Denda::where('peminjaman_id', $id)->get();
        
        return view('admin/peminjamanAdmin', compact('peminjaman', 'hariTelat', 'dendaPerHari', 'dendas'));
    }
    
    public function calculateDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $hariTelat = 0;
        $denda = 0;
        
        if ($peminjaman->status == 'dipinjam' && Carbon::now()->greaterThan($peminjaman->tanggal_harus_kembali)) {
            $hariTelat = Carbon::now()->diffInDays($peminjaman->tanggal_harus_kembali);
            $denda = $hariTelat * 10000;
        }
        
        return response()->json([
            'hari_telat' => $hariTelat,
            'denda' => $denda
        ]);
    }
    
    public function returnBook(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('book')->findOrFail($id);
        
        if ($peminjaman->status == 'dikembalikan') {
            return redirect()->route('peminjaman.index')->with('error', 'Buku sudah dikembalikan sebelumnya');
        }
        
        // Calculate denda if late
        $denda = $request->denda ?? 0;
        if ($denda == 0 && Carbon::now()->greaterThan($peminjaman->tanggal_harus_kembali)) {
            $hariTelat = Carbon::now()->diffInDays($peminjaman->tanggal_harus_kembali);
            $denda = $hariTelat * 10000;
        }
        
        // Update peminjaman
        $peminjaman->status = 'dikembalikan';
        $peminjaman->tanggal_dikembalikan = Carbon::now();
        $peminjaman->denda = $denda;
        $peminjaman->catatan = $request->catatan;
        $peminjaman->save();
        
        // Update book stock
        $book = $peminjaman->book;
        $book->stok += 1;
        $book->is_available = true;
        $book->save();
        
        // Create denda record if exists
        if ($denda > 0) {
            Denda::create([
                'peminjaman_id' => $peminjaman->id,
                'jumlah_denda' => $denda,
                'status' => 'belum_bayar',
                'keterangan' => 'Denda keterlambatan pengembalian'
            ]);
        }
        
        return redirect()->route('peminjaman.index')
            ->with('success', 'Buku berhasil dikembalikan!' . ($denda > 0 ? " Denda: Rp " . number_format($denda, 0, ',', '.') : ''));
    }
    
    // Method destroy untuk DELETE /peminjaman/{id}
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Only allow delete if already returned
        if ($peminjaman->status == 'dipinjam') {
            return redirect()->route('peminjaman.index')->with('error', 'Tidak bisa menghapus peminjaman yang masih aktif');
        }
        
        // Delete related denda records first
        Denda::where('peminjaman_id', $id)->delete();
        
        // Delete peminjaman
        $peminjaman->delete();
        
        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus');
    }
    
    public function export(Request $request)
    {
        // For now, just show info message
        return redirect()->route('peminjaman.index')
            ->with('info', 'Fitur export Excel akan segera tersedia.');
    }
    
    // Method show untuk GET /peminjaman/{id} (jika diperlukan)
    public function show($id)
    {
        // Anda bisa redirect ke detail atau tampilkan view lain
        return redirect()->route('peminjaman.detail', $id);
    }
}
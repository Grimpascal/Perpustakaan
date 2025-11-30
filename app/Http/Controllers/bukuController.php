<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\kategori;
use App\Models\Penerbit;

class bukuController extends Controller
{

    public function index(Request $request)
    {
        $title = 'Manajemen Buku';
        
        $books = Book::when(['kategori', 'penerbit'])
            ->when($request->search, function($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->when($request->kategori, function($query, $kategori) {
                $query->where('kategori_id', $kategori);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kategories = Kategori::all();
        
        return view('admin/bukuAdmin', compact('title', 'books', 'kategories'));
    }

    public function create()
    {
        $title = 'Tambah Buku';
        $kategories = Kategori::all();
        $penerbits = Penerbit::all();
        
        return view('admin.buku-tambah', compact('title', 'kategories', 'penerbits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit_id' => 'required|exists:penerbits,id',
            'kategori_id' => 'required|exists:kategories,id',
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn',
            'deskripsi' => 'nullable|string',
            'bahasa' => 'required|string',
            'stok' => 'required|integer|min:1',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Book::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit_id' => $request->penerbit_id,
            'kategori_id' => $request->kategori_id,
            'tahun' => $request->tahun,
            'isbn' => $request->isbn,
            'deskripsi' => $request->deskripsi,
            'bahasa' => $request->bahasa,
            'stok' => $request->stok,
            'cover' => $coverPath,
            'is_available' => true,
            'total_dipinjam' => 0,
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit($id) // Ubah parameter menjadi $id
    {
        $title = 'Edit Buku';
        $buku = Book::findOrFail($id); // Cari buku berdasarkan ID
        $kategories = Kategori::all();
        $penerbits = Penerbit::all();
        
    }

     public function update(Request $request, $id) // Ubah parameter menjadi $id
    {
        $buku = Book::findOrFail($id); // Cari buku berdasarkan ID
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit_id' => 'required|exists:penerbits,id',
            'kategori_id' => 'required|exists:kategories,id',
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn,' . $buku->id,
            'deskripsi' => 'nullable|string',
            'bahasa' => 'required|string',
            'stok' => 'required|integer|min:1',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coverPath = $buku->cover;
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit_id' => $request->penerbit_id,
            'kategori_id' => $request->kategori_id,
            'tahun' => $request->tahun,
            'isbn' => $request->isbn,
            'deskripsi' => $request->deskripsi,
            'bahasa' => $request->bahasa,
            'stok' => $request->stok,
            'cover' => $coverPath,
            'is_available' => $request->stok > 0,
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil diupdate.');
    }

    public function hapus(Book $buku)
    {
        try {
            // Hapus cover jika ada
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            
            $buku->delete();

            return redirect()->route('buku.index')
                ->with('success', 'Buku berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('buku.index')
                ->with('error', 'Terjadi kesalahan saat menghapus buku.');
        }
    }
}

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
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        $title = 'Manajemen Buku';

        $books = Book::with(['kategori', 'penerbit'])
            ->when($search, function($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->when($kategori, function($query) use ($kategori) {
                $query->where('kategori_id', $kategori);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kategories = Kategori::all();
        $penerbits = Penerbit::all();

        return view('admin/bukuAdmin', compact('books', 'kategories', 'penerbits', 'title'));
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
            'penerbit_id' => 'nullable|exists:penerbits,id',
            'kategori_id' => 'nullable|exists:kategories,id',
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn',
            'deskripsi' => 'nullable|string',
            'bahasa' => 'nullable|string|max:50',
            'stok' => 'required|integer|min:1',
        ]);

        Book::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit_id' => $request->penerbit_id,
            'kategori_id' => $request->kategori_id,
            'tahun' => $request->tahun,
            'isbn' => $request->isbn,
            'deskripsi' => $request->deskripsi,
            'bahasa' => $request->bahasa ?? 'Indonesia',
            'stok' => $request->stok,
            'is_available' => $request->stok > 0,
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id) // Ubah parameter menjadi $id
    {
        $title = 'Edit Buku';
        $buku = Book::findOrFail($id); // Cari buku berdasarkan ID
        $kategories = Kategori::all();
        $penerbits = Penerbit::all();
        
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        // Validasi hanya untuk field yang diisi
        $rules = [];
        $messages = [];
        
        if ($request->filled('judul')) {
            $rules['judul'] = 'string|max:255';
        }
        
        if ($request->filled('penulis')) {
            $rules['penulis'] = 'string|max:255';
        }
        
        if ($request->filled('penerbit_id')) {
            $rules['penerbit_id'] = 'exists:penerbits,id';
        }
        
        if ($request->filled('kategori_id')) {
            $rules['kategori_id'] = 'exists:kategories,id';
        }
        
        if ($request->filled('tahun')) {
            $rules['tahun'] = 'integer|min:1900|max:' . date('Y');
        }
        
        if ($request->filled('isbn')) {
            $rules['isbn'] = 'string|unique:books,isbn,' . $id;
        }
        
        if ($request->filled('stok')) {
            $rules['stok'] = 'integer|min:0';
        }
        
        if ($request->filled('bahasa')) {
            $rules['bahasa'] = 'string|max:50';
        }
        
        if ($request->filled('deskripsi')) {
            $rules['deskripsi'] = 'string';
        }
        
        $validatedData = $request->validate($rules, $messages);
        
        // Update hanya field yang diisi
        foreach ($validatedData as $key => $value) {
            $book->$key = $value;
        }
        
        // Update status ketersediaan jika stok diubah
        if ($request->filled('stok')) {
            $book->is_available = $request->stok > 0;
        }
        
        $book->save();
        
        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil diperbarui!');
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

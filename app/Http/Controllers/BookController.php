<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('bookItems');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('isbn', 'like', '%' . $search . '%')
                  ->orWhere('subject', 'like', '%' . $search . '%');
            });
        }

        $books = $query->latest()->paginate(10);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required|string|unique:books,isbn',
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/books'), $imageName);
            $data['cover_image'] = 'images/books/' . $imageName;
        }

        Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Sách đã được thêm thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('bookItems.rack');
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && file_exists(public_path($book->cover_image))) {
                unlink(public_path($book->cover_image));
            }

            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/books'), $imageName);
            $data['cover_image'] = 'images/books/' . $imageName;
        }

        $book->update($data);

        return redirect()->route('books.index')
            ->with('success', 'Thông tin sách đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover_image && file_exists(public_path($book->cover_image))) {
            unlink(public_path($book->cover_image));
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Sách đã được xóa thành công.');
    }
}
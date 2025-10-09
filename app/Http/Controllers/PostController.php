<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Share;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'book', 'comments.user', 'likes', 'shares'])
            ->latest()
            ->paginate(10);
        
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $books = Book::all();
        return view('posts.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('posts.index')->with('success', 'Đã tạo bài viết thành công!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'book', 'comments.user', 'likes', 'shares']);
        return view('posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isLibrarian()) {
            return back()->with('error', 'Bạn không có quyền xóa bài viết này!');
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Đã xóa bài viết!');
    }

    public function like(Post $post)
    {
        $userId = Auth::id();
        $existingLike = Like::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete();
            return back()->with('success', 'Đã bỏ thích!');
        }

        Like::create([
            'post_id' => $post->id,
            'user_id' => $userId,
        ]);

        return back()->with('success', 'Đã thích bài viết!');
    }

    public function share(Post $post)
    {
        Share::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Đã chia sẻ bài viết!');
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Đã thêm bình luận!');
    }
}

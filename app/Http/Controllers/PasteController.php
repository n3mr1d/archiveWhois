<?php

namespace App\Http\Controllers;

use App\Models\Paste;
use Illuminate\Http\Request;

class PasteController extends Controller
{
    public function home()
    {
        $recent = Paste::public()->with('user')->latest()->limit(8)->get();
        return view('home', compact('recent'));
    }

    public function create()
    {
        return view('paste.create');
    }

    public function show(string $slug)
    {
        $paste = Paste::where('slug', $slug)->firstOrFail();
        $user = auth()->user();

        // Private pastes: only owner or admin can see
        if ($paste->visibility === 'private') {
            if (!$user || ($paste->user_id !== $user->id && !$user->isAdmin())) {
                abort(403, 'This paste is private.');
            }
        }

        return view('paste.show', compact('paste'));
    }

    public function search()
    {
        return view('paste.search');
    }

    public function trending()
    {
        return view('paste.trending');
    }

    public function raw(string $slug)
    {
        $paste = Paste::where('slug', $slug)->firstOrFail();
        $user = auth()->user();

        if ($paste->visibility === 'private') {
            if (!$user || ($paste->user_id !== $user->id && !$user->isAdmin())) {
                abort(403);
            }
        }

        if ($paste->isPasswordProtected()) {
            abort(403, 'Password protected pastes cannot be viewed as raw.');
        }

        return response($paste->content, 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}

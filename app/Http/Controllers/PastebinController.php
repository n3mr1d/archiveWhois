<?php

namespace App\Http\Controllers;

use App\Http\Requests\PastebinRequest;
use App\Models\Pastebin;
use App\Models\Category;
use App\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PastebinController extends Controller
{
    public function create()
    {
        return view('pastebin.create');
    }

    public function store(PastebinRequest $request)
    {
        $dataValidate = $request->validated();

        return DB::transaction(function () use ($request, $dataValidate) {
            // Generate Random Agent Alias
            $adjectives = ['Silent', 'Ghost', 'Shadow', 'Dark', 'Hidden', 'Void', 'Phantom', 'Stealth', 'Covert', 'Cipher'];
            $nouns = ['Operative', 'Agent', 'Watcher', 'Specter', 'Wraith', 'Zero', 'Echo', 'Nexus', 'Vector', 'Pulse'];
            $randomAuthor = $adjectives[array_rand($adjectives)] . ' ' . $nouns[array_rand($nouns)] . ' #' . rand(100, 999);
            $bannerPath = 'profile_anonymous.png';

            // Generate unique slug (handle duplicates)
            $baseSlug = Str::slug($dataValidate['title']);
            $finalSlug = $baseSlug;
            $counter = 1;
            while (Slug::where('slug', $finalSlug)->exists()) {
                $finalSlug = $baseSlug . '-' . $counter++;
            }

            $slug = Slug::create(['slug' => $finalSlug]);

            // Handle banner upload
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $extension = $file->getClientOriginalExtension();
                $nameFile = Str::random(4) . '_' . $finalSlug . '_' . now()->format('Ymd') . '.' . $extension;
                $bannerPath = $file->storeAs('banners_pastebin', $nameFile, 'public');
            }

            // Handle password
            $password = null;
            if (!empty($dataValidate['password'])) {
                $password = Hash::make($dataValidate['password']);
            }

            $pastebin = Pastebin::create([
                'title'        => $dataValidate['title'],
                'author_name'  => $randomAuthor,
                'description'  => $dataValidate['description'] ?? null,
                'content'      => $dataValidate['content'],
                'banner_path'  => $bannerPath,
                'slug_id'      => $slug->id,
                'user_id'      => auth()->id(),
                'language'     => $dataValidate['language'] ?? null,
                'visibility'   => $dataValidate['visibility'] ?? 'public',
                'expires_at'   => $this->resolveExpiry($dataValidate['expiry'] ?? 'never'),
                'password'     => $password,
                'syntax_theme' => $dataValidate['syntax_theme'] ?? 'github-dark',
            ]);

            // Handle categories/tags
            if (!empty($dataValidate['categories'])) {
                $categoryNames = collect(explode(',', $dataValidate['categories']))
                    ->map(fn($name) => trim($name))
                    ->filter()
                    ->unique()
                    ->take(4);

                foreach ($categoryNames as $name) {
                    $category = Category::firstOrCreate(
                        ['name' => $name],
                        ['type' => 'general', 'slug' => Str::slug($name)]
                    );
                    $pastebin->categories()->attach($category->id);
                }
            }

            // Handle Gallery
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $index => $image) {
                    $extension = $image->getClientOriginalExtension();
                    $generateName = Str::random(4) . '_' . $finalSlug . '_' . now()->format('Ymd') . '_img' . $index . '.' . $extension;
                    $path = $image->storeAs('galleries', $generateName, 'public');
                    $pastebin->galleries()->create(['image_path' => $path]);
                }
            }

            return redirect()->route('pastebin.show', $slug->slug)
                ->with('success', 'Paste published successfully!');
        });
    }

    public function show(Request $request, string $slug)
    {
        $slugModel = Slug::where('slug', $slug)->firstOrFail();
        $pastebin = Pastebin::with(['categories', 'galleries', 'slug'])
            ->where('slug_id', $slugModel->id)
            ->firstOrFail();

        // Check if expired
        if ($pastebin->hasExpired()) {
            abort(410, 'This paste has expired.');
        }

        // Handle password protection
        if ($pastebin->isPasswordProtected()) {
            if (!$request->session()->get("paste_access_{$pastebin->id}")) {
                if ($request->isMethod('post')) {
                    $inputPassword = $request->input('paste_password', '');
                    if (Hash::check($inputPassword, $pastebin->password)) {
                        $request->session()->put("paste_access_{$pastebin->id}", true);
                    } else {
                        return view('pastebin.password', compact('pastebin'))
                            ->with('error', 'Incorrect password. Try again.');
                    }
                } else {
                    return view('pastebin.password', compact('pastebin'));
                }
            }
        }

        // Increment view count (once per session)
        $viewKey = "viewed_paste_{$pastebin->id}";
        if (!$request->session()->has($viewKey)) {
            $pastebin->incrementViews();
            $request->session()->put($viewKey, true);
        }

        // Get recent pastes for sidebar
        $recentPastes = Pastebin::with('slug')
            ->public()
            ->notExpired()
            ->where('id', '!=', $pastebin->id)
            ->recent()
            ->limit(5)
            ->get();

        return view('pastebin.show', compact('pastebin', 'recentPastes'));
    }

    /**
     * Resolve expiry time from option string.
     */
    private function resolveExpiry(string $option): ?string
    {
        return match ($option) {
            '10min'  => now()->addMinutes(10)->toDateTimeString(),
            '1hour'  => now()->addHour()->toDateTimeString(),
            '1day'   => now()->addDay()->toDateTimeString(),
            '1week'  => now()->addWeek()->toDateTimeString(),
            '1month' => now()->addMonth()->toDateTimeString(),
            '1year'  => now()->addYear()->toDateTimeString(),
            default  => null, // never
        };
    }
}

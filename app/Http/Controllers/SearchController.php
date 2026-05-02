<?php

namespace App\Http\Controllers;

use App\Models\Pastebin;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Handle the search query - Google-style full-text search.
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $filter = $request->get('filter', 'all'); // all, title, description, tags
        $sort = $request->get('sort', 'relevance'); // relevance, date, views
        $language = $request->get('lang', '');
        $perPage = 12;

        $results = null;
        $suggestions = collect();
        $relatedTags = collect();
        $totalCount = 0;
        $searchTime = 0;

        if (!empty($query)) {
            $startTime = microtime(true);

            $builder = Pastebin::with(['slug', 'categories'])
                ->public()
                ->notExpired();

            // Apply filter
            switch ($filter) {
                case 'title':
                    $builder->where('title', 'LIKE', "%{$query}%");
                    break;
                case 'description':
                    $builder->where('description', 'LIKE', "%{$query}%");
                    break;
                case 'tags':
                    $builder->whereHas('categories', fn($q) => $q->where('name', 'LIKE', "%{$query}%"));
                    break;
                default:
                    $builder->search($query);
                    break;
            }

            // Language filter
            if (!empty($language)) {
                $builder->where('language', $language);
            }

            // Apply sorting
            switch ($sort) {
                case 'date':
                    $builder->orderBy('created_at', 'desc');
                    break;
                case 'views':
                    $builder->orderBy('views', 'desc');
                    break;
                default: // relevance
                    // Boost exact title matches first
                    $builder->orderByRaw(
                        "CASE
                            WHEN title = ? THEN 0
                            WHEN title LIKE ? THEN 1
                            WHEN description LIKE ? THEN 2
                            ELSE 3
                        END",
                        [$query, "%{$query}%", "%{$query}%"]
                    )->orderBy('views', 'desc');
                    break;
            }

            $totalCount = (clone $builder)->count();
            $results = $builder->paginate($perPage)->withQueryString();

            // Get related tags
            $relatedTags = Category::whereHas('pastebins', function ($q) use ($query) {
                $q->public()->notExpired()->search($query);
            })
            ->withCount(['pastebins' => fn($q) => $q->public()->notExpired()])
            ->orderBy('pastebins_count', 'desc')
            ->limit(10)
            ->get();

            // Search suggestions for empty results
            if ($totalCount === 0) {
                $words = explode(' ', $query);
                foreach ($words as $word) {
                    if (strlen($word) >= 3) {
                        $suggested = Pastebin::public()
                            ->notExpired()
                            ->where('title', 'LIKE', "%{$word}%")
                            ->limit(3)
                            ->pluck('title');
                        $suggestions = $suggestions->merge($suggested);
                    }
                }
                $suggestions = $suggestions->unique()->take(5);
            }

            $endTime = microtime(true);
            $searchTime = round(($endTime - $startTime) * 1000, 2);
        }

        // Get popular searches / trending for empty query
        $trending = Pastebin::with(['slug', 'categories'])
            ->trending()
            ->limit(6)
            ->get();

        // Available languages for filter
        $availableLanguages = Pastebin::public()
            ->notExpired()
            ->whereNotNull('language')
            ->select('language')
            ->distinct()
            ->pluck('language');

        return view('search.index', compact(
            'query',
            'filter',
            'sort',
            'language',
            'results',
            'suggestions',
            'relatedTags',
            'totalCount',
            'searchTime',
            'trending',
            'availableLanguages'
        ));
    }

    /**
     * Autocomplete / live suggestions API.
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Pastebin::public()
            ->notExpired()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "{$query}%")
                  ->orWhere('title', 'LIKE', "%{$query}%");
            })
            ->orderBy('views', 'desc')
            ->limit(8)
            ->get(['title', 'description', 'slug_id'])
            ->map(function ($paste) {
                return [
                    'title' => $paste->title,
                    'description' => $paste->description
                        ? \Illuminate\Support\Str::limit($paste->description, 60)
                        : null,
                    'url' => $paste->slug
                        ? route('pastebin.show', $paste->slug->slug)
                        : '#',
                ];
            });

        // Also suggest matching categories
        $tags = Category::where('name', 'LIKE', "%{$query}%")
            ->withCount(['pastebins' => fn($q) => $q->public()])
            ->orderBy('pastebins_count', 'desc')
            ->limit(4)
            ->get()
            ->map(fn($cat) => [
                'type' => 'tag',
                'name' => $cat->name,
                'count' => $cat->pastebins_count,
                'url' => route('search.index') . '?q=' . urlencode($cat->name) . '&filter=tags',
            ]);

        return response()->json([
            'pastes' => $suggestions,
            'tags' => $tags,
        ]);
    }

    /**
     * Stats endpoint for the search engine stats bar.
     */
    public function stats()
    {
        $totalPastes = Pastebin::public()->notExpired()->count();
        $totalViews = Pastebin::public()->sum('views');
        $totalCategories = \App\Models\Category::count();

        return response()->json([
            'total_pastes' => $totalPastes,
            'total_views' => $totalViews,
            'total_categories' => $totalCategories,
        ]);
    }
}

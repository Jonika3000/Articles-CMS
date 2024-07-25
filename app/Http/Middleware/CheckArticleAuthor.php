<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Article;

class CheckArticleAuthor
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $article
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $articleId = $request->route('id');
        $article = Article::find($articleId);

        if ($article && Auth::user()->id !== $article->user_id) {
            return response()->json(['message' => 'Unauthorized'], \Illuminate\Http\Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

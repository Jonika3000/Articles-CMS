<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index','show']]);
    }


    public function index()
    {
        $articles = Article::all();
        return response()->json($articles, Response::HTTP_OK);
    }

    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($article, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $article = Article::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => Auth::id(),
        ]);

        return response()->json($article, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        if ($article->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $article->update([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
        ]);

        return response()->json($article, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        if ($article->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted successfully'], Response::HTTP_OK);
    }
}

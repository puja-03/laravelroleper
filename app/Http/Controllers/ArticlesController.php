<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class ArticlesController extends Controller  implements HasMiddleware

{
    public static function middleware(): array
    {
        return [
           new Middleware(('permission:view articles'),only: ['index', 'show']),
           new Middleware(('permission:create articles'),only: ['create', 'store']),
           new Middleware(('permission:edit articles'),only: ['edit', 'update']),
           new Middleware(('permission:delete articles'),only:  ['destroy']),
        ];
    }
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('articles.list', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');  
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'author' => 'required|string|max:255'
        ]);

        if ($validator->passes()) {
            Article::create($request->all());
            return redirect()->route('articles.index')
                ->with('success', 'Article created successfully.');
        }

        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.create', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'author' => 'required|string|max:255'
        ]);

        if ($validator->passes()) {
            $article->update($request->all());
            return redirect()->route('articles.index')
                ->with('success', 'Article updated successfully.');
        }

        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect()->route('articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ArticleUploadRequest;

class ArticleUploadController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function upload(ArticleUploadRequest $request, Article $article)
    {
        if ($request->has('file')) {
            $path = $article->savePdfArticle();

            return response()->json(['success'=> $path]);
        }
    }
}

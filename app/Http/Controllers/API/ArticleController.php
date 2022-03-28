<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
  {
    $articles = Article::all();
    return response()->json($articles);
  }

  /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

  /**
    * Display the specified resource.
    *
    * @param  int  $slug
    * @return \Illuminate\Http\Response
    */
  public function show($slug)
  {
    $article = Article::findOrFail($slug);
    return response()->json($article);
  }

  /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy($id)
  {
    $article = Article::findOrFail($id);
    $article->delete();

    return response()->json($article::all());
  }

  public function getArticle(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Article::where('title', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = Article::paginate(10);
        }

        return $results;
    }

    public function GetArticleById($id)
    {
        return Article::find($id);
    }
}

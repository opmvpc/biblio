<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('articles.index');
});

Route::get('articles/importer', 'ImporterArticleController@index')->name('articles.importer.index');
Route::post('articles/importer', 'ImporterArticleController@store')->name('articles.importer.store');
Route::post('articles/{article}/upload', 'ArticleUploadController@upload')->name('articles.upload');
Route::post('articles/{article}/attach/cite', 'CitationController@attachCite')->name('articles.attach.cite');
Route::post('articles/{article}/attach/cite-par', 'CitationController@attachEstCitePar')->name('articles.attach.estCitePar');
Route::get('articles/{article}/detach/cite', 'CitationController@detachCite')->name('articles.detach.cite');
Route::get('articles/{article}/detach/cite-par', 'CitationController@detachEstCitePar')->name('articles.detach.estCitePar');
Route::post('articles/{article}/attach/keyword', 'KeywordController@attachKeyword')->name('articles.attach.keywords');
Route::get('articles/{article}/detach/keyword', 'KeywordController@detachKeyword')->name('articles.detach.keyword');
Route::post('articles/{article}/attach/auteur', 'AuteurController@attachAuteur')->name('articles.attach.auteurs');
Route::get('articles/{article}/detach/auteur', 'AuteurController@detachAuteur')->name('articles.detach.auteur');

Route::resource('articles', 'ArticleController');
Route::resource('categories', 'CategorieController');
Route::resource('keywords', 'KeywordController');
Route::resource('auteurs', 'AuteurController');
Route::resource('users', 'UserController')->only(['index', 'create', 'store']);

Route::get('visualisations', 'VisualisationController@index')->name('visualisations.index');
Route::get('visualisations/articles', 'VisualisationController@articles')->name('visualisations.articles');
Route::get('visualisations/keywords', 'VisualisationController@keywords')->name('visualisations.keywords');
Route::get('visualisations/api/articles', 'VisualisationController@dataArticle')->name('visualisations.api.articles');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

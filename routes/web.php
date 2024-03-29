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

Route::get('export', 'ExportController@index')->name('export.index');
Route::get('export/bibtex', 'ExportController@getBibtex')->name('export.bibtex');
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

Route::get('api/articles/{category?}', 'ArticleController@api')->name('api.articles');
Route::resource('articles', 'ArticleController');
Route::resource('categories', 'CategorieController');
Route::resource('keywords', 'KeywordController');
Route::resource('auteurs', 'AuteurController');
Route::resource('users', 'UserController')->only(['index', 'create', 'store']);

Route::get('visualisations', 'VisualisationController@index')->name('visualisations.index');
Route::get('visualisations/articles', 'VisualisationController@articles')->name('visualisations.articles');
Route::get('visualisations/api/articles', 'VisualisationController@dataArticles')->name('visualisations.api.articles');
Route::get('visualisations/categories', 'VisualisationController@categories')->name('visualisations.categories');
Route::get('visualisations/api/categories', 'VisualisationController@dataCategories')->name('visualisations.api.categories');
Route::get('visualisations/auteurs', 'VisualisationController@auteurs')->name('visualisations.auteurs');
Route::get('visualisations/api/auteurs', 'VisualisationController@dataAuteurs')->name('visualisations.api.auteurs');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

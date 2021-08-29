<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
$router->post('login', ['uses' => 'BlogController@loginUser']);
$router->post('signup-user', ['uses' => 'BlogController@SignupUser']);
$router->post('create-post', ['uses' => 'BlogController@createPost']);
$router->post('get-all-post', ['uses' => 'BlogController@getAllBlogPosts']);
$router->post('get-post', ['uses' => 'BlogController@getPost']);
$router->post('get-my-post', ['uses' => 'BlogController@getMyPost']);
$router->post('update-post', ['uses' => 'BlogController@updatePost']);
$router->post('delete-post', ['uses' => 'BlogController@deletePost']);
$router->post('view-post', ['uses' => 'BlogController@viewPost']);

<?php
// Order is important!
Router::root('posts#index');

Router::resource('posts', [
  'as' => 'blog',
  'except' => ['destroy'],
  'get' => ['search/:query/:page_number'],
  'resource' => ['comments', [
    'except' => ['index', 'show']
  ]]
]);

Router::resource('users', [
  'only' => ['index', 'show', 'add', 'create']
]);

Router::get('/login', 'sessions#login');
Router::post('/login', 'sessions#login');
Router::delete('/logout', 'sessions#logout');

Router::get('/test', function ($request, $response) {
  $response->json(['name' => 'andrew', 'age' => 27]);
});

Router::get('/pages/:page', 'pages#show');
Router::get('/pages/test', 'pages#test');
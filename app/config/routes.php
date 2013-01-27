<?php
Router::root('posts#index');

Router::resource('posts', [
  'as' => 'blog',
  'except' => ['destroy'],
  'get' => ['search/:query/:page_number']
]);

Router::resource('user', [
  'only' => ['add', 'create']
]);

Router::get('/login', 'sessions#login');
Router::post('/login', 'sessions#login');
Router::delete('/logout', 'sessions#logout');

Router::get('/test', function ($request, $response) {
  var_dump($request, $response);
  echo 'test';
});

Router::get('/pages/:page', 'pages#show');
Router::put('/pages/test', 'pages#test');
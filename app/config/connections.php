<?php
ActiveRecord\Config::initialize(function ($cfg) {
  $cfg->set_connections([
    'development' => 'mysql://dfm:password@localhost/dfm'
  ]);
});
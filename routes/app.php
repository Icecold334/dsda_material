<?php

use Illuminate\Support\Facades\Route;


Route::get('/json', function () {
  $items = [];

  for ($i = 0; $i < 50; $i++) {
    $items[] = [
      'nomor' => fake()->name(),
    ];
  }

  return response()->json([
    'status' => 'success',
    'data' => $items
  ]);
});

require 'app/kontrak.php';
require 'app/rab.php';
require 'app/driver.php';
require 'app/security.php';
require 'app/sudin.php';
require 'app/district.php';

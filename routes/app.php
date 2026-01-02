<?php

use Illuminate\Support\Facades\Route;


Route::get('/json', function () {
  sleep(10); // Simulate delay
  $items = [];

  for ($i = 0; $i < 50; $i++) {
    $items[] = [
      'name' => fake()->name(),
      'email' => fake()->email(),
      'phone' => fake()->phoneNumber(),
    ];
  }

  return response()->json([
    'status' => 'success',
    'data' => $items
  ]);
});

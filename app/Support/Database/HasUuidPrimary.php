<?php

namespace App\Support\Database;

use Illuminate\Database\Schema\Blueprint;

trait HasUuidPrimary
{
  public function uuid(Blueprint $table)
  {
    $table->uuid('id')->primary();
    $table->softDeletes();
    $table->timestamps();
  }
}

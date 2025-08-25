<?php

use Vyui\Services\Database\Migration\Blueprint;

// the intention behind the way that the migrations are going to work in a builder-esque
// kind of way via the use of anonymous functions to allow a simplistic approach.
// return function ($table) {
//     $table->create->table('migrations');
// };
//
//

return function (Blueprint $table) {
    $table->setTable('user');
    $table->string('first_name', 255)->nullable();
    $table->string('last_name', 255)->nullable();
    // $table->string('last_name', function ($column) {

    // });
};

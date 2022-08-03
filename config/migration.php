<?php

use Illuminate\Database\Schema\Blueprint;

/**
 * --------------------------------------------
 * Setting up database
 * -------------------------------------------
 * Don't change this variable name
 */
$db_up_migration = [
    [
        'key' => 1,
        'table' => 'user',
        "todo" => 'create',
        'run' => function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('names')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('user_image')->nullable();
            $table->text('password')->nullable();
            $table->text('token')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_deleted')->nullable();
        },
        'reason' => 'Creating user table',
    ],
    [
        'key' => 2,
        'table' => 'files',
        "todo" => 'create',
        'run' => function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id')->nullable();
            $table->string('unique_index')->unique()->nullable();
            $table->string('virtual_name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_location')->nullable();
            $table->string('ext')->nullable();
            $table->string('size')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('keyword')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_deleted')->nullable();
        },
        'reason' => 'Creating files table',
    ],
    [
        'key' => 3,
        'table' => 'links',
        "todo" => 'create',
        'run' => function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('names')->nullable();
            $table->string('company')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_deleted')->nullable();
        },
        'reason' => 'Creating links table',
    ],
];

/**
 * --------------------------------------------
 * Rollback database
 * -------------------------------------------
 * Don't change this variable name
 */
$db_down_migration = [
    [
        'key' => 1,
        'table' => 'user',
        'todo' => 'delete',
        'run' => 'drop',
        'reason' => 'Removing user table',
    ],
    [
        'key' => 2,
        'table' => 'files',
        'todo' => 'delete',
        'run' => 'drop',
        'reason' => 'Removing files table',
    ],
];

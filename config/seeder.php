<?php

use Faker\Factory;

$generate = Factory::create();
/**
 * --------------------------------------------
 * Enserting fake data in database
 * -------------------------------------------
 * Don't change this variable name
 */
$database_seeder = [
    [
        'key' => 1,
        'table' => 'user',
        'rows' => 1,
        'fields' => function () use ($generate) {
            return [
                'names' => $generate->firstNameMale,
                'username' => 'omega',
                'user_image' => $generate->imageUrl($width = 100, $height = 100),
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => $generate->dateTime($max = 'now', $timezone = null),
                'updated_at' => $generate->dateTime($max = 'now', $timezone = null),
                'is_deleted' => false,
            ];
        },
    ],
    [
        'key' => 2,
        'table' => 'script',
        'rows' => 3,
        'fields' => function () use ($generate) {
            return [
                'user_id' => 1,
                'title' => $generate->catchPhrase,
                'image' => $generate->imageUrl($width = 100, $height = 100),
                'price' => $generate->numberBetween($min = 1000, $max = 9000),
                'summary' => $generate->paragraph($nbSentences = 3, $variableNbSentences = true),
                'description' => $generate->text($maxNbChars = 500),
                'category' => 'Short Film',
                'genre' => 'Action',
                'filter' => 'New',
                'status' => 'published',
                'file' => $generate->imageUrl($width = 100, $height = 100),
                'created_at' => $generate->dateTime($max = 'now', $timezone = null),
                'updated_at' => $generate->dateTime($max = 'now', $timezone = null),
                'is_deleted' => false,
            ];
        },
    ],
    [
        'key' => 3,
        'table' => 'writter',
        'rows' => 3,
        'fields' => function () use ($generate) {
            return [
                'fullname' => $generate->firstNameMale,
                'phone' => '0789178645',
                'email' => $generate->text($maxNbChars = 10),
                'gender' => 'Male',
                'nationalism' => $generate->country,
                'cv' => $generate->imageUrl($width = 100, $height = 100),
                'status' => 'active',
                'summary' => $generate->paragraph($nbSentences = 4, $variableNbSentences = true),
                'bio' => $generate->text($maxNbChars = 500),
                'image' => $generate->imageUrl($width = 100, $height = 100),
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => $generate->dateTime($max = 'now', $timezone = null),
                'updated_at' => $generate->dateTime($max = 'now', $timezone = null),
                'is_deleted' => false,
            ];
        },
    ],
    [
        'key' => 4,
        'table' => 'clients',
        'rows' => 3,
        'fields' => function () use ($generate) {
            return [
                'fullname' => $generate->firstNameMale,
                'phone' => '0789178645',
                'email' => $generate->text($maxNbChars = 10),
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => $generate->dateTime($max = 'now', $timezone = null),
                'updated_at' => $generate->dateTime($max = 'now', $timezone = null),
                'is_deleted' => false,
            ];
        },
    ],
    [
        'key' => 5,
        'table' => 'script_order',
        'rows' => 3,
        'fields' => function () use ($generate) {
            return [
                'user_id' => $generate->numberBetween($min = 1, $max = 3),
                'script_id' => $generate->numberBetween($min = 1, $max = 3),
                'amount' => $generate->numberBetween($min = 1000, $max = 30000),
                'payment_status' => 'ok',
                'payment_type' => 'card',
                'link' => 'http://127.0.0.1:8000/admin',
                'repeat' => 1,
                'created_at' => $generate->dateTime($max = 'now', $timezone = null),
                'updated_at' => $generate->dateTime($max = 'now', $timezone = null),
                'is_deleted' => false,
            ];
        },
    ],
    [
        'key' => 6,
        'table' => 'consultancy',
        'rows' => 3,
        'fields' => function () use ($generate) {
            return [
                'fullname' => $generate->firstNameMale,
                'email' => $generate->text($maxNbChars = 10),
                'phone' => '0789178645',
                'file' => $generate->imageUrl($width = 100, $height = 100),
                'message' => $generate->text($maxNbChars = 500),
                'status' => 'pending',
                'created_at' => $generate->dateTime($max = 'now', $timezone = null),
                'updated_at' => $generate->dateTime($max = 'now', $timezone = null),
                'is_deleted' => false,
            ];
        },
    ],
];
<?php

use system\library\Lang;
/* -----------------------------
| Twig filter management
-------------------------------- */

$filters = [
    [
        'name' => 'env',
        'func' => function ($val) {
            return getenv($val);
        },
    ], [
        'name' => 'translate',
        'func' => function ($val) {
            return Lang::init()->Trans($val);
        },
    ],

];

/* -----------------------------
| Twig functions management
-------------------------------- */
$functions = [
    [
        'name' => 'isAdmin',
        'func' => function () {
            if (url()->contains('admin')) {
                return true;
            }
            return false;
        },
    ],
    [
        'name' => 'getFilesInFolder',
        'func' => function ($dir, $ext) {
            $files = scandir($dir, 1);
            $files = array_filter($files, function ($file_name) use ($ext) {
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                return $file_ext == $ext;
            });
            return $files;
        },
    ],
    [
        'name' => 'requestIs',
        'func' => function ($url, $feedback) {
            $url = strtolower($url);
            if (url() == $url) {
                return $feedback;
            }
        },
    ],
    [
        'name' => 'requestContain',
        'func' => function ($url, $feedback) {
            $url = strtolower($url);
            if (url()->contains($url)) {
                return $feedback;
            }
        },
    ],
    [
        'name' => 'cutText',
        'func' => function ($string, $length, $icon) {
            return cutText($string, $length, $icon);
        },
    ],
];
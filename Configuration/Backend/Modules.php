<?php

return [
    'web_nsblogsystem' => [
        'parent' => 'web',
        'position' => ['after' => 'list'],
        'access' => 'user',

        'path' => '/module/web/nsblogsystem',

        'labels' => 'LLL:EXT:ns_blog_system/Resources/Private/Language/locallang.xlf',

        'extensionName' => 'NsBlogSystem',

        'controllerActions' => [
            \NITSAN\NsBlogSystem\Controller\Backend\BlogController::class => [
                'list',
                'new',
                'create',
                'edit',
                'update',
                'delete'
            ],
        ],
    ],
];
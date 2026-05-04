<?php
return [
    'dependencies' => ['backend'],
    'imports' => [
        '@ns_blog_system/' => 'EXT:ns_blog_system/Resources/Public/JavaScript/',
        '@ns_blog_system/backend' =>
            'EXT:ns_blog_system/Resources/Public/JavaScript/backend.js',
    ],
];
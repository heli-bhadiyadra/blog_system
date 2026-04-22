<?php

return [
    'ctrl' => [
        'title' => 'Comment',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'name,email,comment',
        'rootLevel' => -1,
        'iconfile' => 'EXT:ns_blogsystem/Resources/Public/Icons/comment.svg',
    ],

    'types' => [
        '1' => [
            'showitem' => 'blog, name, email, comment, approved'
        ],
    ],

    'columns' => [

        'name' => [
            'exclude' => 0,
            'label' => 'Name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],

        'email' => [
            'exclude' => 0,
            'label' => 'Email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required,email'
            ],
        ],

        'comment' => [
            'exclude' => 0,
            'label' => 'Comment',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5
            ],
        ],

        'approved' => [
            'exclude' => 0,
            'label' => 'Approved',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],

        'blog' => [
            'label' => 'Blog',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_nsblogsystem_domain_model_blog',
                'minitems' => 0,
                'maxitems' => 1,
                'readOnly' => true, 
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'Hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],

        'tstamp' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

    ],
];
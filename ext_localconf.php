<?php

defined('TYPO3') or die();

call_user_func(function () {

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'NsBlogSystem',
        'Bloglist',
        [
            \NITSAN\NsBlogSystem\Controller\BlogController::class => 'list,show'
        ],
        [
            \NITSAN\NsBlogSystem\Controller\BlogController::class => ''
        ]
    );

});

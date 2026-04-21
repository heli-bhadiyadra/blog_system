<?php

defined('TYPO3') or die();

call_user_func(function () {

    // Frontend plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'NsBlogSystem',
        'Bloglist',
        [
            \NITSAN\NsBlogSystem\Controller\BlogController::class =>
                'list, show, createComment'
        ],
        [
            \NITSAN\NsBlogSystem\Controller\BlogController::class =>
                'createComment'
        ]
    );

    // Backend module
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'NsBlogSystem',
        'Blogmodule',
        [
            \NITSAN\NsBlogSystem\Controller\Backend\BlogController::class =>
                'list,new,create,edit,update,delete'
        ],
        [
            \NITSAN\NsBlogSystem\Controller\Backend\BlogController::class =>
                'create,update,delete'
        ]
    );

});
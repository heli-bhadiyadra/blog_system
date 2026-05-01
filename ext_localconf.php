<?php

defined('TYPO3') or die();

call_user_func(function () {

    // Frontend plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'NsBlogSystem',
        'BlogList',
        [
            \NITSAN\NsBlogSystem\Controller\BlogController::class =>
                'list, show, createComment, filter'
        ],
        [
            \NITSAN\NsBlogSystem\Controller\BlogController::class =>
                'createComment, filter'
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
    // Load TypoScript automatically
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'ns_blog_system',
        'setup',
        "@import 'EXT:ns_blog_system/Configuration/TypoScript/setup.typoscript'"
    );
    
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] =
        'EXT:ns_blog_system/Configuration/RTE/Default.yaml';

    $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['ns_blog'] =
        'EXT:ns_blog_system/Resources/Public/Css/contents.css';
});
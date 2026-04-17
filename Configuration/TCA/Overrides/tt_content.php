<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(function () {

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'NsBlogSystem',
        'Bloglist',
        'Blog List'
    );
    $pluginSignature = 'nsblogsystem_bloglist';

    // Add FlexForm
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        'FILE:EXT:ns_blog_system/Configuration/FlexForms/BlogSettings.xml'
    );

    // Show FlexForm in plugin
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
});

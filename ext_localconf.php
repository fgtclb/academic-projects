<?php

use FGTCLB\AcademicProjects\Controller\ProjectController;
use FGTCLB\AcademicProjects\Enumeration\PageTypes;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(static function (): void {
    $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);

    // Starting with TYPO3 v12.0 Configuration/page.tsconfig in an Extension is automatically loaded during build time
    // @see https://docs.typo3.org/m/typo3/reference-tsconfig/12.4/en-us/UsingSetting/PageTSconfig.html#pagesettingdefaultpagetsconfig
    if ($versionInformation->getMajorVersion() < 12) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
            @import \'EXT:academic_programs/Configuration/page.tsconfig\'
        ');
    }

    $projectDokType = PageTypes::TYPE_ACEDEMIC_PROJECT;
    // Allow backend users to drag and drop the new page type:
    ExtensionManagementUtility::addUserTSConfig(
        sprintf(
            'options.pageTree.doktypesToShowInNewPageDragArea := addToList(%d)',
            $projectDokType
        )
    );

    $plugins = [
        'ProjectList' => 'list',
        'ProjectListSingle' => 'list',
    ];

    // TODO: Add caching to non filtered lists
    foreach ($plugins as $pluginName => $action) {
        ExtensionUtility::configurePlugin(
            'AcademicProjects',
            $pluginName,
            [
                ProjectController::class => $action,
            ],
            [
                ProjectController::class => $action,
            ],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );
    }
})();

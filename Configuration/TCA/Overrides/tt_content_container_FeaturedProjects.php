<?php

declare(strict_types=1);

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if (! defined('TYPO3')) {
    die('Access denied.');
}

(static function (Registry $containerRegistry): void {
    $lllPrefix = 'LLL:EXT:hio_typo3_connector_wtl/Resources/Private/Language/locallang_be.xlf:hio.';

    $containerRegistry->configureContainer(
        (new ContainerConfiguration(
            'tx_hiotypo3connectorwtl_featured_projects',
            $lllPrefix . 'featuredProjects.title',
            $lllPrefix . 'featuredProjects.description',
            [
                [
                    [
                        'name' => $lllPrefix . 'featuredProjects.contentColumn',
                        'colPos' => 101,
                        'allowed' => [
                            'CType' => 'tx_hiotypo3connectorwtl_featured_project',
                        ],
                        'maxitems' => 12,
                    ],
                ],
            ]
        ))
        ->setIcon('tx-hio_typo3_connector_wtl-featured-projects')
        ->setSaveAndCloseInNewContentElementWizard(false)
    );

    $GLOBALS['TCA']['tt_content']['types']['tx_hiotypo3connectorwtl_featured_projects']['showitem'] = str_replace(
        'header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header.ALT.div_formlabel,',
        '--palette--;;headers,',
        (string)$GLOBALS['TCA']['tt_content']['types']['tx_hiotypo3connectorwtl_featured_projects']['showitem']
    );
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['tx_hiotypo3connectorwtl_featured_projects'] = 'tx-hio_typo3_connector_wtl-featured-projects';
})(GeneralUtility::makeInstance(Registry::class));

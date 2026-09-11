<?php

declare(strict_types=1);

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Information\Typo3Version;

if (! defined('TYPO3')) {
    die('Access denied.');
}
$lllPrefix = 'LLL:EXT:hio_typo3_connector_wtl/Resources/Private/Language/locallang_be.xlf:hio.';

$typo3Version = GeneralUtility::makeInstance(Typo3Version::class);

$gridConfiguration = [
    [
        [
            'name' => $lllPrefix . 'featuredProjects.contentColumn',
            'colPos' => 101,
            'maxitems' => 12,
        ],
    ],
];

if (version_compare($typo3Version->getBranch(), '14.0', '>=')) {
    $gridConfiguration[0][0]['allowedContentTypes'] = 'tx_hiotypo3connectorwtl_featured_project';
} else {
    $gridConfiguration[0][0]['allowed'] = [
        'CType' => 'tx_hiotypo3connectorwtl_featured_project',
    ];
}

GeneralUtility::makeInstance(Registry::class)->configureContainer(
    (new ContainerConfiguration(
        'tx_hiotypo3connectorwtl_featured_projects',
        $lllPrefix . 'featuredProjects.title',
        $lllPrefix . 'featuredProjects.description',
        $gridConfiguration
    ))
        ->setIcon('tx-hio_typo3_connector_wtl-featured-projects')
        ->setSaveAndCloseInNewContentElementWizard(false)
        ->setGroup('hio-publisher')
);

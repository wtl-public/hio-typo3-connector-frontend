<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (! defined('TYPO3')) {
    die('Access denied.');
}

(static function (): void {
    $lllPrefix = 'LLL:EXT:hio_typo3_connector_wtl/Resources/Private/Language/locallang_be.xlf:hio.';

    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'label' => $lllPrefix . 'tt_content.type.featuredPublication.title',
            'value' => 'tx_hiotypo3connectorwtl_featured_publication',
            'icon' => 'tx-hio_typo3_connector_wtl-featured-publication',
        ]
    );

    $GLOBALS['TCA']['tt_content']['palettes']['tx_hiotypo3connectorwtl_featured_publication'] = [
        'label' => $lllPrefix . 'tt_content.palette.featuredPublication.image.label',
        'description' => $lllPrefix . 'tt_content.palette.featuredPublication.image.description',
        'showitem' => '
            image,
            --linebreak--,
            tx_hiotypo3connectorwtl_featured_publication,
        ',
    ];

    $GLOBALS['TCA']['tt_content']['columns']['tx_hiotypo3connectorwtl_featured_publication'] = [
        'label' => $lllPrefix . 'tt_content.palette.featuredPublication.publication.label',
        'description' => $lllPrefix . 'tt_content.palette.featuredPublication.publication.description',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_hiotypo3connector_domain_model_publication',
            'minitems' => 1,
            'maxitems' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['types']['tx_hiotypo3connectorwtl_featured_publication'] = [
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;;general,
                --palette--;;tx_hiotypo3connectorwtl_featured_publication,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;;access,
                sectionIndex,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                categories,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                rowDescription,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        ',
        'columnsOverrides' => [
            'image' => [
                'config' => [
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'columns' => [
                            'crop' => [
                                'config' => [
                                    'cropVariants' => [
                                        'default' => [
                                            'title' => 'Default',
                                            'selectedRatio' => '3:2',
                                            'allowedAspectRatios' => [
                                                '16:9' => [
                                                    'title' => '16:9',
                                                    'value' => 16 / 9,
                                                ],
                                                '3:2' => [
                                                    'title' => '3:2',
                                                    'value' => 3 / 2,
                                                ],
                                                '1:1' => [
                                                    'title' => '1:1',
                                                    'value' => 1 / 1,
                                                ],
                                                'NaN' => [
                                                    'title' => 'Free',
                                                    'value' => 0.0,
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];


    $GLOBALS['TCA']['tt_content']['types']['tx_hiotypo3connectorwtl_featured_publication']['previewRenderer'] = \Wtl\HioTypo3ConnectorWtl\Backend\Preview\FeaturedPublicationPreviewRenderer::class;
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['tx_hiotypo3connectorwtl_featured_publication'] = 'tx-hio_typo3_connector_wtl-featured-publication';
})();

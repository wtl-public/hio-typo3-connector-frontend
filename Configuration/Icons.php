<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'tx-hio_typo3_connector_wtl-featured-projects' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:hio_typo3_connector/Resources/Public/Icons/list-of-projects.svg',
    ],
    'tx-hio_typo3_connector_wtl-featured-project' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:hio_typo3_connector/Resources/Public/Icons/project.svg',
    ],
    'tx-hio_typo3_connector_wtl-featured-publications' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:hio_typo3_connector/Resources/Public/Icons/list-of-publications.svg',
    ],
    'tx-hio_typo3_connector_wtl-featured-publication' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:hio_typo3_connector/Resources/Public/Icons/publication.svg',
    ],
];

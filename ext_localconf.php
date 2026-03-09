<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addPageTSConfig(
    '@import "EXT:hio_typo3_connector_wtl/Configuration/TsConfig/Page/ContentElements/*.tsconfig"'
);

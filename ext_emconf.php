<?php

$EM_CONF["hio_typo3_connector_wtl"] = [
    'title' => 'hio-typo3-connector-frontend',
    'description' => 'TYPO3 Connector for HISinOne',
    'version' => '1.0.7',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Wtl\\HioTypo3ConnectorWtl\\' => 'Classes/',
        ],
    ],
];

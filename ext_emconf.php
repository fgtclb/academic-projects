<?php

$EM_CONF[$_EXTKEY] = [
    'author' => 'FGTCLB',
    'author_company' => 'FGTCLB GmbH',
    'author_email' => 'hello@fgtclb.com',
    'category' => 'fe',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
            'backend' => '12.4.0-13.4.99',
            'extbase' => '12.4.0-13.4.99',
            'fluid' => '12.4.0-13.4.99',
            'install' => '12.4.0-13.4.99',
            'category_types' => '2.0.2',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'description' => 'Academic project pages for TYPO3 with specific structured data and typed sys_categories',
    'state' => 'beta',
    'title' => 'FGTCLB: Academic Projects',
    'version' => '2.0.2',
];

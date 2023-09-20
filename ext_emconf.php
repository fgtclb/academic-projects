<?php

$EM_CONF[$_EXTKEY] = [
    'title' => '(FGTCLB) Academic Projects',
    'description' => 'Academic project pages for TYPO3 with specific structured data and typed sys_categories',
    'category' => 'fe',
    'state' => 'beta',
    'version' => '0.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
            'fgtclb_educational' => '*',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

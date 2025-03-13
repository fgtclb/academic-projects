<?php

$ll = function (string $label) {
    return sprintf(
        'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:%s',
        $label
    );
};

return [
    'ctrl' => [
        'title' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,description,company_name,sector,work_location,link,slug',
        'iconfile' => 'EXT:academic_jobs/Resources/Public/Icons/tx_academicjobs_domain_model_job.svg',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_academicjobs_domain_model_job',
                'foreign_table_where' => 'AND {#tx_academicjobs_domain_model_job}.{#pid}=###CURRENT_PID### AND {#tx_academicjobs_domain_model_job}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int,required',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int,required',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.job_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$ll('tx_academicjobs_domain_model_job.jobtype.job'), 1],
                    [$ll('tx_academicjobs_domain_model_job.jobtype.sidejob'), 2],
                    [$ll('tx_academicjobs_domain_model_job.jobtype.thesis'), 3],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
            ],

        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.job_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'default' => '',
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.job_description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim,required',
            ],

        ],
        'employment_start_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.employment_start_date',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date,required',
                'default' => null,
            ],
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                        ],
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'image',
                        'tablenames' => 'tx_academicjobs_domain_model_job',
                        'table_local' => 'sys_file',
                    ],
                    'maxitems' => 1,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),

        ],
        'company_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.company_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'default' => '',
            ],
        ],
        'sector' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.sector',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'default' => '',
            ],
        ],
        'employment_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.employment_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$ll('tx_academicjobs_domain_model_job.employment_type.fulltime'), 1],
                    [$ll('tx_academicjobs_domain_model_job.employment_type.parttime'), 2],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
            ],
        ],
        'work_location' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.work_location',
            'description' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.work_location.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
            ],
        ],
        'link' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.link',
            'description' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.link.description',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.slug',
            'description' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.slug.description',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title', 'company_name'],
                    'fieldSeparator' => '-',
                    'replacements' => [
                        '/' => '',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInPid',
            ],

        ],
        'contact' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.contact',
            'description' => 'LLL:EXT:academic_jobs/Resources/Private/Language/locallang.xlf:tx_academicjobs_domain_model_job.contact.description',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academicjobs_domain_model_contact',
                'foreign_field' => 'job',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],

        ],

    ],
    'palettes' => [
        'general' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general',
            'showitem' => '
                type,
            ',
        ],
        'name' => [
            'showitem' => '
                title,
                employment_type,
                --linebreak--,
                description,
                --linebreak--,
                employment_start_date,
                --linebreak--,
                work_location,
                --linebreak--,
                link,

            ',
        ],
        'company' => [
            'showitem' => '
                company_name,
                sector,
                --linebreak--,
                contact,
            ',
        ],
        'slug' => [
            'showitem' => '
                slug,
            ',
        ],
        'hidden' => [
            'showitem' => '
                hidden;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:field.default.hidden,
            ',
        ],
        'language' => [
            'showitem' => '
                sys_language_uid;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:sys_language_uid_formlabel,
                l10n_parent,
            ',
        ],
        'access' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access',
            'showitem' => '
                starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
                endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
            ',
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;name,
                    image,
                    --palette--;;company,
                    --palette--;;slug,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
    ],
];

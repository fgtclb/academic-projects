<?php

declare(strict_types=1);

namespace FGTCLB\AcademicProjects\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use FGTCLB\AcademicProjects\Domain\Collection\CategoryCollection;
use FGTCLB\AcademicProjects\Domain\Enumeration\CategoryTypes;
use FGTCLB\AcademicProjects\Domain\Model\EducationalCategory;
use FGTCLB\AcademicProjects\Exception\Domain\CategoryExistException;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CategoryRepository
{
    protected ConnectionPool $connection;

    public function __construct()
    {
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);
    }

    /**
     * @throws DBALException
     * @throws Exception
     * @throws CategoryExistException
     */
    public function findByType(int $pageId, CategoryTypes $type): CategoryCollection
    {
        $db = $this->connection
            ->getQueryBuilderForTable('sys_category');
        $statement = $db
            ->select(
                'sys_category.uid',
                'sys_category.type',
                'sys_category.title'
            )
            ->from('sys_category')
            ->join(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                'sys_category.uid=mm.uid_local'
            )
            ->join(
                'mm',
                'pages',
                'pages',
                'mm.uid_foreign=pages.uid'
            )
            ->where(
                $db->expr()->eq(
                    'sys_category.type',
                    $db->createNamedParameter((string)$type)
                ),
                $db->expr()->eq(
                    'mm.tablenames',
                    $db->createNamedParameter('pages')
                ),
                $db->expr()->eq(
                    'mm.fieldname',
                    $db->createNamedParameter('categories')
                ),
                $db->expr()->eq(
                    'pages.uid',
                    $db->createNamedParameter($pageId, Connection::PARAM_INT)
                ),
            );
        $attributes = new CategoryCollection();

        foreach ($statement->executeQuery()->fetchAllAssociative() as $attribute) {
            if (in_array($attribute['type'], CategoryTypes::getConstants())) {
                $attributes->attach(
                    new EducationalCategory(
                        $attribute['uid'],
                        CategoryTypes::cast($attribute['type']),
                        $attribute['title']
                    )
                );
            }
        }
        return $attributes;
    }

    /**
     * @throws DBALException
     * @throws Exception
     * @throws CategoryExistException
     */
    public function findAllByPageId(int $pageId): CategoryCollection
    {
        $db = $this->connection
            ->getQueryBuilderForTable('sys_category');
        $statement = $db
            ->select(
                'sys_category.uid',
                'sys_category.type',
                'sys_category.title'
            )
            ->from('sys_category')
            ->join(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                'sys_category.uid=mm.uid_local'
            )
            ->join(
                'mm',
                'pages',
                'pages',
                'mm.uid_foreign=pages.uid'
            )
            ->where(
                $db->expr()->neq(
                    'sys_category.type',
                    $db->createNamedParameter('')
                ),
                $db->expr()->eq(
                    'mm.tablenames',
                    $db->createNamedParameter('pages')
                ),
                $db->expr()->eq(
                    'mm.fieldname',
                    $db->createNamedParameter('categories')
                ),
                $db->expr()->eq(
                    'pages.uid',
                    $db->createNamedParameter($pageId, Connection::PARAM_INT)
                ),
            );

        $attributes = new CategoryCollection();

        foreach ($statement->executeQuery()->fetchAllAssociative() as $attribute) {
            if (in_array($attribute['type'], CategoryTypes::getConstants())) {
                $attributes->attach(
                    new EducationalCategory(
                        $attribute['uid'],
                        CategoryTypes::cast($attribute['type']),
                        $attribute['title']
                    )
                );
            }
        }
        return $attributes;
    }

    /**
     * @throws CategoryExistException
     * @throws DBALException
     * @throws Exception
     */
    public function findAll(): CategoryCollection
    {
        $db = $this->connection
            ->getQueryBuilderForTable('sys_category');
        $statement = $db
            ->select(
                'sys_category.uid',
                'sys_category.type',
                'sys_category.title'
            )
            ->from('sys_category')
            ->where(
                $db->expr()->neq(
                    'sys_category.type',
                    $db->createNamedParameter('')
                )
            );

        $attributes = new CategoryCollection();

        foreach ($statement->executeQuery()->fetchAllAssociative() as $attribute) {
            if (in_array($attribute['type'], CategoryTypes::getConstants())) {
                $attributes->attach(
                    new EducationalCategory(
                        $attribute['uid'],
                        CategoryTypes::cast($attribute['type']),
                        $attribute['title']
                    )
                );
            }
        }
        return $attributes;
    }

    public function getByDatabaseFields(
        int $uid,
        string $table = 'tt_content',
        string $field = 'pi_flexform'
    ): CategoryCollection {
        $db = $this->connection
            ->getQueryBuilderForTable('sys_category');
        $statement = $db
            ->select(
                'sys_category.uid',
                'sys_category.type',
                'sys_category.title'
            )
            ->from('sys_category')
            ->join(
                'sys_category',
                'sys_category_record_mm',
                'sys_category_record_mm',
                'sys_category.uid=sys_category_record_mm.uid_local'
            )
            ->join(
                'sys_category_record_mm',
                $table,
                $table,
                sprintf('sys_category_record_mm.uid_foreign=%s.uid', $table)
            )
            ->groupBy('sys_category.uid')
            ->where(
                $db->expr()->neq(
                    'sys_category.type',
                    $db->createNamedParameter('')
                ),
                $db->expr()->eq(
                    'sys_category_record_mm.tablenames',
                    $db->createNamedParameter($table)
                ),
                $db->expr()->eq(
                    'sys_category_record_mm.fieldname',
                    $db->createNamedParameter($field)
                ),
                $db->expr()->eq(
                    'sys_category_record_mm.uid_foreign',
                    $db->createNamedParameter($uid, Connection::PARAM_INT)
                )
            );

        $attributes = new CategoryCollection();

        foreach ($statement->executeQuery()->fetchAllAssociative() as $attribute) {
            if (in_array($attribute['type'], CategoryTypes::getConstants())) {
                $attributes->attach(
                    new EducationalCategory(
                        $attribute['uid'],
                        CategoryTypes::cast($attribute['type']),
                        $attribute['title']
                    )
                );
            }
        }
        return $attributes;
    }

    /**
     * @throws CategoryExistException
     * @throws DBALException
     * @throws Exception
     */
    public function findChildren(int $uid): ?CategoryCollection
    {
        $db = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $statement = $db
            ->select('uid', 'title', 'type')
            ->from('sys_category')
            ->where(
                $db->expr()->eq('parent', $db->createNamedParameter($uid, Connection::PARAM_INT))
            );
        $children = new CategoryCollection();

        $result = $statement->executeQuery()->fetchAllAssociative();

        foreach ($result as $child) {
            if (in_array($child['type'], CategoryTypes::getConstants())) {
                $children->attach(
                    new EducationalCategory(
                        $child['uid'],
                        CategoryTypes::cast($child['type']),
                        $child['title']
                    )
                );
            }
        }
        return $children;
    }
}
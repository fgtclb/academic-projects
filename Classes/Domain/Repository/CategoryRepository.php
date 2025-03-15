<?php

declare(strict_types=1);

namespace FGTCLB\AcademicProjects\Domain\Repository;

use FGTCLB\AcademicProjects\Collection\CategoryCollection;
use FGTCLB\AcademicProjects\Domain\Model\Category;
use FGTCLB\AcademicProjects\Domain\Model\Project;
use FGTCLB\AcademicProjects\Enumeration\CategoryTypes;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

class CategoryRepository
{
    public function __construct(
        protected ConnectionPool $connectionPool,
        protected PageRepository $pageRepository
    ) {}

    public function findByType(
        int $pageId,
        CategoryTypes $type
    ): CategoryCollection {
        $queryBuilder = $this->buildQueryBuilder();

        $result = $queryBuilder
            ->select('sys_category.*')
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
                $this->categoryTypeCondition($queryBuilder),
                $this->siteDefaultLanguageCondition($queryBuilder),
                $queryBuilder->expr()->eq(
                    'sys_category.type',
                    $queryBuilder->createNamedParameter((string)$type)
                ),
                $queryBuilder->expr()->eq(
                    'mm.tablenames',
                    $queryBuilder->createNamedParameter('pages')
                ),
                $queryBuilder->expr()->eq(
                    'mm.fieldname',
                    $queryBuilder->createNamedParameter('categories')
                ),
                $queryBuilder->expr()->eq(
                    'pages.uid',
                    $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT)
                ),
            )->executeQuery();

        $categories = new CategoryCollection();

        if ($result->rowCount() === 0) {
            return $categories;
        }

        foreach ($result->fetchAllAssociative() as $row) {
            $category = $this->buildCategoryObjectFromArray($row);
            $categories->attach($category);
        }

        return $categories;
    }

    public function findAllByPageId(int $pageId): CategoryCollection
    {
        $queryBuilder = $this->buildQueryBuilder();

        $result = $queryBuilder
            ->select('sys_category.*')
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
                $this->categoryTypeCondition($queryBuilder),
                $this->siteDefaultLanguageCondition($queryBuilder),
                $queryBuilder->expr()->eq(
                    'mm.tablenames',
                    $queryBuilder->createNamedParameter('pages')
                ),
                $queryBuilder->expr()->eq(
                    'mm.fieldname',
                    $queryBuilder->createNamedParameter('categories')
                ),
                $queryBuilder->expr()->eq(
                    'pages.uid',
                    $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT)
                ),
            )->executeQuery();

        $categories = new CategoryCollection();

        if ($result->rowCount() === 0) {
            return $categories;
        }

        foreach ($result->fetchAllAssociative() as $row) {
            $category = $this->buildCategoryObjectFromArray($row);
            $categories->attach($category);
        }

        return $categories;
    }

    public function findAll(): CategoryCollection
    {
        $queryBuilder = $this->buildQueryBuilder();

        $result = $queryBuilder
            ->select('sys_category.*')
            ->from('sys_category')
            ->where(
                $this->categoryTypeCondition($queryBuilder),
                $this->siteDefaultLanguageCondition($queryBuilder),
            )->executeQuery();

        $categories = new CategoryCollection();

        if ($result->rowCount() === 0) {
            return $categories;
        }

        foreach ($result->fetchAllAssociative() as $row) {
            $category = $this->buildCategoryObjectFromArray($row);
            $categories->attach($category);
        }

        return $categories;
    }

    /**
     * @param QueryResult<Project> $projects
     */
    public function findAllApplicable(QueryResult $projects): CategoryCollection
    {
        $queryBuilder = $this->buildQueryBuilder();

        $result = $queryBuilder
            ->select('sys_category.*')
            ->from('sys_category')
            ->where(
                $this->categoryTypeCondition($queryBuilder),
                $this->siteDefaultLanguageCondition($queryBuilder),
            )->executeQuery();

        $categories = new CategoryCollection();

        if ($result->rowCount() === 0) {
            return $categories;
        }

        // Generate aa list of all categories which are assigned to the given projects
        $applicableCategories = [];
        foreach ($projects as $project) {
            foreach ($project->getAttributes() as $attribute) {
                $applicableCategories[] = $attribute->getUid();
            }
        }

        // Disable all categories which are not assigned to any of the given projects
        foreach ($result->fetchAllAssociative() as $row) {
            $category = $this->buildCategoryObjectFromArray($row);
            if (!in_array($row['uid'], $applicableCategories)) {
                $category->setDisabled(true);
            }
            $categories->attach($category);
        }

        return $categories;
    }

    /**
     * @param array<int> $idList
     */
    public function findByUidListAndType(
        array $idList,
        CategoryTypes $categoryType
    ): CategoryCollection {
        $queryBuilder = $this->buildQueryBuilder();

        $result = $queryBuilder->select('sys_category.*')
            ->from('sys_category')
            ->where(
                $this->categoryTypeCondition($queryBuilder),
                $this->siteDefaultLanguageCondition($queryBuilder),
                $queryBuilder->expr()->in('uid', $idList),
                $queryBuilder->expr()->eq('type', $queryBuilder->createNamedParameter((string)$categoryType))
            )->executeQuery();

        $categories = new CategoryCollection();

        if ($result->rowCount() === 0) {
            return $categories;
        }

        foreach ($result->fetchAllAssociative() as $row) {
            $category = $this->buildCategoryObjectFromArray($row);
            $categories->attach($category);
        }

        return $categories;
    }

    public function getByDatabaseFields(
        int $uid,
        string $table = 'tt_content',
        string $field = 'pi_flexform'
    ): CategoryCollection {
        $queryBuilder = $this->buildQueryBuilder();

        $result = $queryBuilder
            ->select('sys_category.*')
            ->distinct()
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
            ->where(
                $this->categoryTypeCondition($queryBuilder),
                $this->siteDefaultLanguageCondition($queryBuilder),
                $queryBuilder->expr()->eq(
                    'sys_category_record_mm.tablenames',
                    $queryBuilder->createNamedParameter($table)
                ),
                $queryBuilder->expr()->eq(
                    'sys_category_record_mm.fieldname',
                    $queryBuilder->createNamedParameter($field)
                ),
                $queryBuilder->expr()->eq(
                    'sys_category_record_mm.uid_foreign',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                )
            )->executeQuery();

        $categories = new CategoryCollection();

        if ($result->rowCount() === 0) {
            return $categories;
        }

        foreach ($result->fetchAllAssociative() as $row) {
            $category = $this->buildCategoryObjectFromArray($row);
            $categories->attach($category);
        }

        return $categories;
    }

    public function findParent(int $parent): ?Category
    {
        $queryBuilder = $this->buildQueryBuilder();

        $result = $queryBuilder
            ->select('sys_category.*')
            ->from('sys_category')
            ->where(
                $this->categoryTypeCondition($queryBuilder),
                $this->siteDefaultLanguageCondition($queryBuilder),
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($parent, Connection::PARAM_INT)
                )
            )
            ->setMaxResults(1)
            ->executeQuery();

        if ($result->rowCount() === 0) {
            return null;
        }

        $row = $result->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return $this->buildCategoryObjectFromArray($row);
    }

    public function findChildren(int $uid): ?CategoryCollection
    {
        $queryBuilder = $this->buildQueryBuilder();

        $result = $queryBuilder
            ->select('sys_category.*')
            ->from('sys_category')
            ->where(
                $this->categoryTypeCondition($queryBuilder),
                $this->siteDefaultLanguageCondition($queryBuilder),
                $queryBuilder->expr()->eq(
                    'parent',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                ),
                $this->categoryTypeCondition($queryBuilder)
            )->executeQuery();

        $childCategories = new CategoryCollection();

        if ($result->rowCount() === 0) {
            return $childCategories;
        }

        foreach ($result->fetchAllAssociative() as $row) {
            $category = $this->buildCategoryObjectFromArray($row);
            $childCategories->attach($category);
        }

        return $childCategories;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function buildCategoryObjectFromArray(array $row): Category
    {
        $row = $this->pageRepository->getLanguageOverlay('sys_category', $row) ?? $row;

        return new Category(
            $row['uid'],
            $row['parent'],
            $row['title'],
            $row['type']
        );
    }

    /**
     * General check to exclude all category records, which are not related to projects
     */
    private function categoryTypeCondition(QueryBuilder $queryBuilder): string
    {
        return $queryBuilder->expr()->in(
            'sys_category.type',
            array_map(function (string $value) {
                return '\'' . $value . '\'';
            }, array_values(CategoryTypes::getConstants()))
        );
    }

    /**
     * General check to exclude all translated category records
     */
    private function siteDefaultLanguageCondition(QueryBuilder $queryBuilder): string
    {
        $defaultLanguageUid = $GLOBALS['TYPO3_REQUEST']
            ->getAttribute('site')
            ->getDefaultLanguage()
            ->getLanguageId();

        return $queryBuilder->expr()->in(
            'sys_category.sys_language_uid',
            [$defaultLanguageUid, -1]
        );
    }

    private function buildQueryBuilder(): QueryBuilder
    {
        return $this->connectionPool->getQueryBuilderForTable('sys_category');
    }
}

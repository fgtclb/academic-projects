<?php

declare(strict_types=1);

namespace FGTCLB\AcademicProjects\Domain\Collection;

use ArrayAccess;
use Countable;
use FGTCLB\AcademicProjects\Domain\Enumeration\CategoryTypes;
use FGTCLB\AcademicProjects\Domain\Model\AcademicCategory;
use FGTCLB\AcademicProjects\Exception\Domain\CategoryExistException;
use Iterator;
use TYPO3\CMS\Core\Type\Exception\InvalidEnumerationValueException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @implements Iterator<int, AcademicCategory>
 */
class CategoryCollection implements Countable, Iterator, ArrayAccess
{
    /**
     * @var AcademicCategory[]
     */
    protected array $container = [];

    /**
     * @var array<string, AcademicCategory[]>
     */
    protected array $typeSortedContainer = [
        CategoryTypes::TYPE_COMPETENCE_FIELD => [],
        CategoryTypes::TYPE_COOPERATION => [],
        CategoryTypes::TYPE_DEPARTMENT => [],
        CategoryTypes::TYPE_FUNDING_PARTNER => [],
    ];

    public function current(): AcademicCategory|false
    {
        return current($this->container);
    }

    public function next(): void
    {
        next($this->container);
    }

    public function key(): string|int|null
    {
        return key($this->container);
    }

    public function valid(): bool
    {
        return current($this->container) !== false;
    }

    public function rewind(): void
    {
        reset($this->container);
    }

    public function count(): int
    {
        return count($this->container);
    }

    /**
     * @throws CategoryExistException
     */
    public function attach(AcademicCategory $category): void
    {
        if (in_array($category, $this->container, true)) {
            throw new CategoryExistException(
                'Category already defined in container.',
                1678979375329
            );
        }
        $this->container[] = $category;
        $this->typeSortedContainer[(string)$category->getType()][] = $category;
    }

    /**
     * @return array<string, AcademicCategory[]>
     */
    public function getAllAttributesByType(): array
    {
        return $this->typeSortedContainer;
    }

    /**
     * @param CategoryTypes|string $type
     * @return Iterator<int, AcademicCategory>|Countable
     */
    public function getAttributesByType(CategoryTypes|string $type): Iterator|Countable
    {
        if (!array_key_exists((string)$type, $this->typeSortedContainer)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Category type "%s" must type of "%s"',
                    $type,
                    CategoryTypes::class
                ),
                1683633304209
            );
        }
        return new class($this->typeSortedContainer[(string)$type]) implements Iterator, Countable, \JsonSerializable {
            /**
             * @var AcademicCategory[]
             */
            private array $container;

            /**
             * @param AcademicCategory[] $attributes
             */
            public function __construct(array $attributes)
            {
                $this->container = $attributes;
            }
            public function current(): AcademicCategory|false
            {
                return current($this->container);
            }

            public function next(): void
            {
                next($this->container);
            }

            public function key(): string|int|null
            {
                return key($this->container);
            }

            public function valid(): bool
            {
                return current($this->container) !== false;
            }

            public function rewind(): void
            {
                reset($this->container);
            }

            public function count(): int
            {
                return count($this->container);
            }

            public function jsonSerialize(): mixed
            {
                $values = [];
                foreach ($this->container as $category) {
                    $values[] = $category->getUid();
                }
                return $values;
            }
        };
    }

    /**
     * @param array<int|string, mixed> $arguments
     * @throws InvalidEnumerationValueException
     */
    public function __call(string $name, array $arguments): Iterator|Countable
    {
        $lowerName = GeneralUtility::camelCaseToLowerCaseUnderscored($name);
        $enum = new CategoryTypes($lowerName);

        return $this->getAttributesByType($enum);
    }

    public function offsetExists(mixed $offset): bool
    {
        if (!is_string($offset)) {
            return false;
        }
        $lowerName = GeneralUtility::camelCaseToLowerCaseUnderscored($offset);
        try {
            $enum = new CategoryTypes($lowerName);
            return true;
        } catch (InvalidEnumerationValueException $e) {
            return false;
        }
    }

    /**
     * @throws InvalidEnumerationValueException
     */
    public function offsetGet(mixed $offset): Iterator|false|Countable
    {
        if (!is_string($offset)) {
            return false;
        }
        $lowerName = GeneralUtility::camelCaseToLowerCaseUnderscored($offset);
        $enum = new CategoryTypes($lowerName);

        return $this->getAttributesByType($enum);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \InvalidArgumentException(
            'Method should never be called',
            1683214236549
        );
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \InvalidArgumentException(
            'Method should never be called',
            1683214246022
        );
    }
}

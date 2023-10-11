<?php

declare(strict_types=1);

namespace FGTCLB\AcademicProjects\Domain\Model;

use FGTCLB\AcademicProjects\Domain\Collection\CategoryCollection;
use FGTCLB\AcademicProjects\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Model class for the page type "project"
 */
class Project extends AbstractEntity
{
    protected int $doktype;

    protected string $title = '';

    /** @var ObjectStorage<FileReference> */
    protected $media;

    protected string $projectTitle = '';

    protected string $shortDescription = '';

    protected ?\DateTime $startDate = null;

    protected ?\DateTime $endDate = null;

    protected float $budget = 0;

    protected string $funders = '';

    protected ?CategoryCollection $attributes = null;

    public function getPid(): int
    {
        return $this->pid;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function getDoktype(): int
    {
        return $this->doktype;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMedia(): ObjectStorage
    {
        return $this->media;
    }

    public function getProjectTitle(): string
    {
        return $this->projectTitle;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function getBudget(): float
    {
        return $this->budget;
    }

    public function getFunders(): string
    {
        return $this->funders;
    }

    public function getAttributes(): ?CategoryCollection
    {
        return GeneralUtility::makeInstance(CategoryRepository::class)->findAllByPageId($this->uid);
    }
}

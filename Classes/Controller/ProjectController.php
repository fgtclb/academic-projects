<?php

declare(strict_types=1);

namespace FGTCLB\AcademicProjects\Controller;

use FGTCLB\AcademicProjects\Domain\Repository\CategoryRepository;
use FGTCLB\AcademicProjects\Domain\Repository\ProjectRepository;
use FGTCLB\AcademicProjects\Factory\DemandFactory;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ProjectController extends ActionController
{
    public function __construct(
        protected readonly ProjectRepository $projectRepository,
        protected readonly CategoryRepository $categoryRepository,
        protected readonly DemandFactory $demandFactory
    ) {
    }

    /**
     * @param array<string, mixed>|null $demand
     * @return ResponseInterface
     */
    public function listAction(?array $demand = null): ResponseInterface
    {
        \TYPO3\CMS\Core\Utility\DebugUtility::debug($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']);
        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);

        // With version TYPO3 v12 the access to the content object renderer has changed
        // @see https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ApiOverview/RequestLifeCycle/RequestAttributes/CurrentContentObject.html
        if (version_compare($versionInformation->getVersion(), '12.0.0', '>=')) {
            $contentObjectRenderer = $this->request->getAttribute('currentContentObject');
        } else {
            $contentObjectRenderer = $this->configurationManager->getContentObject();
        }

        /** @var ContentObjectRenderer $contentObjectRenderer */
        $contentElementData = $contentObjectRenderer->data;

        $demandObject = $this->demandFactory->createDemandObject(
            $demand,
            $this->settings,
            $contentElementData
        );

        $projects = $this->projectRepository->findByDemand($demandObject);
        $categories = $this->categoryRepository->findAllApplicable($projects);

        $assignedValues = [
            'projects' => $projects,
            'demand' => $demandObject,
            'categories' => $categories,
        ];

        $this->view->assignMultiple($assignedValues);

        return $this->htmlResponse();
    }
}

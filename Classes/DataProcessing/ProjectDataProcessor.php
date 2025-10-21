<?php

namespace Wtl\HioTypo3ConnectorWtl\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Wtl\HioTypo3Connector\Domain\Repository\ProjectRepository;


class ProjectDataProcessor implements DataProcessorInterface
{
    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $fieldName = $processorConfiguration['fieldName'] ?? '';
        $as = $processorConfiguration['as'] ?? 'featuredProject';
        $projectUid = $processedData['data'][$fieldName] ?? null;

        if ($projectUid) {
            $project = $this->projectRepository->findByUid($projectUid);
            $processedData[$as] = $project;
        } else {
            $processedData[$as] = null;
        }

        return $processedData;
    }
}

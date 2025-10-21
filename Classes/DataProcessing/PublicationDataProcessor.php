<?php

namespace Wtl\HioTypo3ConnectorWtl\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Wtl\HioTypo3Connector\Domain\Repository\PublicationRepository;


class PublicationDataProcessor implements DataProcessorInterface
{
    public function __construct(private PublicationRepository $publicationRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $fieldName = $processorConfiguration['fieldName'] ?? '';
        $as = $processorConfiguration['as'] ?? 'featuredPublication';
        $publicationUid = $processedData['data'][$fieldName] ?? null;

        if ($publicationUid) {
            $publication = $this->publicationRepository->findByUid($publicationUid);
            $processedData[$as] = $publication;
        } else {
            $processedData[$as] = null;
        }

        return $processedData;
    }
}

<?php

namespace Wtl\HioTypo3ConnectorWtl\Backend\Preview;

use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use Wtl\HioTypo3Connector\Domain\Repository\PublicationRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FeaturedPublicationPreviewRenderer extends StandardContentPreviewRenderer
{
    protected ?PublicationRepository $publicationRepository = null;

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        if ($this->publicationRepository === null) {
            $this->publicationRepository = GeneralUtility::makeInstance(PublicationRepository::class);
        }

        $otherContentPreview = parent::renderPageModulePreviewContent($item);

        $row = $item->getRecord();
        $uid = $row['tx_hiotypo3connectorwtl_featured_publication'];

        if (!$uid) {
            return $otherContentPreview;
        }

        $publication = $this->publicationRepository->findByUid($uid);

        if (!$publication) {
            return $otherContentPreview;
        }

        return $otherContentPreview . '<br /><p>' . htmlspecialchars($publication->getTitle()) . '</p>';

    }
}

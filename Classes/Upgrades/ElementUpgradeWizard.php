<?php

namespace Xima\XmFormcycle\Upgrades;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('xmFormcycle_migrateElements')]
class ElementUpgradeWizard implements UpgradeWizardInterface
{
    public function getTitle(): string
    {
        return 'Formcycle element upgrade wizard';
    }

    public function getDescription(): string
    {
        return 'Upgrades old xm_formcycle content elements to the new structure';
    }

    public function executeUpdate(): bool
    {
        return false;
    }

    /**
     * @throws Exception
     */
    public function updateNecessary(): bool
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $elements = $qb->select('*')
            ->from('tt_content')
            ->where($qb->expr()->and(
                $qb->expr()->eq('CType', $qb->createNamedParameter('list')),
                $qb->expr()->eq('list_type', $qb->createNamedParameter('Xmformcycle'))
            ))
            ->executeQuery()
            ->fetchAllAssociative();

        return (bool)count($elements);
    }

    public function getPrerequisites(): array
    {
        return ['database up-to-date'];
    }
}

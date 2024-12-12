<?php

namespace Xima\XmFormcycle\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('xmFormcycle_flexformMigration')]
readonly class FlexformMigrationWizard implements UpgradeWizardInterface, RepeatableInterface
{
    public function __construct(
        private FlexFormService $flexFormService,
    ) {
    }

    public function getTitle(): string
    {
        return 'Formcycle Flexform Migration';
    }

    public function getDescription(): string
    {
        return 'Migrate the flexform of the content elements to the new structure';
    }

    public function executeUpdate(): bool
    {
        $contentElements = $this->getContentElements();

        foreach ($contentElements as $contentElement) {
            $piFlexform = $this->flexFormService->convertFlexFormContentToArray($contentElement['pi_flexform']);

            $uid = $contentElement['uid'];
            $flexformValues = [
                'tx_xmformcycle_redirect_success' => $piFlexform['settings']['xf']['siteok'] ?? null,
                'tx_xmformcycle_redirect_error' => $piFlexform['settings']['xf']['siteerror'] ?? null,
                'tx_xmformcycle_integration_mode' => $piFlexform['settings']['xf']['integrationMode'] ?? null,
                'tx_xmformcycle_is_jquery' => $piFlexform['settings']['xf']['useFcjQuery'] ?? null,
                'tx_xmformcycle_is_jquery_ui' => $piFlexform['settings']['xf']['useFcjQueryUi'] ?? null,
                'tx_xmformcycle_additional_params' => $piFlexform['settings']['xf']['useFcUrlParams'] ?? null,
            ];

            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
            $queryBuilder->update('tt_content')
                ->set('pi_flexform', '')
                ->where(
                    $queryBuilder->expr()->eq('uid', $uid)
                );

            foreach ($flexformValues as $key => $value) {
                if ($value !== null) {
                    $queryBuilder->set($key, $value);
                }
            }

            $queryBuilder->executeStatement();
        }

        return true;
    }

    protected function getContentElements(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $qb->getRestrictions()->removeAll();
        return $qb->select('uid', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $qb->expr()->eq(
                    'CType',
                    $qb->createNamedParameter('formcycle', Connection::PARAM_STR)
                )
            )
            ->andWhere(
                $qb->expr()->neq(
                    'pi_flexform',
                    $qb->createNamedParameter('', Connection::PARAM_STR)
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function updateNecessary(): bool
    {
        return (bool)$this->getContentElements();
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }
}

<?php

namespace Xima\XmFormcycle\Service;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use Xima\XmFormcycle\Error\FormcycleConnectionException;

final readonly class FormImportService
{
    const TABLE_NAME = 'tx_xmformcycle_domain_model_form';

    public function __construct(private readonly FormcycleServiceFactory $formcycleServiceFactory, private readonly ConnectionPool $connectionPool, private readonly SiteFinder $siteFinder)
    {
    }

    public function import(): array
    {
        $sites = $this->siteFinder->getAllSites();

        $returnInfo = [];
        foreach ($sites as $site) {
            $returnInfo[$site->getIdentifier()] = $this->importForSite($site);
        }

        return $returnInfo;
    }

    public function importForSite(\TYPO3\CMS\Core\Site\Entity\Site $site): array
    {
        $importInfo = [
            'success' => true,
            'errors' => [],
            'new' => 0,
            'updated' => 0,
            'deleted' => 0,
        ];
        $formcycleService = $this->formcycleServiceFactory->createFromSite($site);
        try {
            $formsToImport = $formcycleService->loadAvailableFormsFromRemoteServer();
        } catch (FormcycleConnectionException $e) {
            $importInfo['success'] = false;
            $importInfo['errors'][] = $e->getMessage();
            return $importInfo;
        }

        $storagePid = $site->getSettings()->get('formcycle.storagePid') ?? false;

        if (!$storagePid) {
            $importInfo['success'] = false;
            $importInfo['errors'][] = 'No storagePid defined for site "' . $site->getIdentifier() . '"';
            return $importInfo;
        }

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_NAME);
        $existingFormRows = $queryBuilder->select('*')
        ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($storagePid, ParameterType::INTEGER))
            )
            ->executeQuery();

        // compare forms to import with existing
        $formsToDeleteCmd = [];
        $existingFormUidMapping = [];
        while ($existingForm = $existingFormRows->fetchAssociative()) {
            $keys = array_column($formsToImport, 'id');
            $key = array_search($existingForm['id'], $keys, true);

            if ($key !== false) {
                $existingFormUidMapping[$formsToImport[$key]['id']] = $existingForm['uid'];
                continue;
            }
            $formsToDeleteCmd[self::TABLE_NAME][$existingForm['uid']]['delete'] = 1;
            $importInfo['deleted']++;
        }

        // delete existing forms that are not available in import-data (anymore)
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $formsToDeleteCmd);
        $dataHandler->process_cmdmap();
        if ($dataHandler->errorLog !== []) {
            $importInfo['success'] = false;
            $importInfo['errors'] = $dataHandler->errorLog;
        }

        // import new forms and update existing ones
        $data = [];
        foreach ($formsToImport as $form) {
            if ($existingFormUidMapping[$form['id']] ?? false) {
                $uid = $existingFormUidMapping[$form['id']];
                $importInfo['updated']++;
            } else {
                $uid = StringUtility::getUniqueId('NEW');
                $importInfo['new']++;
            }
            $form['pid'] = $storagePid;
            $data[self::TABLE_NAME][$uid] = $form;
        }
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($data, []);
        $dataHandler->process_datamap();

        if ($dataHandler->errorLog !== []) {
            $importInfo['success'] = false;
            $importInfo['errors'] = array_merge($importInfo['errors'], $dataHandler->errorLog);
        }

        return $importInfo;
    }
}

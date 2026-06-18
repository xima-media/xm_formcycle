<?php

declare(strict_types=1);

namespace Xima\XmFormcycle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Core\Bootstrap;
use Xima\XmFormcycle\Service\FormImportService;

#[AsCommand(
    name: 'formcycle:import-forms',
    description: 'Import formcycle forms.',
)]
final class ImportFormsCommand extends Command
{
    public function __construct(private readonly FormImportService $formImportService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('Import available forms from formcycle server.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Bootstrap::initializeBackendAuthentication();
        $io = new SymfonyStyle($input, $output);
        $io->title('Importing Formcycle forms for all sites...');

        $importInfo = $this->formImportService->import();

        $errors = false;
        foreach ($importInfo as $siteIdentifier => $siteInfo) {
            if ($siteInfo['success']) {
                $io->success('Forms have been imported successfully for site "' . $siteIdentifier . '"');
                $io->text([
                    'new: ' . $siteInfo['new'],
                    'updated: ' . $siteInfo['updated'],
                    'deleted: ' . $siteInfo['deleted'],
                ]);
            } else {
                $io->error('Errors during import for site "' . $siteIdentifier . '"');
                foreach ($siteInfo['errors'] as $error) {
                    $io->text($error);
                }

                $errors = true;
            }
        }

        return $errors ? Command::FAILURE : Command::SUCCESS;
    }
}

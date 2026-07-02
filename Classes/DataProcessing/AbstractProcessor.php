<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Dto\IntegrationMode;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;
use Xima\XmFormcycle\Error\FormcycleConnectionException;
use Xima\XmFormcycle\Service\FormcycleService;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

abstract class AbstractProcessor implements DataProcessorInterface
{
    protected ElementSettings $settings;

    protected FormcycleService $formcycleService;

    public function __construct(private readonly FormcycleServiceFactory $formcycleServiceFactory)
    {
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        // construct element settings
        $this->settings = ElementSettings::createFromContentElement($cObj);

        // override form-id from request params
        $params = $cObj->getRequest()->getAttribute('routing');
        $formId = $params->get('xfc_form');

        if (!empty($formId)) {
            $this->settings->formId = (string)$formId;
        }

        try {
            $this->formcycleService = $this->formcycleServiceFactory->createFromPageUid($cObj->data['pid']);
        } catch (FormcycleConfigurationException $e) {
            return $this->withError($processedData, 'configuration', (int)$e->getCode());
        } catch (FormcycleConnectionException $e) {
            return $this->withError($processedData, 'connection', (int)$e->getCode());
        } catch (\Exception $e) {
            return $this->withError($processedData, 'unknown', (int)$e->getCode());
        }

        // check if integration mode is set
        if ($this->settings->integrationMode === IntegrationMode::Default) {
            $this->settings->integrationMode = $this->formcycleService->getDefaultIntegrationMode();
        }

        // check if concrete processor should be used
        if ($this->settings->integrationMode->forDataProcessing() !== $this->getIntegrationMode()) {
            return $processedData;
        }

        return $this->subProcess($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
    }

    /**
     * @param array<string, mixed> $processedData
     * @return array<string, mixed>
     */
    private function withError(array $processedData, string $type, int $code): array
    {
        $processedData['mode'] = 'error';
        $processedData['error'] = ['type' => $type, 'code' => $code];

        return $processedData;
    }

    abstract protected function getIntegrationMode(): IntegrationMode;

    abstract public function subProcess(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array;
}

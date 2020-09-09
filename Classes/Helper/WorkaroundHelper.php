<?php

namespace Xima\XmFormcycle\Helper;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Workaround to get Flexform by UID. Useful for AJAX-Calls.
 *
 * @see http://blog.wolf-whv.de/flexform-daten-eines-plugins-ajax-abfragen
 */
class WorkaroundHelper extends Repository
{

    /**
     * Find Flexform settings by UID.
     *
     * @param $uid
     *
     * @return array
     */
    public function findFlexformDataByUid($uid)
    {
        if (empty($uid)) {
            return [];
        }

        $uid = (int)$uid;

        $query = $this->createQuery();
        $query->statement('SELECT pi_flexform from tt_content where list_type="xmformcycle_xmformcycle" and uid = ' . $uid);
        $pages = $query->execute(true);
        $xml = simplexml_load_string($pages[0]['pi_flexform']);
        $flexformData = [];

        foreach ($xml->data->sheet as $sheet) {
            foreach ($sheet->language->field as $field) {
                $flexformData['xf'][str_replace(
                    'settings.xf.',
                    '',
                    (string)$field->attributes()
                )] = (string)$field->value;
            }
        }

        return $flexformData;
    }

}

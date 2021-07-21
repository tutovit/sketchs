<?

namespace Discounts\Logic\PushNotification;

use Discounts\Logic\Action\Action;
use Discounts\Logic\City\City;
use Discounts\Logic\IBlock\Property;
use Discounts\Logic\PushNotification\PushSubscribersTable;
use Discounts\Logic\Settings;
use Discounts\Services\Logger;

/**
 *
 * Класс для рассылки пуш-уведомлений подписчикам, которые хранятся в БД
 * Class Notification
 * @package Discounts\Logic\PushNotification
 */

class Notification
{
    public static function SendNewAction($id, $bBigPreview)
    {
        $arFilter = [];

        $arAction = Action::getActionFormatted([
            'ID' => $id
        ]);

        if (!in_array('all', $arAction['city'])) {
            if ($arAction['city']) {
                $arCities = \Discounts\Logic\City\City::GetCityByCodes($arAction['city']);
                $arCitiesID = array_column($arCities, 'ID');
            }

            $arFilter = [
                'UF_CITY_ID' => $arCitiesID,
            ];
        }

        $rsRecipients = PushSubscribersTable::getList(['filter' => $arFilter]);

        while ($arRecipient = $rsRecipients->fetch()) {

            $arCity = City::getById($arRecipient['UF_CITY_ID']);

            if ($bBigPreview) {
                $arParams = [
                    'title' => 'Новая акция в г. ' . $arCity['NAME'],
                    'body' => 'Акция «' . $arAction['name'] . '»',
                    'image' => 'https://api.****.ru' . $arAction['image_mobile'],
                    'icon' => 'https://api.****.ru' . $arAction['image_mobile'],
                    'click_action' => 'https://****.ru/id' . $id,
                    'to' => $arRecipient['UF_CODE'],
                ];
            } else {
                $arParams = [
                    'title' => 'Новая акция в г. ' . $arCity['NAME'],
                    'body' => 'Акция «' . $arAction['NAME'] . '»',
                    'icon' => 'https://api.****.ru' . $arAction['image_mobile'],
                    'click_action' => 'https://****.ru/id' . $arAction['ID'],
                    'to' => $arRecipient['UF_CODE'],
                ];
            }

            Notification::sendRequest($arParams);
        }
    }


    public static function sendRequest($arParams) {

        $url = 'https://fcm.googleapis.com/fcm/send';
        $YOUR_API_KEY = ''; // Server key

        $request_body = [
            'to' => $arParams['to'],
            'notification' => [
                'title' => $arParams['title'],
                'body' => $arParams['body'],
                'icon' => $arParams['icon'],
                'click_action' => $arParams['click_action'],
            ],
        ];

        if ($arParams['image']) {
            $request_body['notification']['image'] = $arParams['image'];
        }

        $fields = json_encode($request_body);

        $request_headers = [
            'Content-Type: application/json',
            'Authorization: key=' . $YOUR_API_KEY,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Опубликована новая акция, уведомляем через определённое время подписавшихся на обновления о новых акциях
     *
     * @param $id
     */
    public static function ActionNotify($id) {
        \Bitrix\Main\Loader::includeModule('iblock');
        $id = intval($id);

        $arFilter = array(
            'ID' => $id,
            'PROPERTY_PROVIDER_CHECK_VALUE' => 'Y',
            '!PROPERTY_PROVIDER' => false,
            'PROPERTY_INVISIBLE' => false,
            '!PROPERTY_CATEGORIES' => false,
            'PROPERTY_LK_STATUS' => [Settings::ACTION_LK_STATUS_ACTIVE, Settings::ACTION_LK_STATUS_ARCHIVE, Settings::ACTION_LK_STATUS_BAN],
        );

        $arAction = \CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'PROPERTY_LK_STATUS_PUBLISHED'])->Fetch();

        if ($arAction) {
            if ($arAction['PROPERTY_LK_STATUS_PUBLISHED_VALUE'] == 'PUBLIC') {
                Notification::SendNewAction($id, true);
            }
        }
    }


}
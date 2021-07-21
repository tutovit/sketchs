<?
namespace Discounts\Logic\PushNotification;
use \Bitrix\Main\Entity;
/**
 * Class SkidkiPushSubscribersTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UF_CITY_ID int optional
 * <li> UF_CODE string optional
 * </ul>
 *
 * @package Bitrix\Hlbd
 **/

class PushSubscribersTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_hlbd_skidki_push_subscribers';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
            ),
            'UF_CITY_ID' => array(
                'data_type' => 'integer',
            ),
            'UF_CODE' => array(
                'data_type' => 'text',
            ),
        );
    }
}
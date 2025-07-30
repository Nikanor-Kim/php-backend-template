<?php
namespace App\Helpers;
use App\Config\Config;
use App\Utils\Logger;

// require_once 'Logger.php';
// require_once 'Config.php';

class AmoCrmHelper
{
    public static function getFieldValue($customFields, $field)
    {
        foreach ($customFields as $customField) {
            if ($customField['id'] == $field['id']) {
                switch ($field['type']) {
                    case 'checkbox':
                        return true;
                    case 'numeric':
                        return floatval(str_replace(',', '.', $customField['values'][0]['value'])) ?? 0;
                    default:
                        return $customField['values'][0]['value'] ?? '';
                }
            }
        }
        switch ($field['type']) {
            case 'checkbox':
                return false;
            case 'numeric':
                return 0;
            default:
                return null;
        }
    }
    
}

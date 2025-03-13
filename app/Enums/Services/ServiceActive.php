<?php
namespace App\Enums\Services;
enum ServiceActive:int{
    case ACTIVE = 1;
    case INACTIVE = 0;
    public static function values(){
        return array_column(self::cases(), 'value');
    }
}
?>

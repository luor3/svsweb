<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    
    /**
     * Attributes that are mass assignable
     *
     * @var array 
     */
    protected $fillable = [
        'name', 'value'
    ];
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public static function get($name) 
    {
        if (empty(trim($name))) {
            return '';
        }
        
        $db = \DB::connection()->getPdo();
        $query = $db->prepare("SELECT `value` FROM `settings` WHERE TRIM(`name`) = ? LIMIT 1;");
        $query->execute([trim($name)]);
        $setting = $query->fetch(\PDO::FETCH_COLUMN);
        
        return $setting;
    }
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public static function set($name, $value) 
    {
        if (empty(trim($name))) {
            return false;
        }
        
        $setting = self::where([['name', '=', trim($name)]])->first();
        $currentTime = date("Y-m-d h:i:s", time());
        if (!empty($setting)) {
            $setting->value = $value;
            $setting->updated_at = $currentTime;
            $setting->save();
            $setting = $setting->value;
        } else {
            $db = \DB::connection()->getPdo();
            $query = $db->prepare("INSERT INTO `settings` SET `value` = :value, `name` = :name, `created_at` = NOW(), `updated_at` = NOW();");
            $setting = $query->execute(['name' => $name, 'value' => $value]);
        }
        
        return $setting;
    }
}

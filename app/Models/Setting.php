<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group_name', 'description', 'type'];
    
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value, $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group_name' => $group]
        );
    }

    public static function getGroup($group)
    {
        return static::where('group_name', $group)->pluck('value', 'key')->toArray();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hwavina\HwaMeta\Libraries\MetaTools;

class CustomerMeta extends Model
{
    use HasFactory;

    /**
     * The table associated with the entity.
     *
     * @var string
     */
    protected $table = 'customer_metas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'meta_key',
        'meta_value',
    ];

    public $timestamps = false;

    /**
     * Check instance and reset it
     *
     * @param string $type
     * @return MetaTools $_instance
     */
    protected static function metaTools($type = 'customer'){
        return new MetaTools($type);
    }

    /**
     * Check instance and reset it
     *
     * @param $object_id
     * @param $meta_key
     * @param bool $single
     * @return array|false|mixed $_instance
     */
    public static function _get($object_id, $meta_key, $single = true){
        $meta = self::metaTools();

        return $meta->getMeta($object_id, $meta_key, $single);
    }

    /**
     * Check instance and reset it
     *
     * @param $object_id
     * @param $meta_key
     * @param $meta_value
     * @param bool $unique
     * @return false|int $_instance
     */
    public static function _add($object_id, $meta_key, $meta_value, $unique = false){
        $meta = self::metaTools();

        return $meta->addMeta($object_id, $meta_key, $meta_value, $unique);
    }

    /**
     * Check instance and reset it
     *
     * @param $object_id
     * @param $meta_key
     * @param string $meta_value
     * @param bool $delete_all
     * @return bool $_instance
     */
    public static function _delete($object_id, $meta_key, $meta_value = '', $delete_all = false){
        $meta = self::metaTools();

        return $meta->deleteMeta($object_id, $meta_key, $meta_value, $delete_all);
    }

    /**
     * Check instance and reset it
     *
     * @param $object_id
     * @param $meta_key
     * @param $meta_value
     * @param string $prev_value
     * @return bool|int $_instance
     */
    public static function _update($object_id, $meta_key, $meta_value, $prev_value = ''){
        $meta = self::metaTools();

        return $meta->updateMeta($object_id, $meta_key, $meta_value, $prev_value);
    }

}

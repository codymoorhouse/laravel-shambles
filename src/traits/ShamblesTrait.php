<?php
namespace PDERAS\Shambles;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ShamblesTrait
{
    /**
     *  The default hash size length to use.
     * 
     * @var int
     */
    protected static $defaultHashSize = 36;

    /**
     *  The default route key to use.
     * 
     * @var int
     */
    protected static $defaultRouteKey = 'hash';

    /**
     * Automatically add a hash when a new instance of the calling class is made.
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (!$this->hash) {
            $this->hash = self::generateHash();
        }
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return config('shambles.route_key', static::$defaultRouteKey);
    }

    /**
     * Save a new model and return the instance.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public static function create(array $attributes = [])
    {
        if (!isset($attributes['hash'])) {
            $attributes['hash'] = self::generateHash();
        }
        $model = static::query()->create($attributes);
        return $model;
    }
    /**
     * Gets the model associated to the hash.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public static function findByHash(string $hash)
    {
        $model = static::query()->where('hash', $hash)->first();
        return $model;
    }
    /**
     * Gets the model associated to the hash and fails otherwise.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public static function findByHashOrFail(string $hash)
    {
        $model = static::query()->where('hash', $hash)->first();
        if (!$model) {
            throw new ModelNotFoundException();
        }
        return $model;
    }
    /**
     * Save a new model and return the instance. Allow mass-assignment.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public static function forceCreate(array $attributes = [])
    {
        if (!isset($attributes['hash'])) {
            $attributes['hash'] = self::generateHash();
        }
        $model = static::query()->forceCreate($attributes);
        return $model;
    }
    /**
     * Create and return an un-saved model instance.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public static function make(array $attributes = [])
    {
        if (!isset($attributes['hash'])) {
            $attributes['hash'] = self::generateHash();
        }
        $model = static::query()->make($attributes);
        return $model;
    }
    /**
     * Generates a hash from the given attributes.
     *
     * @return string
     */
    public static function generateHash()
    {
        $model = get_class();
        $hashSize = property_exists($model, 'hashSize') ? $model::$hashSize : config('shambles.hash_size', static::$defaultHashSize);
        return bin2hex(openssl_random_pseudo_bytes($hashSize, $crypto));
    }
}

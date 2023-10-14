<?php

namespace Core\Contracts;

use Generator;
use Modules\Setting\Models\Setting;

interface Configurable
{
    /**
     * Get the module's configurations.
     *
     * @param string|null $key
     * @return mixed
     */
    public static function config($key = '');

    /**
     * Get setting.
     *
     * @return Setting
     */
    public static function settings();

    /**
     * Get setting value.
     *
     * @param string $key
     * @return mixed
     */
    public static function setting($key);

    /**
     * Set the default config values in the database.
     *
     * @return Generator
     */
    public function setDefaultConfig(): Generator;
}

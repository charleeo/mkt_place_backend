<?php

namespace Core\Services;

use Generator;
use Modules\Setting\Facades\SettingService;
use Modules\Setting\Models\Setting;

trait Configurable
{
    /**
     * Get the module's configurations.
     *
     * @param string|null $key
     * @return mixed
     */
    public static function config($key = '')
    {
        $key = $key ? ".{$key}" : '';
        return config(static::name() . "{$key}");
    }

    /**
     * Get setting.
     *
     * @return Setting
     */
    public static function settings()
    {
        // return SettingService::getGroup(static::name()) ?? static::config();
    }

    /**
     * Get setting value.
     *
     * @param string $key
     * @return mixed
     */
    public static function setting($key)
    {
        // return SettingService::getValue($key, static::name());
    }

    /**
     * Set the default config values in the database.
     *
     * @return Generator
     */
    public function setDefaultConfig(): Generator
    {
        $defaultConfigs = $this->config('defaults');

        if (empty($defaultConfigs)) return;

        yield [
            'message' => 'Setting ' . $this->name() . '...',
            'level' => 'info'
        ];

        foreach ($defaultConfigs as $config) {
            if (!$this->repository->firstWhere('name', $config['name'])) {

                $this->store($config);

                yield [
                    'message' => $config['name'] . ' set successfully',
                    'level' => 'info'
                ];
            } else {
                yield [
                    'message' => $config['name'] . ' already exists',
                    'level' => 'error'
                ];
            }
        }
    }
}

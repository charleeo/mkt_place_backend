<?php

namespace Core\Services;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Core\Contracts\Repository;
use Core\Contracts\Service as ServiceContract;
use Core\Helper\Helper;

abstract class Service implements ServiceContract
{
    use Identifiable, Configurable, PerformsCrudOperation;

    /**
     * The Repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Create a new instance of the service.
     *
     * @param Repository $repository
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function makeApiResource(Model $model, ?string $type = null): JsonResource
    {
        $module = $this->getModule();

        $baseName = !is_null($type) ? Str::studly($type) : Str::afterLast(get_class($model), '\\');

        $resourceClass = Helper::productNamespace() . "{$module}\\Http\\Resources\\{$baseName}Resource";

        return new $resourceClass($model);
    }

    /**
     * Get the module this service belongs to.
     *
     * @return string
     */
    protected function getModule()
    {
        $module = Str::afterLast(static::class, '\\');
        return Str::beforeLast($module, 'Service');
    }

    /**
     * Dynamically handle undefined method calls.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (!method_exists($this->repository, $method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.',
                static::class,
                $method
            ));
        }

        return $this->repository->{$method}(...$parameters);
    }
}

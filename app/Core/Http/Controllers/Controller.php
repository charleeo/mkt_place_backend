<?php

namespace Core\Http\Controllers;

use BadMethodCallException;
use Core\Contracts\Service;
use Core\Events\ControllerStoreAction;
use Core\Events\ControllerUpdateAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Core\Transformers\SuccessResource;
use ReflectionClass;

abstract class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Service instance.
     *
     * @var Service
     */
    protected $service;

    /**
     * Create new instance of the controller.
     *
     * @param Service $service
     * @return void
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Get the class namespace.
     *
     * @return void
     */
    protected function namespace()
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }

    /**
     * Get the form request name for the given method.
     *
     * @param string $method
     * @return string
     */
    protected function requestClass($method)
    {
        return Str::beforeLast($this->namespace(), 'Http')
            . 'Http\\Requests\\'
            . Str::studly($method)
            . 'Request';
    }

    /**
     * Get the name of the module's base directory.
     *
     * @return string
     */
    protected function moduleBaseName()
    {
        $fileName = (new ReflectionClass($this))->getFileName();
        $fileName = dirname($fileName, 3);

        return Str::afterLast($fileName, DIRECTORY_SEPARATOR);
    }

    /**
     * Get the api resource class.
     *
     * @return string
     */
    protected function resourceClass()
    {
        return Str::beforeLast($this->namespace(), 'Http')
            . 'Http\\Resources\\'
            . $this->moduleBaseName()
            . 'Resource';
    }

    /**
     * Display a paginated list of all resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = app($this->requestClass(__FUNCTION__));

        $requestAttributes = $request->validated();

        $resources = $this->service->index($requestAttributes);

        $resourceClass = $this->resourceClass();

        $resourceCollectionClass = $resourceClass . 'Collection';

        if (class_exists($resourceCollectionClass)) {
            return new $resourceCollectionClass($resources);
        }

        return $resourceClass::collection($resources);
    }

    /**
     * Show the specified resource.
     *
     * @param string|int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = app($this->requestClass(__FUNCTION__));

        $requestAttributes = $request->validated();

        $resource = $this->service->show($id, $requestAttributes);

        $resourceClass = $this->resourceClass();

        return new $resourceClass($resource);
    }

    /**
     * Create a new Group resource  in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $action = __FUNCTION__;

        $request = app($this->requestClass($action));

        $requestAttributes = $request->validated();

        $response = $this->service->store($requestAttributes);

        ControllerStoreAction::dispatch($action, $response);

        return $response;
    }

    /**
     * Update the specified resource.
     *
     * @param string|int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $action = __FUNCTION__;

        $request = app($this->requestClass($action));

        $requestAttributes = $request->validated();

        $response = $this->service->update($id, $requestAttributes);

        ControllerUpdateAction::dispatch($action, $response);

        return new SuccessResource("Updated successfully.");
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param string|int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action = __FUNCTION__;

        $request = app($this->requestClass($action));

        $requestAttributes = $request->validated();

        $numberOfDeletedRecords = $this->service->destroy($id, $requestAttributes);

        $response = $numberOfDeletedRecords > 0 ? "Deleted successfully." : "No record was found.";

        return new SuccessResource($response);
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
        $requestClass = $this->requestClass($method);
        $indexRequestClass = $this->requestClass('index');

        if (class_exists($requestClass)) {
            $request = app($requestClass)->validated();
        } else if (class_exists($indexRequestClass)) {
            $request = app($indexRequestClass)->validated();
        }

        try {
            return $this->service->{$method}(($request ?? []), ...$parameters);
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.',
                static::class,
                $method
            ));
        }
    }
}

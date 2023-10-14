<?php

namespace Core\Helper;

use Carbon\Carbon;
use Core\Repositories\Repository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Modules\User\Models\User;
use ReflectionClass;
use RuntimeException;

class Helper
{
    /**
     * Generate random unique reference.
     *
     * @return string
     */
    public static function reference()
    {
        return Str::uuid();
    }

    /**
     * Get the base namespace of the modules.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public static function moduleNamespace()
    {
        return 'Modules\\';
    }

    /**
     * Get the base namespace of the providers.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public static function providerNamespace()
    {
        return 'Providers\\';
    }

    /**
     * Get the base namespace of the products.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public static function productNamespace()
    {
        return 'Products\\';
    }

    /**
     * Get the base namespace of the core.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public static function coreNamespace()
    {
        return 'Core\\';
    }

    /**
     * Get the organization.
     *
     * @return User
     */
    public static function getOrganization(): User
    {
        return User::oldest()->first();
    }

    /**
     * Read the content of the composer.json file.
     *
     * @return object
     */
    public static function readComposerJson()
    {
        $composerFile = file_get_contents(
            base_path('composer.json')
        );

        return json_decode($composerFile, true);
    }

    /**
     * Get the namespace of a given directory in the script path.
     *
     * @param string $dir
     * @return string
     *
     * @throws RuntimeException
     */
    public static function getNamespace($dir)
    {
        $composer = static::readComposerJson();

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                $basePath = realpath(base_path($pathChoice));

                if ($basePath === realpath(base_path($dir))) {
                    return $namespace;
                }
            }
        }

        throw new RuntimeException('The namespace could not be detected automatically.');
    }

    /**
     * Get the directory path associated with the specified namespace.
     *
     * @param string $namespace
     * @return string
     *
     * @throws RuntimeException
     */
    public static function namespacePath($namespace)
    {
        $composer = static::readComposerJson();

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespaceChoice => $path) {
            if ($namespace === $namespaceChoice) {
                return base_path($path);
            }
        }

        throw new RuntimeException('No path matches the given namespace.');
    }

    /**
     * Get the api resource name of a given model.
     *
     * @param Model|null $model
     * @return string|null
     */
    public static function getApiResource(?Model $model): ?string
    {
        if (!$model) return null;

        $baseName = Str::afterLast(get_class($model), '\\');

        return static::productNamespace() . "{$baseName}\\Http\\Resources\\{$baseName}Resource";
    }

    /**
     * Create an instance of an api resource.
     *
     * @param Model|null $model
     * @return JsonResource|null
     */
    public static function makeApiResource(?Model $model): ?JsonResource
    {
        $resourceClass = static::getApiResource($model);

        return $resourceClass ? new $resourceClass($model) : null;
    }

    /**
     * Cast boolean literal to tinyint.
     *
     * @param string $value
     * @return bool
     */
    public static function toBool($value)
    {
        return $value == 'true' ? 1 : 0;
    }

    /**
     * Convert the given xml string to an array.
     *
     * @param string $xml
     * @return array
     */
    public static function xmlToArray($xml)
    {
        $simpleXml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

        $json = json_encode($simpleXml);

        return json_decode($json, true);
    }

    /**
     * Convert the given xml string to an array.
     *
     * @param array $data
     * @return string
     * @throws Exception
     */
    public static function arrayToXml($data): string
    {
        $string = '';

        foreach ($data as $key => $value) {
            $string .= "<{$key}>";

            if (is_array($value)) {
                $string .= static::arrayToXml($value);
            } else {
                $string .= $value;
            }

            $string .= "</{$key}>";
        }

        return $string;
    }

    /**
     * Format the given date.
     *
     * @param Carbon|string|int $time
     * @return string
     */
    public static function timeFormat($time)
    {
        return Carbon::parse($time)->format('Y-d-m H:i');
    }

    /**
     * Get all the classes using the given trait.
     *
     * @param string $trait
     * @return array
     */
    public static function getClassesUsingTrait($trait)
    {
        // get user defined classes
        $definedClasses = array_filter(
            get_declared_classes(),
            function ($className) {
                return !call_user_func(
                    array(new ReflectionClass($className), 'isInternal')
                );
            }
        );

        // select only classes that use trait $trait
        return array_filter(
            $definedClasses,
            function ($className) use ($trait) {
                $traits = class_uses($className);
                return isset($traits[$trait]) && !(new ReflectionClass($className))->isAbstract();
            }
        );
    }

    /**
     * Get all classes implementing an interface.
     *
     * @param string $interfaceName
     * @return array
     */
    public static function getSubClasses(string $interfaceName)
    {
        return array_filter(
            get_declared_classes(),
            function ($className) use ($interfaceName) {
                return is_subclass_of($className, $interfaceName)
                    && !(new ReflectionClass($className))->isAbstract();
            }
        );
    }

    /**
     * Get all abstract classes implementing an interface.
     *
     * @param string $interfaceName
     * @return array
     */
    public static function getAbstractSubClasses(string $interfaceName)
    {
        return array_filter(
            get_declared_classes(),
            function ($className) use ($interfaceName) {
                return is_subclass_of($className, $interfaceName)
                    && (new ReflectionClass($className))->isAbstract();
            }
        );
    }

    /**
     * Get the morph classes of all models that are subclasses the given class.
     *
     * @param string $morphClass
     * @return array
     */
    public static function getMorphTypes(string $morphClass): array
    {
        $subClasses = interface_exists($morphClass) ? self::getSubClasses($morphClass)
            : (trait_exists($morphClass) ? self::getClassesUsingTrait($morphClass) : []);

        $morphClasses = collect($subClasses)->map(function ($model) {
            return Repository::getMorphClass($model);
        })->toArray();

        return array_values($morphClasses);
    }

    /**
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *
     * @return array<int|string, mixed>
     */
    public static function arrayMergeRecursiveDistinct(array &$array1, array &$array2): array
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}

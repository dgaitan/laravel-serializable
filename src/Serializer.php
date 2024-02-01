<?php

namespace Dgaitan\Serializable;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection as SupportCollection;

/**
 * In the practice this will convert the model to an array. And it can
 * convert a collection or only a single model.
 *
 * // It can convert data already fetched from the database
 * $data = PostSerializer::serialize($assignment); // single model instance
 * $data = PostSerializer::serialize($assignments); // collection of records
 *
 * // Or use the serializer to build the query
 * $data = PostSerializer::find(1)->serialize(); // find a record and serializer
 * $data = PostSerializer::whereStatus('active)->serialize(); // build query and serialize
 */
abstract class Serializer {

    /**
     * Self instance
     *
     * @var null|static
     */
    public static $instance = null;

    /**
     * Model reference
     *
     * @var string|null
     */
    protected null|string $model = null;

    /**
     * Query builded
     *
     * @var null|Builder
     */
    protected null|Builder $query = null;

    /**
     * Collection of models
     *
     * @var null|Collection|SupportCollection
     */
    protected null|Collection|SupportCollection $collection = null;

    /**
     * Model Instance if exists
     *
     * @var null|Model
     */
    protected null|Model $modelInstance = null;

    /**
     * Initialize the serializer
     */
    public function __construct(Model|Collection|Builder|SupportCollection|null $instance = null) {
        if ($instance instanceof Model) {
            $this->modelInstance = $instance;
        }

        if ($instance instanceof Collection || $instance instanceof SupportCollection) {
            $this->collection = $instance;
        }

        if ($instance instanceof Builder) {
            $this->collection = $instance->get();
            $this->model = get_class($instance->first());
        }

        if (!$this->model) {
            throw new Exception('Model not set for serializer');
        }

        if (!class_exists($this->model)) {
            throw new Exception('Model does not exist');
        }

        if (is_null($instance)) {
            $this->query = $this->model::query();
        }
    }

    /**
     * This should represent a single item
     *
     * @return array
     */
    public function toArray(Model $instance): array {
        return $instance->toArray();
    }

    /**
     * Serialize the data
     *
     * @return array
     */
    public function serializer(): array {
        if ($this->modelInstance) {
            return $this->toArray($this->modelInstance);
        }

        if ($this->collection) {
            return $this->collection->map(function ($item) {
                return $this->toArray($item);
            })->toArray();
        }

        return $this->query->get()->map(function ($item) {
            return $this->toArray($item);
        })->toArray();
    }

    /**
     * Serialize and convert to json
     *
     * @return string|false
     */
    public function jsonEncoder(): string|false {
        return json_encode($this->serializer());
    }

    /**
     * The idea is that we can call any method on the serializer and also
     * the Model methods.
     *
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
    public function __call($name, $arguments) {
        if ($name === 'serialize') {
            return $this->serializer();
        }

        if ($name === 'toJson') {
            return $this->jsonEncoder();
        }

        if (method_exists($this, $name)) {
            return $this->{$name}(...$arguments);
        }

        if ($name === 'find') {
            $this->modelInstance = $this->query->find($arguments[0]);
            return $this;
        }

        if ($name === 'get') {
            $this->collection = $this->query->get();
            return $this;
        }

        $this->query = $this->query->{$name}(...$arguments);
        return $this;
    }

    /**
     * Interfacing with the serializer
     *
     * @param string $name
     * @param mixed $arguments
     * @return static
     */
    public static function __callStatic($name, $arguments) {
        // If the called method is serialize, then we will create a new
        // isntance and return thte data serialized.
        if ($name === 'serialize') {
            $serializer = new static(...$arguments);
            return $serializer->serialize();
        }

        if ($name === 'toJson') {
            $serializer = new static(...$arguments);
            return $serializer->toJson();
        }

        // If the method is not serialize, then we will create a new
        // instance and passed the data.
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance->{$name}(...$arguments);
    }
}

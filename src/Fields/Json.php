<?php

namespace BonsaiCms\MetamodelJsonApi\Fields;

use JsonException;
use LaravelJsonApi\Eloquent\Fields\Attribute;

class Json extends Attribute
{
    /**
     * Create a json attribute.
     *
     * @param string $fieldName
     * @param string|null $column
     * @return Json
     */
    public static function make(string $fieldName, string $column = null): self
    {
        return new self($fieldName, $column);
    }

    /**
     * @inheritDoc
     */
    protected function assertValue($value): void
    {
        if (!is_null($value) && !$this->isJson($value)) {
            throw new \UnexpectedValueException(sprintf(
                'Expecting the value of attribute %s to be a JSON value.',
                $this->name()
            ));
        }
    }

    protected function isJson($value) {
        try {
            $encoded = json_encode($value, JSON_THROW_ON_ERROR);
            return (json_decode(
                json: $encoded,
                flags: JSON_THROW_ON_ERROR
            ) === $value);
        } catch (JsonException $e)
        {
            return false;
        }
    }
}

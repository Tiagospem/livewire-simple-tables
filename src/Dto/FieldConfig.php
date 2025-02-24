<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Dto;

use Closure;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;

final readonly class FieldConfig
{
    public function __construct(private ?Closure $callback = null, private int $numberOfParameters = 1, private string $parameterType = 'mixed') {}

    public static function fromClosure(Closure $callback): self
    {
        try {
            $reflection         = new ReflectionFunction($callback);
            $numberOfParameters = $reflection->getNumberOfParameters();
            $parameterType      = self::getFirstParameterType($reflection);
        }
        // @codeCoverageIgnoreStart
        catch (ReflectionException) {
            $numberOfParameters = 1;
            $parameterType      = 'mixed';
        }
        // @codeCoverageIgnoreEnd

        return new self($callback, $numberOfParameters, $parameterType);
    }

    public function getCallback(): ?Closure
    {
        return $this->callback;
    }

    public function toObject(): FieldData
    {
        return new FieldData($this->callback, $this->numberOfParameters, $this->parameterType);
    }

    private static function getFirstParameterType(ReflectionFunction $reflection): string
    {
        $parameters = $reflection->getParameters();

        if ([] === $parameters) {
            return 'mixed';
        }

        $firstParam = $parameters[0];
        $type       = $firstParam->getType();

        if ( ! $type instanceof ReflectionNamedType) {
            return 'mixed';
        }

        /** @var class-string|'mixed'|'scalar'|'array'|'callable'|'iterable'|'object' $typeName */
        $typeName = $type->getName();

        return $typeName;
    }
}

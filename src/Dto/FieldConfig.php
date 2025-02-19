<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Dto;

use Closure;
use Exception;
use ReflectionFunction;
use ReflectionNamedType;

final readonly class FieldConfig
{
    public function __construct(private ?Closure $callback = null, private int $numberOfParameters = 1, private string $parameterType = 'mixed') {}

    public static function fromClosure(Closure $callback): self
    {
        try {
            $reflection = new ReflectionFunction($callback);

            $numberOfParameters = $reflection->getNumberOfParameters();

            $parameterType = self::getFirstParameterType($reflection);

            return new self($callback, $numberOfParameters, $parameterType);
        } catch (Exception) {
            return new self($callback, 1, 'mixed');
        }
    }

    public function getCallback(): ?Closure
    {
        return $this->callback;
    }

    public function getNumberOfParameters(): int
    {
        return $this->numberOfParameters;
    }

    public function getParameterType(): string
    {
        return $this->parameterType;
    }

    public function toObject(): FieldData
    {
        return new FieldData($this->callback, $this->numberOfParameters, $this->parameterType);
    }

    private static function getFirstParameterType(ReflectionFunction $reflection): string
    {
        $parameters = $reflection->getParameters();

        if ($parameters === []) {
            return 'mixed';
        }

        $firstParam = $parameters[0];
        $type = $firstParam->getType();

        if (! $type instanceof ReflectionNamedType) {
            return 'mixed';
        }

        /** @var class-string|'mixed'|'scalar'|'array'|'callable'|'iterable'|'object' $typeName */
        $typeName = $type->getName();

        return $typeName;
    }
}

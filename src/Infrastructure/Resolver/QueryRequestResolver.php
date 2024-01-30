<?php

namespace App\Infrastructure\Resolver;

use App\Infrastructure\Exception\BadApiRequestException;
use ReflectionAttribute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class QueryRequestResolver implements ValueResolverInterface
{
    private const CONTEXT_DENORMALIZE = [
        'disable_type_enforcement' => true,
        'collect_denormalization_errors' => true,
    ];

    public function __construct(
        private SerializerInterface&DenormalizerInterface $serializer,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributesOfType(MapQueryString::class, ReflectionAttribute::IS_INSTANCEOF);

        if (!isset($attributes[0])) {
            return [];
        }

        $attribute = $attributes[0];

        $type = $argument->getType();

        if ($type === null) {
            throw new BadApiRequestException();
        }

        try {
            yield $this->serializer->denormalize(
                data: $request->query->all(),
                type: $type,
                context: $attribute->serializationContext + self::CONTEXT_DENORMALIZE,
            );
        } catch (ExceptionInterface $exception) {
            throw new BadApiRequestException();
        }
    }
}
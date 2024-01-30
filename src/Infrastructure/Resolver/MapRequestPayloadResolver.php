<?php

namespace App\Infrastructure\Resolver;

use App\Infrastructure\Exception\BadApiRequestException;
use ReflectionAttribute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class MapRequestPayloadResolver implements ValueResolverInterface
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
        $attributes = $argument->getAttributesOfType(MapRequestPayload::class, ReflectionAttribute::IS_INSTANCEOF);

        if (!isset($attributes[0])) {
            return [];
        }

        $attribute = $attributes[0];
        assert($attribute instanceof MapRequestPayload);

        $type = $argument->getType();

        if ($type === null) {
            throw new BadApiRequestException();
        }

        if (null === $format = $request->getContentTypeFormat()) {
            throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, 'Unsupported format.');
        }

        if ($attribute->acceptFormat && !\in_array($format, (array)$attribute->acceptFormat, true)) {
            throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, sprintf('Unsupported format, expects "%s", but "%s" given.', implode('", "', (array) $attribute->acceptFormat), $format));
        }

        $formData = $request->request->all();

        if ($formData !== []) {
            yield $this->serializer->denormalize(
                data: $formData,
                type: $type,
                context: $attribute->serializationContext + self::CONTEXT_DENORMALIZE,
            );

            return [];
        }

        try {
            yield $this->serializer->deserialize(
                data: $request->getContent(),
                type: $type,
                format: $format,
                context: $attribute->serializationContext + self::CONTEXT_DENORMALIZE,
            );
        } catch (ExceptionInterface $exception) {
            throw new BadApiRequestException();
        }
    }
}
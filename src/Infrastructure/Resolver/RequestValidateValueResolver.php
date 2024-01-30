<?php

namespace App\Infrastructure\Resolver;

use App\Infrastructure\Exception\RequestConstraintValidationException;
use ReflectionAttribute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class RequestValidateValueResolver implements ValueResolverInterface
{
    public function __construct(
        private ValueResolverInterface $valueResolver,
        private ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $validatingGroups = $this->getValidationGroups($argument);

        foreach ($this->valueResolver->resolve($request, $argument) as $value) {
            $violationList = $this->validator->validate($value, groups: $validatingGroups);

            if ($violationList->count() > 0) {
                throw RequestConstraintValidationException::createFromConstraintViolationList($violationList);
            }

            yield $value;
        }
    }

    private function getValidationGroups(ArgumentMetadata $argument): ?array
    {
        $attribute = $argument->getAttributesOfType(MapQueryString::class, ArgumentMetadata::IS_INSTANCEOF)[0]
            ?? $argument->getAttributesOfType(MapRequestPayload::class, ArgumentMetadata::IS_INSTANCEOF)[0]
            ?? null;


        if ($attribute) {
            return $attribute->validationGroups;
        }

        return null;
    }
}

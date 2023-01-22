<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable;

use Evrinoma\ContactBundle\Dto\ContactApiDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait ContactsApiDtoTrait
{
    protected array $contactsApiDto = [];

    protected static string $classContactsApiDto = ContactApiDto::class;

    public function hasContactsApiDto(): bool
    {
        return 0 !== \count($this->contactsApiDto);
    }

    public function getContactsApiDto(): array
    {
        return $this->contactsApiDto;
    }

    public function genRequestContactsApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $entities = $request->get(ContactsApiDtoInterface::CONTACTS);
            if ($entities) {
                foreach ($entities as $entity) {
                    $newRequest = $this->getCloneRequest();
                    $entity[DtoInterface::DTO_CLASS] = static::$classContactsApiDto;
                    $newRequest->request->add($entity);

                    yield $newRequest;
                }
            }
        }
    }
}

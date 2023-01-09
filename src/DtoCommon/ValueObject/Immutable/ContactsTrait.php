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
use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait ContactsTrait
{
    /**
     * @var ContactApiDtoInterface []
     */
    protected array $contactsApiDto = [];

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
            $entities = $request->get(ContactsInterface::CONTACTS);
            if ($entities) {
                foreach ($entities as $entity) {
                    $newRequest = $this->getCloneRequest();
                    $entity[DtoInterface::DTO_CLASS] = ContactApiDto::class;
                    $newRequest->request->add($entity);

                    yield $newRequest;
                }
            }
        }
    }
}

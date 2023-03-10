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
use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface as BaseContactApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait ContactApiDtoTrait
{
    protected ?BaseContactApiDtoInterface $contactApiDto = null;

    protected static string $classContactApiDto = ContactApiDto::class;

    public function genRequestContactApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $contact = $request->get(ContactApiDtoInterface::CONTACT);
            if ($contact) {
                $newRequest = $this->getCloneRequest();
                $contact[DtoInterface::DTO_CLASS] = static::$classContactApiDto;
                $newRequest->request->add($contact);

                yield $newRequest;
            }
        }
    }

    public function hasContactApiDto(): bool
    {
        return null !== $this->contactApiDto;
    }

    public function getContactApiDto(): BaseContactApiDtoInterface
    {
        return $this->contactApiDto;
    }
}

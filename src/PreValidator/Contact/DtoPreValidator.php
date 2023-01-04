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

namespace Evrinoma\ContactBundle\PreValidator\Contact;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactInvalidException;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\PreValidator\AbstractPreValidator;

class DtoPreValidator extends AbstractPreValidator implements DtoPreValidatorInterface
{
    public function onPost(DtoInterface $dto): void
    {
        $this
            ->checkTitle($dto)
            ->checkPosition($dto)
            ->checkContact($dto);
    }

    public function onPut(DtoInterface $dto): void
    {
        $this
            ->checkId($dto)
            ->checkTitle($dto)
            ->checkActive($dto)
            ->checkPosition($dto)
            ->checkContact($dto);
    }

    public function onDelete(DtoInterface $dto): void
    {
        $this
            ->checkId($dto);
    }

    private function checkPosition(DtoInterface $dto): self
    {
        /** @var ContactApiDtoInterface $dto */
        if (!$dto->hasPosition()) {
            throw new ContactInvalidException('The Dto has\'t position');
        }

        return $this;
    }

    private function checkTitle(DtoInterface $dto): self
    {
        /** @var ContactApiDtoInterface $dto */
        if (!$dto->hasTitle()) {
            throw new ContactInvalidException('The Dto has\'t title');
        }

        return $this;
    }

    private function checkActive(DtoInterface $dto): self
    {
        /** @var ContactApiDtoInterface $dto */
        if (!$dto->hasActive()) {
            throw new ContactInvalidException('The Dto has\'t active');
        }

        return $this;
    }

    private function checkContact(DtoInterface $dto): self
    {
        /* @var ContactApiDtoInterface $dto */

        return $this;
    }

    private function checkId(DtoInterface $dto): self
    {
        /** @var ContactApiDtoInterface $dto */
        if (!$dto->hasId()) {
            throw new ContactInvalidException('The Dto has\'t ID or class invalid');
        }

        return $this;
    }
}

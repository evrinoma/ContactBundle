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

namespace Evrinoma\ContactBundle\PreValidator\Group;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Group\GroupInvalidException;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\PreValidator\AbstractPreValidator;

class DtoPreValidator extends AbstractPreValidator implements DtoPreValidatorInterface
{
    public function onPost(DtoInterface $dto): void
    {
        $this
            ->checkPosition($dto)
            ->checkBrief($dto);
    }

    public function onPut(DtoInterface $dto): void
    {
        $this
            ->checkId($dto)
            ->checkPosition($dto)
            ->checkBrief($dto)
            ->checkActive($dto);
    }

    public function onDelete(DtoInterface $dto): void
    {
        $this
            ->checkId($dto);
    }

    private function checkActive(DtoInterface $dto): self
    {
        /** @var GroupApiDtoInterface $dto */
        if (!$dto->hasActive()) {
            throw new GroupInvalidException('The Dto has\'t active');
        }

        return $this;
    }

    private function checkBrief(DtoInterface $dto): self
    {
        /** @var GroupApiDtoInterface $dto */
        if (!$dto->hasBrief()) {
            throw new GroupInvalidException('The Dto has\'t brief');
        }

        return $this;
    }

    private function checkId(DtoInterface $dto): self
    {
        /** @var GroupApiDtoInterface $dto */
        if (!$dto->hasId()) {
            throw new GroupInvalidException('The Dto has\'t ID or class invalid');
        }

        return $this;
    }

    private function checkPosition(DtoInterface $dto): self
    {
        /** @var GroupApiDtoInterface $dto */
        if (!$dto->hasPosition()) {
            throw new GroupInvalidException('The Dto has\'t position');
        }

        return $this;
    }
}

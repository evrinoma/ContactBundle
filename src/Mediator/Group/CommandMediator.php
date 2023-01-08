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

namespace Evrinoma\ContactBundle\Mediator\Group;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    public function onUpdate(DtoInterface $dto, $entity): GroupInterface
    {
        /* @var $dto GroupApiDtoInterface */

        $entity
            ->setBrief($dto->getBrief())
            ->setPosition($dto->getPosition())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setActive($dto->getActive());

        return $entity;
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {
        $entity
            ->setActiveToDelete();
    }

    public function onCreate(DtoInterface $dto, $entity): GroupInterface
    {
        /* @var $dto GroupApiDtoInterface */

        $entity
            ->setBrief($dto->getBrief())
            ->setPosition($dto->getPosition())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActiveToActive();

        return $entity;
    }
}

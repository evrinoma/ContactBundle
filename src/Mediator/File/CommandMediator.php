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

namespace Evrinoma\ContactBundle\Mediator\File;

use Evrinoma\ContactBundle\Dto\FileApiDtoInterface;
use Evrinoma\ContactBundle\Model\File\FileInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    public function onUpdate(DtoInterface $dto, $entity): FileInterface
    {
        /* @var $dto FileApiDtoInterface */

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

    public function onCreate(DtoInterface $dto, $entity): FileInterface
    {
        /* @var $dto FileApiDtoInterface */

        $entity
            ->setBrief($dto->getBrief())
            ->setPosition($dto->getPosition())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActiveToActive();

        return $entity;
    }
}

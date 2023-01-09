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
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeCreatedException;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeSavedException;
use Evrinoma\ContactBundle\Manager\Contact\QueryManagerInterface as ContactQueryManagerInterface;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    private ContactQueryManagerInterface $contactQueryManager;

    public function __construct(ContactQueryManagerInterface $contactQueryManager)
    {
        $this->contactQueryManager = $contactQueryManager;
    }

    public function onUpdate(DtoInterface $dto, $entity): GroupInterface
    {
        /* @var $dto GroupApiDtoInterface */

        try {
            foreach ($entity->getContacts() as $contact) {
                $entity->removeContact($contact);
            }

            foreach ($dto->getContactsApiDto() as $contactApiDto) {
                $entity->addContact($this->contactQueryManager->proxy($contactApiDto));
            }
        } catch (\Exception $e) {
            throw new GroupCannotBeSavedException($e->getMessage());
        }

        $entity
            ->setBrief($dto->getBrief())
            ->setPosition($dto->getPosition())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setActive($dto->getActive());

        return $entity;
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {
        try {
            foreach ($entity->getContacts() as $contact) {
                $entity->removeContact($contact);
            }
        } catch (\Exception $e) {
            throw new GroupCannotBeRemovedException($e->getMessage());
        }

        $entity
            ->setActiveToDelete();
    }

    public function onCreate(DtoInterface $dto, $entity): GroupInterface
    {
        /* @var $dto GroupApiDtoInterface */

        try {
            foreach ($dto->getContactsApiDto() as $contactApiDto) {
                $entity->addContact($this->contactQueryManager->proxy($contactApiDto));
            }
        } catch (\Exception $e) {
            throw new GroupCannotBeCreatedException($e->getMessage());
        }

        $entity
            ->setBrief($dto->getBrief())
            ->setPosition($dto->getPosition())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActiveToActive();

        return $entity;
    }
}

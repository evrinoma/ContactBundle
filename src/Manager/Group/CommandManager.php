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

namespace Evrinoma\ContactBundle\Manager\Group;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeCreatedException;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeSavedException;
use Evrinoma\ContactBundle\Exception\Group\GroupInvalidException;
use Evrinoma\ContactBundle\Exception\Group\GroupNotFoundException;
use Evrinoma\ContactBundle\Factory\Group\FactoryInterface;
use Evrinoma\ContactBundle\Mediator\Group\CommandMediatorInterface;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;
use Evrinoma\ContactBundle\Repository\Group\GroupRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private GroupRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;

    public function __construct(ValidatorInterface $validator, GroupRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     *
     * @throws GroupInvalidException
     * @throws GroupCannotBeCreatedException
     * @throws GroupCannotBeSavedException
     */
    public function post(GroupApiDtoInterface $dto): GroupInterface
    {
        $group = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $group);

        $errors = $this->validator->validate($group);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new GroupInvalidException($errorsString);
        }

        $this->repository->save($group);

        return $group;
    }

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     *
     * @throws GroupInvalidException
     * @throws GroupNotFoundException
     * @throws GroupCannotBeSavedException
     */
    public function put(GroupApiDtoInterface $dto): GroupInterface
    {
        try {
            $group = $this->repository->find($dto->idToString());
        } catch (GroupNotFoundException $e) {
            throw $e;
        }

        $this->mediator->onUpdate($dto, $group);

        $errors = $this->validator->validate($group);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new GroupInvalidException($errorsString);
        }

        $this->repository->save($group);

        return $group;
    }

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @throws GroupCannotBeRemovedException
     * @throws GroupNotFoundException
     */
    public function delete(GroupApiDtoInterface $dto): void
    {
        try {
            $group = $this->repository->find($dto->idToString());
        } catch (GroupNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $group);
        try {
            $this->repository->remove($group);
        } catch (GroupCannotBeRemovedException $e) {
            throw $e;
        }
    }
}

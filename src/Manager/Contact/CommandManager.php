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

namespace Evrinoma\ContactBundle\Manager\Contact;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeCreatedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeSavedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactInvalidException;
use Evrinoma\ContactBundle\Exception\Contact\ContactNotFoundException;
use Evrinoma\ContactBundle\Factory\Contact\FactoryInterface;
use Evrinoma\ContactBundle\Mediator\Contact\CommandMediatorInterface;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;
use Evrinoma\ContactBundle\Repository\Contact\ContactRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private ContactRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;

    public function __construct(ValidatorInterface $validator, ContactRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     *
     * @throws ContactInvalidException
     * @throws ContactCannotBeCreatedException
     * @throws ContactCannotBeSavedException
     */
    public function post(ContactApiDtoInterface $dto): ContactInterface
    {
        $contact = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $contact);

        $errors = $this->validator->validate($contact);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new ContactInvalidException($errorsString);
        }

        $this->repository->save($contact);

        return $contact;
    }

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     *
     * @throws ContactInvalidException
     * @throws ContactNotFoundException
     * @throws ContactCannotBeSavedException
     */
    public function put(ContactApiDtoInterface $dto): ContactInterface
    {
        try {
            $contact = $this->repository->find($dto->idToString());
        } catch (ContactNotFoundException $e) {
            throw $e;
        }

        $this->mediator->onUpdate($dto, $contact);

        $errors = $this->validator->validate($contact);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new ContactInvalidException($errorsString);
        }

        $this->repository->save($contact);

        return $contact;
    }

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @throws ContactCannotBeRemovedException
     * @throws ContactNotFoundException
     */
    public function delete(ContactApiDtoInterface $dto): void
    {
        try {
            $contact = $this->repository->find($dto->idToString());
        } catch (ContactNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $contact);
        try {
            $this->repository->remove($contact);
        } catch (ContactCannotBeRemovedException $e) {
            throw $e;
        }
    }
}

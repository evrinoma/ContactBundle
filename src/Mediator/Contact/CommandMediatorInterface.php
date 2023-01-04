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

namespace Evrinoma\ContactBundle\Mediator\Contact;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeCreatedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeSavedException;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;

interface CommandMediatorInterface
{
    /**
     * @param ContactApiDtoInterface $dto
     * @param ContactInterface       $entity
     *
     * @return ContactInterface
     *
     * @throws ContactCannotBeSavedException
     */
    public function onUpdate(ContactApiDtoInterface $dto, ContactInterface $entity): ContactInterface;

    /**
     * @param ContactApiDtoInterface $dto
     * @param ContactInterface       $entity
     *
     * @throws ContactCannotBeRemovedException
     */
    public function onDelete(ContactApiDtoInterface $dto, ContactInterface $entity): void;

    /**
     * @param ContactApiDtoInterface $dto
     * @param ContactInterface       $entity
     *
     * @return ContactInterface
     *
     * @throws ContactCannotBeSavedException
     * @throws ContactCannotBeCreatedException
     */
    public function onCreate(ContactApiDtoInterface $dto, ContactInterface $entity): ContactInterface;
}

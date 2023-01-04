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
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactInvalidException;
use Evrinoma\ContactBundle\Exception\Contact\ContactNotFoundException;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;

interface CommandManagerInterface
{
    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     *
     * @throws ContactInvalidException
     */
    public function post(ContactApiDtoInterface $dto): ContactInterface;

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     *
     * @throws ContactInvalidException
     * @throws ContactNotFoundException
     */
    public function put(ContactApiDtoInterface $dto): ContactInterface;

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @throws ContactCannotBeRemovedException
     * @throws ContactNotFoundException
     */
    public function delete(ContactApiDtoInterface $dto): void;
}

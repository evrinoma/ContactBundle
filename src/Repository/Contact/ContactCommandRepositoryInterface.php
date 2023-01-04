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

namespace Evrinoma\ContactBundle\Repository\Contact;

use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeSavedException;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;

interface ContactCommandRepositoryInterface
{
    /**
     * @param ContactInterface $contact
     *
     * @return bool
     *
     * @throws ContactCannotBeSavedException
     */
    public function save(ContactInterface $contact): bool;

    /**
     * @param ContactInterface $contact
     *
     * @return bool
     *
     * @throws ContactCannotBeRemovedException
     */
    public function remove(ContactInterface $contact): bool;
}

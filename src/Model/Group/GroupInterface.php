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

namespace Evrinoma\ContactBundle\Model\Group;

use Doctrine\Common\Collections\Collection;
use Evrinoma\AddressBundle\Model\Address\AddressInterface;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;
use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\BriefInterface;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;

interface GroupInterface extends ActiveInterface, IdInterface, BriefInterface, CreateUpdateAtInterface
{
    /**
     * @return Collection|ContactInterface[]
     */
    public function getContacts(): Collection;

    public function addContact(ContactInterface $contact): GroupInterface;

    public function removeContact(ContactInterface $contact): GroupInterface;

    public function getAddress(): AddressInterface;

    public function resetAddress(): GroupInterface;

    public function setAddress(AddressInterface $address): GroupInterface;
}

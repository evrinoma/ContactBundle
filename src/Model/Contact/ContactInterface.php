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

namespace Evrinoma\ContactBundle\Model\Contact;

use Doctrine\Common\Collections\Collection;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;
use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;
use Evrinoma\UtilsBundle\Entity\PositionInterface;
use Evrinoma\UtilsBundle\Entity\TitleInterface;

interface ContactInterface extends ActiveInterface, CreateUpdateAtInterface, IdInterface, TitleInterface, PositionInterface
{
    /**
     * @return Collection|GroupInterface[]
     */
    public function getGroups(): Collection;

    public function addGroup(GroupInterface $group): ContactInterface;

    public function removeGroup(GroupInterface $group): ContactInterface;
}

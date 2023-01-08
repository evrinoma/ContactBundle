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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;
use Evrinoma\UtilsBundle\Entity\PositionTrait;
use Evrinoma\UtilsBundle\Entity\TitleTrait;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractContact implements ContactInterface
{
    use ActiveTrait;
    use CreateUpdateAtTrait;
    use IdTrait;
    use PositionTrait;
    use TitleTrait;

    /**
     * @var ArrayCollection|GroupInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Evrinoma\ContactBundle\Model\Group\GroupInterface")
     * @ORM\JoinTable(
     *     joinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * @return Collection|GroupInterface[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(GroupInterface $group): ContactInterface
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function removeGroup(GroupInterface $group): ContactInterface
    {
        $this->groups->removeElement($group);

        return $this;
    }
}

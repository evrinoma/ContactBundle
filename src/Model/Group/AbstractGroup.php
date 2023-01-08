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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\BriefTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;
use Evrinoma\UtilsBundle\Entity\PositionTrait;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractGroup implements GroupInterface
{
    use ActiveTrait;
    use BriefTrait;
    use CreateUpdateAtTrait;
    use IdTrait;
    use PositionTrait;

    /**
     * @var ArrayCollection|ContactInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Evrinoma\ContactBundle\Model\Contact\ContactInterface")
     * @ORM\JoinTable(
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")}
     * )
     */
    protected $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    /**
     * @return Collection|ContactInterface[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(ContactInterface $contact): GroupInterface
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }

    public function removeContact(ContactInterface $contact): GroupInterface
    {
        $this->contacts->removeElement($contact);

        return $this;
    }
}

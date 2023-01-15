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
use Evrinoma\MailBundle\Model\Mail\MailInterface;
use Evrinoma\PhoneBundle\Model\Phone\PhoneInterface;
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

    /**
     * @var ArrayCollection|PhoneInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Evrinoma\PhoneBundle\Model\Phone\PhoneInterface")
     * @ORM\JoinTable(
     *     name="e_contact_contacts_phones",
     *     joinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="phone_id", referencedColumnName="id")}
     * )
     */
    protected $phones;

    /**
     * @var ArrayCollection|MailInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Evrinoma\MailBundle\Model\Mail\MailInterface")
     * @ORM\JoinTable(
     *     name="e_contact_contacts_mails",
     *     joinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="mail_id", referencedColumnName="id")}
     * )
     */
    protected $mails;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->mails = new ArrayCollection();
        $this->phones = new ArrayCollection();
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

    /**
     * @return Collection|PhoneInterface[]
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(PhoneInterface $phone): ContactInterface
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
        }

        return $this;
    }

    public function removePhone(PhoneInterface $phone): ContactInterface
    {
        $this->phones->removeElement($phone);

        return $this;
    }

    /**
     * @return Collection|MailInterface[]
     */
    public function getMails(): Collection
    {
        return $this->mails;
    }

    public function addMail(MailInterface $mail): ContactInterface
    {
        if (!$this->mails->contains($mail)) {
            $this->mails[] = $mail;
        }

        return $this;
    }

    public function removeMail(MailInterface $mail): ContactInterface
    {
        $this->mails->removeElement($mail);

        return $this;
    }
}

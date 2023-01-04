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

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeSavedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactNotFoundException;
use Evrinoma\ContactBundle\Exception\Contact\ContactProxyException;
use Evrinoma\ContactBundle\Mediator\Contact\QueryMediatorInterface;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;

trait ContactRepositoryTrait
{
    private QueryMediatorInterface $mediator;

    /**
     * @param ContactInterface $contact
     *
     * @return bool
     *
     * @throws ContactCannotBeSavedException
     * @throws ORMException
     */
    public function save(ContactInterface $contact): bool
    {
        try {
            $this->persistWrapped($contact);
        } catch (ORMInvalidArgumentException $e) {
            throw new ContactCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param ContactInterface $contact
     *
     * @return bool
     */
    public function remove(ContactInterface $contact): bool
    {
        return true;
    }

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ContactNotFoundException
     */
    public function findByCriteria(ContactApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilderWrapped($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $contacts = $this->mediator->getResult($dto, $builder);

        if (0 === \count($contacts)) {
            throw new ContactNotFoundException('Cannot find contact by findByCriteria');
        }

        return $contacts;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     *
     * @throws ContactNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): ContactInterface
    {
        /** @var ContactInterface $contact */
        $contact = $this->findWrapped($id);

        if (null === $contact) {
            throw new ContactNotFoundException("Cannot find contact with id $id");
        }

        return $contact;
    }

    /**
     * @param string $id
     *
     * @return ContactInterface
     *
     * @throws ContactProxyException
     * @throws ORMException
     */
    public function proxy(string $id): ContactInterface
    {
        $contact = $this->referenceWrapped($id);

        if (!$this->containsWrapped($contact)) {
            throw new ContactProxyException("Proxy doesn't exist with $id");
        }

        return $contact;
    }
}

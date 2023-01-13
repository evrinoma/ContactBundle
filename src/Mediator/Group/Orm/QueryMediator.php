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

namespace Evrinoma\ContactBundle\Mediator\Group\Orm;

use Evrinoma\AddressBundle\Repository\AliasInterface as AddressAliasInterface;
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Mediator\Group\QueryMediatorInterface;
use Evrinoma\ContactBundle\Repository\AliasInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractQueryMediator;
use Evrinoma\UtilsBundle\Mediator\Orm\QueryMediatorTrait;
use Evrinoma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

class QueryMediator extends AbstractQueryMediator implements QueryMediatorInterface
{
    use QueryMediatorTrait;

    protected static string $alias = AliasInterface::GROUP;

    /**
     * @param DtoInterface          $dto
     * @param QueryBuilderInterface $builder
     *
     * @return mixed
     */
    public function createQuery(DtoInterface $dto, QueryBuilderInterface $builder): void
    {
        $alias = $this->alias();

        /** @var $dto GroupApiDtoInterface */
        if ($dto->hasId()) {
            $builder
                ->andWhere($alias.'.id = :id')
                ->setParameter('id', $dto->getId());
        }

        if ($dto->hasBrief()) {
            $builder
                ->andWhere($alias.'.brief like :brief')
                ->setParameter('brief', '%'.$dto->getBrief().'%');
        }

        if ($dto->hasActive()) {
            $builder
                ->andWhere($alias.'.active = :active')
                ->setParameter('active', $dto->getActive());
        }

        $aliasContacts = AliasInterface::CONTACTS;
        $builder
            ->leftJoin($alias.'.contacts', $aliasContacts)
            ->addSelect($aliasContacts);

        if ($dto->hasContactApiDto()) {
            if ($dto->getContactApiDto()->hasActive()) {
                $builder
                    ->andWhere($aliasContacts.'.active = :contactActive')
                    ->setParameter('contactActive', $dto->getContactApiDto()->getActive());
            }
            if ($dto->getContactApiDto()->hasTitle()) {
                $builder
                    ->andWhere($aliasContacts.'.title like :contactTitle')
                    ->setParameter('contactTitle', '%'.$dto->getContactApiDto()->getTitle().'%');
            }
        }

        $aliasAddress = AddressAliasInterface::ADDRESS;
        $builder
            ->leftJoin($alias.'.address', $aliasAddress)
            ->addSelect($aliasAddress);
    }
}

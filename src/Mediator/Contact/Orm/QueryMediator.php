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

namespace Evrinoma\ContactBundle\Mediator\Contact\Orm;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Mediator\Contact\QueryMediatorInterface;
use Evrinoma\ContactBundle\Repository\AliasInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\MailBundle\Repository\AliasInterface as MailAliasInterface;
use Evrinoma\PhoneBundle\Repository\AliasInterface as PhoneAliasInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractQueryMediator;
use Evrinoma\UtilsBundle\Mediator\Orm\QueryMediatorTrait;
use Evrinoma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

class QueryMediator extends AbstractQueryMediator implements QueryMediatorInterface
{
    use QueryMediatorTrait;

    protected static string $alias = AliasInterface::CONTACT;

    /**
     * @param DtoInterface          $dto
     * @param QueryBuilderInterface $builder
     *
     * @return mixed
     */
    public function createQuery(DtoInterface $dto, QueryBuilderInterface $builder): void
    {
        $alias = $this->alias();
        /** @var $dto ContactApiDtoInterface */
        if ($dto->hasId()) {
            $builder
                ->andWhere($alias.'.id = :id')
                ->setParameter('id', $dto->getId());
        }

        if ($dto->hasTitle()) {
            $builder
                ->andWhere($alias.'.title like :title')
                ->setParameter('title', '%'.$dto->getTitle().'%');
        }

        if ($dto->hasPosition()) {
            $builder
                ->andWhere($alias.'.position = :position')
                ->setParameter('position', $dto->getPosition());
        }

        if ($dto->hasActive()) {
            $builder
                ->andWhere($alias.'.active = :active')
                ->setParameter('active', $dto->getActive());
        }

        $aliasGroups = AliasInterface::GROUPS;
        $builder
            ->leftJoin($alias.'.groups', $aliasGroups)
            ->addSelect($aliasGroups);

        if ($dto->hasGroupApiDto()) {
            if ($dto->getGroupApiDto()->hasActive()) {
                $builder
                    ->andWhere($aliasGroups.'.active = :groupActive')
                    ->setParameter('groupActive', $dto->getGroupApiDto()->getActive());
            }
            if ($dto->getGroupApiDto()->hasBrief()) {
                $builder
                    ->andWhere($aliasGroups.'.brief = :groupBrief')
                    ->setParameter('groupBrief', $dto->getGroupApiDto()->getBrief());
            }
        }

        $aliasPhones = PhoneAliasInterface::PHONES;
        $builder
            ->leftJoin($alias.'.phones', $aliasPhones)
            ->addSelect($aliasPhones);

        $aliasMails = MailAliasInterface::MAILS;
        $builder
            ->leftJoin($alias.'.mails', $aliasMails)
            ->addSelect($aliasMails);
    }
}

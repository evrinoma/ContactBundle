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

namespace Evrinoma\ContactBundle\Mediator\Contact;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

interface QueryMediatorInterface
{
    /**
     * @return string
     */
    public function alias(): string;

    /**
     * @param ContactApiDtoInterface $dto
     * @param QueryBuilderInterface  $builder
     *
     * @return mixed
     */
    public function createQuery(ContactApiDtoInterface $dto, QueryBuilderInterface $builder): void;

    /**
     * @param ContactApiDtoInterface $dto
     * @param QueryBuilderInterface  $builder
     *
     * @return array
     */
    public function getResult(ContactApiDtoInterface $dto, QueryBuilderInterface $builder): array;
}

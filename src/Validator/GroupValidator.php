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

namespace Evrinoma\ContactBundle\Validator;

use Evrinoma\ContactBundle\Entity\Group\BaseGroup;
use Evrinoma\UtilsBundle\Validator\AbstractValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GroupValidator extends AbstractValidator
{
    /**
     * @var string|null
     */
    protected static ?string $entityClass = BaseGroup::class;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator, static::$entityClass);
    }
}

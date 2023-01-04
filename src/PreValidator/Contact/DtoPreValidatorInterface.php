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

namespace Evrinoma\ContactBundle\PreValidator\Contact;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactInvalidException;

interface DtoPreValidatorInterface
{
    /**
     * @param ContactApiDtoInterface $dto
     *
     * @throws ContactInvalidException
     */
    public function onPost(ContactApiDtoInterface $dto): void;

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @throws ContactInvalidException
     */
    public function onPut(ContactApiDtoInterface $dto): void;

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @throws ContactInvalidException
     */
    public function onDelete(ContactApiDtoInterface $dto): void;
}

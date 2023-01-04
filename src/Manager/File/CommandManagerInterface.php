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

namespace Evrinoma\ContactBundle\Manager\File;

use Evrinoma\ContactBundle\Dto\FileApiDtoInterface;
use Evrinoma\ContactBundle\Exception\File\FileCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\File\FileInvalidException;
use Evrinoma\ContactBundle\Exception\File\FileNotFoundException;
use Evrinoma\ContactBundle\Model\File\FileInterface;

interface CommandManagerInterface
{
    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     *
     * @throws FileInvalidException
     */
    public function post(FileApiDtoInterface $dto): FileInterface;

    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     *
     * @throws FileInvalidException
     * @throws FileNotFoundException
     */
    public function put(FileApiDtoInterface $dto): FileInterface;

    /**
     * @param FileApiDtoInterface $dto
     *
     * @throws FileCannotBeRemovedException
     * @throws FileNotFoundException
     */
    public function delete(FileApiDtoInterface $dto): void;
}

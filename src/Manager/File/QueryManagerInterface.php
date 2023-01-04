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
use Evrinoma\ContactBundle\Exception\File\FileNotFoundException;
use Evrinoma\ContactBundle\Exception\File\FileProxyException;
use Evrinoma\ContactBundle\Model\File\FileInterface;

interface QueryManagerInterface
{
    /**
     * @param FileApiDtoInterface $dto
     *
     * @return array
     *
     * @throws FileNotFoundException
     */
    public function criteria(FileApiDtoInterface $dto): array;

    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     *
     * @throws FileNotFoundException
     */
    public function get(FileApiDtoInterface $dto): FileInterface;

    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     *
     * @throws FileProxyException
     */
    public function proxy(FileApiDtoInterface $dto): FileInterface;
}

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

namespace Evrinoma\ContactBundle\Entity\Contact;

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\ContactBundle\Model\Contact\AbstractContact;

/**
 * @ORM\Table(name="e_contact_contact")
 * @ORM\Entity
 */
class BaseContact extends AbstractContact
{
}

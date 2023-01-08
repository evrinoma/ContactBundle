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

namespace Evrinoma\ContactBundle\Serializer;

interface GroupInterface
{
    public const API_POST_CONTACT = 'API_POST_CONTACT';
    public const API_PUT_CONTACT = 'API_PUT_CONTACT';
    public const API_GET_CONTACT = 'API_GET_CONTACT';
    public const API_CRITERIA_CONTACT = self::API_GET_CONTACT;
    public const APP_GET_BASIC_CONTACT = 'APP_GET_BASIC_CONTACT';

    public const API_POST_CONTACT_GROUP = 'API_POST_CONTACT_GROUP';
    public const API_PUT_CONTACT_GROUP = 'API_PUT_CONTACT_GROUP';
    public const API_GET_CONTACT_GROUP = 'API_GET_CONTACT_GROUP';
    public const API_CRITERIA_CONTACT_GROUP = self::API_GET_CONTACT_GROUP;
    public const APP_GET_BASIC_CONTACT_GROUP = 'APP_GET_BASIC_CONTACT_GROUP';
}

<?php

namespace ShveiderDtoTest\DTO\Module4;

use ShveiderDto\AbstractReflectionTransfer;

class MyReflectionTransfer extends AbstractReflectionTransfer
{
    protected string $name;

    private ?string $firstName;

    private \DateTime $dateTime;

    protected ?\DateTime $fullDate;

    protected ?string $city;

    protected ?string $country;

    protected ?string $zip;

    protected ?string $street;

    protected ?int $streetNumber;
}
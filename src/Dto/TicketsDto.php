<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TicketsDto
{
    /** @var TicketDto[] */
    #[Assert\Valid]
    public array $tickets;

}
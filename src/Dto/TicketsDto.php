<?php

namespace App\Dto;

use App\Enum\TicketCompareType;
use App\Model\Ticket;
use Exception;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use ValueError;

class TicketsDto
{
    /** @var TicketDto[] */
    #[Assert\Valid]
    public array $tickets = [];

    #[Assert\Type(
        type: TicketCompareType::class,
        message: 'The compare type must be a valid TicketCompareType enum.'
    )]
    public TicketCompareType $compareType = TicketCompareType::DEPARTURE;

    /**
     * @return $this
     *
     * @throws ValueError
     */
    public function setCompareType(string|TicketCompareType $compareType): self
    {
        if (is_string($compareType)) {
            $this->compareType = TicketCompareType::from($compareType);
        } elseif ($compareType instanceof TicketCompareType) {
            $this->compareType = $compareType;
        }

        return $this;
    }

    /**
     * @return Ticket[]
     *
     * @throws Exception
     */
    #[Ignore]
    public function toClass(): array
    {
        $tickets = [];

        foreach ($this->tickets as $ticket) {
            $tickets[] = $ticket->toClass();
        }

        return $tickets;
    }
}

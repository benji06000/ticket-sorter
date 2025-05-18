<?php

namespace App\Service;

use App\Dto\TicketDto;
use App\Dto\TicketsDto;
use DateTimeImmutable;
use Exception;

class SorterService
{
    /**
     * @throws Exception
     *
     * @return TicketDto[]
     */
    public function sortByDate(TicketsDto $dto): array
    {
        $tickets = $dto->tickets;
        usort( $tickets, function ($a, $b) {
            return new DateTimeImmutable($a->departureDate) <=> new DateTimeImmutable($b->departureDate);
        });

        return $tickets;
    }

    /**
     * @throws Exception
     */
    public function sortAndExplain(TicketsDto $dto): array
    {
        $sortedTickets = $this->sortByDate($dto);
        $explain = [];

        foreach ($sortedTickets as $ticket) {
            $class = $ticket->toClass();
            $explain[] = $class->displayTicket();
        }

        if ( 0 < count($explain))
            $explain[] = 'You have arrived at your final destination.';

        return $explain;
    }
}
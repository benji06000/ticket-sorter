<?php

namespace App\Service;

use App\Enum\TicketCompareType;
use App\Model\Ticket;

class SorterService
{
    /**
     * Chains a list of tickets in travel order.
     *
     * @param Ticket[] $tickets
     *
     * @return Ticket[]
     *
     * @throws \Exception
     */
    public static function chainTicket(array $tickets): array
    {
        $sorted       = [];
        $departureMap = [];
        $arrivalMap   = [];

        // - Build maps for departures and arrivals
        foreach ($tickets as $ticket) {
            $departure = $ticket->getDepartureName();
            $arrival   = $ticket->getArrivalName();

            // - Prevent logical loops or duplicate entries
            if (isset($departureMap[$departure])) {
                throw new \Exception("Duplicate departure from '$departure'.");
            }
            if (isset($arrivalMap[$arrival])) {
                throw new \Exception("Duplicate arrival at '$arrival'.");
            }

            $departureMap[$departure] = $ticket;
            $arrivalMap[$arrival]     = $ticket;
        }

        // - Identify the unique starting point: a departure not present in arrivals
        $startCandidates = array_diff_key($departureMap, $arrivalMap);
        if (1 !== count($startCandidates)) {
            throw new \Exception('No unique starting point found.');
        }

        /** @var Ticket $current */
        $current = reset($startCandidates);

        // - Rebuild the journey by following each ticket's arrival
        while (null !== $current) {
            $sorted[]      = $current;
            $nextDeparture = $current->getArrivalName();
            $current       = $departureMap[$nextDeparture] ?? null;
        }

        // - Final integrity check: ensure all tickets were chained
        if (count($sorted) !== count($tickets)) {
            throw new \Exception('Not all tickets could be chained - possible loop or disconnected ticket.');
        }

        return $sorted;
    }

    /**
     * @param Ticket[] $tickets
     *
     * @return Ticket[]
     *
     * @throws Exception
     */
    public static function sort(
        array $tickets,
        TicketCompareType $compareType = TicketCompareType::DEPARTURE,
    ): array {
        switch ($compareType) {
            case TicketCompareType::DATE:
                usort($tickets, function (Ticket $a, Ticket $b) {
                    return $a->compareByDateTo($b);
                });
                break;
            case TicketCompareType::DEPARTURE:
                $tickets = self::chainTicket($tickets);
        }

        return $tickets;
    }

    /**
     * @param Ticket[] $tickets
     *
     * @return string[]
     */
    public static function explainTickets(
        array $tickets,
    ): array {
        $explain = [];

        foreach ($tickets as $ticket) {
            $explain[] = $ticket->displayTicket();
        }

        if (0 < count($explain)) {
            $explain[] = 'You have arrived at your final destination.';
        }

        return $explain;
    }
}

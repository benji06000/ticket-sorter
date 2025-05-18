<?php
namespace App\Controller;

use App\Dto\TicketsDto;
use App\Service\SorterService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

readonly class SortController
{
    public function __construct(private SorterService $sorterService)
    {
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            example: [
                'tickets' => [
                    [
                        'seat' => '3A',
                        'ticketType' => 'flight',
                        'departureName' => 'Gerona Airport',
                        'arrivalName' => 'Stockholm',
                        'departureDate' => '2025-05-18 10:40:00',
                        'arrivalDate' => '2025-05-18 12:00:00',
                        'flightNumber' => 'SK455',
                        'gate' => '45B',
                        'baggageDrop' => 344,
                        'connectedFlight' => true,
                    ],
                    [
                        'seat' => '45B',
                        'ticketType' => 'train',
                        'departureName' => 'Madrid',
                        'arrivalName' => 'Barcelona',
                        'departureDate' => '2025-05-18 09:00:00',
                        'arrivalDate' => '2025-05-18 10:00:00',
                        'trainWagon' => '78A',
                    ],
                    [
                        'seat' => '7B',
                        'ticketType' => 'flight',
                        'departureName' => 'Stockholm',
                        'arrivalName' => 'New York JFK',
                        'departureDate' => '2025-05-18 13:10:00',
                        'arrivalDate' => '2025-05-18 16:00:00',
                        'flightNumber' => 'SK455',
                        'gate' => '22',
                        'connectedFlight' => true,
                    ],
                    [
                        'ticketType' => 'bus',
                        'departureName' => 'Barcelona',
                        'arrivalName' => 'Gerona Airport',
                        'departureDate' => '2025-05-18 10:00:00',
                        'arrivalDate' => '2025-05-18 10:10:00',
                        'busNumber' => 'airport',
                    ],
                ]
            ]
        )
    )]
    #[Route('/sort', name: 'sort_tickets', methods: ['POST'])]
    public function sort(
        #[MapRequestPayload]
        TicketsDto $tickets
    ): JsonResponse
    {
        $sortedTickets = $this->sorterService->sortByDate($tickets);

        return new JsonResponse($sortedTickets);
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            example: [
                'tickets' => [
                    [
                        'seat' => '3A',
                        'ticketType' => 'flight',
                        'departureName' => 'Gerona Airport',
                        'arrivalName' => 'Stockholm',
                        'departureDate' => '2025-05-18 10:40:00',
                        'arrivalDate' => '2025-05-18 12:00:00',
                        'flightNumber' => 'SK455',
                        'gate' => '45B',
                        'baggageDrop' => 344,
                        'connectedFlight' => true,
                    ],
                    [
                        'seat' => '45B',
                        'ticketType' => 'train',
                        'departureName' => 'Madrid',
                        'arrivalName' => 'Barcelona',
                        'departureDate' => '2025-05-18 09:00:00',
                        'arrivalDate' => '2025-05-18 10:00:00',
                        'trainWagon' => '78A',
                    ],
                    [
                        'seat' => '7B',
                        'ticketType' => 'flight',
                        'departureName' => 'Stockholm',
                        'arrivalName' => 'New York JFK',
                        'departureDate' => '2025-05-18 13:10:00',
                        'arrivalDate' => '2025-05-18 16:00:00',
                        'flightNumber' => 'SK455',
                        'gate' => '22',
                        'connectedFlight' => true,
                    ],
                    [
                        'ticketType' => 'bus',
                        'departureName' => 'Barcelona',
                        'arrivalName' => 'Gerona Airport',
                        'departureDate' => '2025-05-18 10:00:00',
                        'arrivalDate' => '2025-05-18 10:10:00',
                        'busNumber' => 'airport',
                    ],
                ]
            ]
        )
    )]
    #[Route('/explain', name: 'explain_tickets', methods: ['POST'])]
    public function explain(
        #[MapRequestPayload]
        TicketsDto $tickets
    ): JsonResponse
    {
        $explainedTickets = $this->sorterService->sortAndExplain($tickets);

        return new JsonResponse(
            $explainedTickets
        );
    }
}
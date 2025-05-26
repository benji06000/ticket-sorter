<?php

namespace App\Controller;

use App\Dto\TicketDto;
use App\Dto\TicketsDto;
use App\Enum\TicketCompareType;
use App\Service\SorterService;
use Exception;
use Nelmio\ApiDocBundle\Attribute as ADoc;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

readonly class SortController
{
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'compareType',
                    type: 'string',
                    enum: TicketCompareType::class,
                    example: 'departure'
                ),
                new OA\Property(
                    property: 'tickets',
                    description: 'List of tickets to be sorted',
                    type: 'array',
                    items: new OA\Items(
                        ref: new ADoc\Model(type: TicketDto::class)
                    ),
                    example: [
                        [
                            'seat'            => '3A',
                            'ticketType'      => 'flight',
                            'departureName'   => 'Gerona Airport',
                            'arrivalName'     => 'Stockholm',
                            'departureDate'   => '2025-05-18 10:40:00',
                            'arrivalDate'     => '2025-05-18 12:00:00',
                            'flightNumber'    => 'SK455',
                            'gate'            => '45B',
                            'baggageDrop'     => 344,
                            'connectedFlight' => true,
                        ],
                        [
                            'seat'          => '45B',
                            'ticketType'    => 'train',
                            'departureName' => 'Madrid',
                            'arrivalName'   => 'Barcelona',
                            'departureDate' => '2025-05-18 09:00:00',
                            'arrivalDate'   => '2025-05-18 10:00:00',
                            'trainWagon'    => '78A',
                        ],
                        [
                            'seat'            => '7B',
                            'ticketType'      => 'flight',
                            'departureName'   => 'Stockholm',
                            'arrivalName'     => 'New York JFK',
                            'departureDate'   => '2025-05-18 13:10:00',
                            'arrivalDate'     => '2025-05-18 16:00:00',
                            'flightNumber'    => 'SK455',
                            'gate'            => '22',
                            'connectedFlight' => true,
                        ],
                        [
                            'ticketType'    => 'bus',
                            'departureName' => 'Barcelona',
                            'arrivalName'   => 'Gerona Airport',
                            'departureDate' => '2025-05-18 10:00:00',
                            'arrivalDate'   => '2025-05-18 10:10:00',
                            'busNumber'     => 'airport',
                        ],
                    ]
                ),
            ],
            type: 'object'
        )
    )]
    #[Route('/sort', name: 'sort_tickets', methods: ['POST'])]
    public function sort(
        #[MapRequestPayload]
        TicketsDto $tickets,
        SerializerInterface $serializer,
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize(
                $this->getSortedTicket($tickets), 'json'
            ),
            json: true
        );
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'compareType',
                    type: 'string',
                    enum: TicketCompareType::class,
                    example: 'departure'
                ),
                new OA\Property(
                    property: 'tickets',
                    description: 'List of tickets to be sorted',
                    type: 'array',
                    items: new OA\Items(
                        ref: new ADoc\Model(type: TicketDto::class)
                    ),
                    example: [
                        [
                            'seat'            => '3A',
                            'ticketType'      => 'flight',
                            'departureName'   => 'Gerona Airport',
                            'arrivalName'     => 'Stockholm',
                            'departureDate'   => '2025-05-18 10:40:00',
                            'arrivalDate'     => '2025-05-18 12:00:00',
                            'flightNumber'    => 'SK455',
                            'gate'            => '45B',
                            'baggageDrop'     => 344,
                            'connectedFlight' => true,
                        ],
                        [
                            'seat'          => '45B',
                            'ticketType'    => 'train',
                            'departureName' => 'Madrid',
                            'arrivalName'   => 'Barcelona',
                            'departureDate' => '2025-05-18 09:00:00',
                            'arrivalDate'   => '2025-05-18 10:00:00',
                            'trainWagon'    => '78A',
                        ],
                        [
                            'seat'            => '7B',
                            'ticketType'      => 'flight',
                            'departureName'   => 'Stockholm',
                            'arrivalName'     => 'New York JFK',
                            'departureDate'   => '2025-05-18 13:10:00',
                            'arrivalDate'     => '2025-05-18 16:00:00',
                            'flightNumber'    => 'SK455',
                            'gate'            => '22',
                            'connectedFlight' => true,
                        ],
                        [
                            'ticketType'    => 'bus',
                            'departureName' => 'Barcelona',
                            'arrivalName'   => 'Gerona Airport',
                            'departureDate' => '2025-05-18 10:00:00',
                            'arrivalDate'   => '2025-05-18 10:10:00',
                            'busNumber'     => 'airport',
                        ],
                    ]
                ),
            ],
            type: 'object'
        )
    )]
    #[Route('/explain', name: 'explain_tickets', methods: ['POST'])]
    public function explain(
        #[MapRequestPayload]
        TicketsDto $tickets,
    ): JsonResponse {
        return new JsonResponse(
            SorterService::explainTickets($this->getSortedTicket($tickets)),
        );
    }

    /**
     * @throws UnprocessableEntityHttpException
     */
    private function getSortedTicket(TicketsDto $tickets): array
    {
        try {
            return SorterService::sort(
                $tickets->toClass(),
                $tickets->compareType
            );
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException('Invalid ticket data provided: '.$e->getMessage(), previous: $e);
        }
    }
}

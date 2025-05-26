<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SortControllerTest extends WebTestCase
{
    public function shuffleTicketsDataProvider(): array
    {
        $tickets = [
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
                'flightNumber'    => 'SK22',
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
        ];

        shuffle($tickets);

        return [
            'ShuffleTickets-ByDeparture' => [
                'tickets'     => $tickets,
                'compareType' => 'departure',
            ],
            'ShuffleTickets-ByDate' => [
                'tickets'     => $tickets,
                'compareType' => 'date',
            ],
        ];
    }

    /**
     * @dataProvider shuffleTicketsDataProvider
     */
    public function testSortResponse($tickets, $compareType): void
    {
        $client       = static::createClient();
        $urlGenerator = $client->getContainer()->get(UrlGeneratorInterface::class);
        $url          = $urlGenerator->generate('sort_tickets');

        $client->request(
            Request::METHOD_POST,
            $url,
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT'  => 'application/json',
            ],
            content: json_encode([
                'tickets'     => $tickets,
                'compareType' => $compareType,
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $decodedResponse = json_decode($client->getResponse()->getContent(), true);

        $d1 = $decodedResponse[0];
        $this->assertArrayHasKey('departureName', $d1);
        $this->assertEquals('Madrid', $d1['departureName']);
        $d2 = $decodedResponse[1];
        $this->assertArrayHasKey('departureName', $d2);
        $this->assertEquals('Barcelona', $d2['departureName']);
        $d3 = $decodedResponse[2];
        $this->assertArrayHasKey('departureName', $d3);
        $this->assertEquals('Gerona Airport', $d3['departureName']);
        $d4 = $decodedResponse[3];
        $this->assertArrayHasKey('departureName', $d4);
        $this->assertEquals('Stockholm', $d4['departureName']);
    }

    /**
     * @dataProvider shuffleTicketsDataProvider
     */
    public function testExplainResponse($tickets, $compareType): void
    {
        $client       = static::createClient();
        $urlGenerator = $client->getContainer()->get(UrlGeneratorInterface::class);
        $url          = $urlGenerator->generate('explain_tickets');

        $client->request(
            Request::METHOD_POST,
            $url,
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT'  => 'application/json',
            ],
            content: json_encode([
                'tickets' => $tickets,
                $compareType,
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $decodedResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            'Take train 78A from Madrid to Barcelona. Sit in seat 45B.',
            $decodedResponse[0]
        );
        $this->assertEquals(
            'Take the airport bus from Barcelona to Gerona Airport. No seat assignment.',
            $decodedResponse[1]
        );
        $this->assertEquals(
            'From Gerona Airport, take flight SK455 to Stockholm. Gate 45B, seat 3A.'.
            ' Baggage drop at ticket counter 344.',
            $decodedResponse[2]
        );
        $this->assertEquals(
            'From Stockholm, take flight SK22 to New York JFK. Gate 22, seat 7B.'.
            ' Baggage will be automatically transferred from your last leg.',
            $decodedResponse[3]
        );
        $this->assertEquals(
            'You have arrived at your final destination.',
            $decodedResponse[4]
        );
    }
}

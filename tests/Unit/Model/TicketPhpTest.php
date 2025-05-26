<?php

namespace App\Tests\Unit\Model;

use App\Model\Bus;
use App\Model\Flight;
use App\Model\Train;
use App\Service\SorterService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TicketPhpTest extends KernelTestCase
{
    private SorterService $sorter;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->sorter = self::getContainer()->get(SorterService::class);
    }

    public function errorTicketDataProvider(): array
    {
        $ticketsLoop = [
            (new Bus())
                ->setSeat('3A')
                ->setDepartureName('Gerona Airport')
                ->setArrivalName('Stockholm')
                ->setDepartureDate(new DateTime('2025-05-18 10:40:00'))
                ->setArrivalDate(new DateTime('2025-05-18 12:00:00'))
                ->setBusNumber('airport'),
            (new Flight())
                ->setSeat('45B')
                ->setDepartureName('Stockholm')
                ->setArrivalName('Gerona Airport')
                ->setDepartureDate(new DateTime('2025-05-18 09:00:00'))
                ->setArrivalDate(new DateTime('2025-05-18 10:00:00'))
                ->setFlightNumber('SK455')
                ->setGate('45B')
                ->setConnectedFlight(true),
        ];

        $multipleArrivalAndDestination = [
            (new Flight())
                ->setSeat('7B')
                ->setDepartureName('Stockholm')
                ->setArrivalName('New York JFK')
                ->setDepartureDate(new DateTime('2025-05-18 8:10:00'))
                ->setArrivalDate(new DateTime('2025-05-18 9:40:00'))
                ->setFlightNumber('SK22')
                ->setGate('22')
                ->setConnectedFlight(true),
            (new Bus())
                ->setDepartureName('New York JFK')
                ->setArrivalName('Gerona Airport')
                ->setDepartureDate(new DateTime('2025-05-18 10:00:00'))
                ->setArrivalDate(new DateTime('2025-05-18 10:10:00'))
                ->setBusNumber('airport'),
            (new Flight())
                ->setSeat('3A')
                ->setDepartureName('Gerona Airport')
                ->setArrivalName('New York JFK')
                ->setDepartureDate(new DateTime('2025-05-18 10:40:00'))
                ->setArrivalDate(new DateTime('2025-05-18 12:00:00'))
                ->setFlightNumber('SK455')
                ->setGate('45B')
                ->setConnectedFlight(true),
            (new Flight())
                ->setSeat('45B')
                ->setDepartureName('New York JFK')
                ->setArrivalName('Barcelona')
                ->setDepartureDate(new DateTime('2025-05-18 16:00:00'))
                ->setArrivalDate(new DateTime('2025-05-18 18:00:00'))
                ->setFlightNumber('SK455')
                ->setGate('45B')
                ->setConnectedFlight(true),
        ];

        return [
            'ticketsLoop' => [
                'tickets'          => $ticketsLoop,
                'exceptionMessage' => '/^No unique starting point found\.$/',
            ],
            'multipleArrivalAndDestination' => [
                'tickets'          => $multipleArrivalAndDestination,
                'exceptionMessage' => '/^Duplicate (arrival|departure) (from|at) \'.+\'\.$/',
            ],
        ];
    }

    public function normalTicketDataProvider(): array
    {
        $ticketsNormal = [
            (new Bus())
                ->setSeat('3A')
                ->setDepartureName('Gerona Airport')
                ->setArrivalName('Stockholm')
                ->setDepartureDate(new DateTime('2025-05-18 8:00:00'))
                ->setArrivalDate(new DateTime('2025-05-18 8:30:00'))
                ->setBusNumber('airport'),
            (new Flight())
                ->setSeat('45B')
                ->setDepartureName('Stockholm')
                ->setArrivalName('Barcelona')
                ->setDepartureDate(new DateTime('2025-05-18 09:00:00'))
                ->setArrivalDate(new DateTime('2025-05-18 10:00:00'))
                ->setFlightNumber('SK455')
                ->setGate('45B')
                ->setConnectedFlight(true),
            (new Train())
                ->setSeat('7B')
                ->setDepartureName('Barcelona')
                ->setArrivalName('Paris')
                ->setDepartureDate(new DateTime('2025-05-18 10:20:00'))
                ->setArrivalDate(new DateTime('2025-05-18 18:10:00'))
                ->setTrainWagon('78A'),
        ];

        return [
            'normal' => [
                'tickets' => $ticketsNormal,
            ],
        ];
    }

    /**
     * @dataProvider normalTicketDataProvider
     */
    public function testChainTicket($tickets): void
    {
        $this->sorter::chainTicket($tickets);

        $this->assertCount(3, $tickets);

        $this->assertEquals('Gerona Airport', $tickets[0]->getDepartureName());
        $this->assertEquals('Stockholm', $tickets[0]->getArrivalName());
        $this->assertEquals('Stockholm', $tickets[1]->getDepartureName());
        $this->assertEquals('Barcelona', $tickets[1]->getArrivalName());
        $this->assertEquals('Barcelona', $tickets[2]->getDepartureName());
        $this->assertEquals('Paris', $tickets[2]->getArrivalName());
    }

    /**
     * @dataProvider errorTicketDataProvider
     */
    public function testChainTicketError($tickets, $exceptionMessage): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches($exceptionMessage);

        $this->sorter::chainTicket($tickets);
    }
}

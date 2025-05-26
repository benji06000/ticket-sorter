<?php

namespace App\Model;

use App\Interface\TicketInterface;

class Bus extends Ticket implements TicketInterface
{
    private string $busNumber;

    public function getBusNumber(): string
    {
        return $this->busNumber;
    }

    public function setBusNumber(string $busNumber): Bus
    {
        $this->busNumber = $busNumber;

        return $this;
    }

    public function displayTicket(): string
    {
        $sit = null !== $this->seat ?
            sprintf(
                'Sit in seat %s',
                $this->seat
            ) :
            'No seat assignment';

        return sprintf(
            'Take the %s bus from %s to %s. %s.',
            $this->busNumber,
            $this->departureName,
            $this->arrivalName,
            $sit,
        );
    }
}

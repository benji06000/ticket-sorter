<?php

namespace App\Model;

use App\Interface\TicketInterface;

class Train extends Ticket implements TicketInterface
{
    private string $trainWagon;

    public function getTrainWagon(): string
    {
        return $this->trainWagon;
    }

    public function setTrainWagon(string $trainWagon): Train
    {
        $this->trainWagon = $trainWagon;

        return $this;
    }

    public function displayTicket(): string
    {
        return sprintf(
            'Take train %s from %s to %s. Sit in seat %s.',
            $this->trainWagon,
            $this->departureName,
            $this->arrivalName,
            $this->seat,
        );
    }
}

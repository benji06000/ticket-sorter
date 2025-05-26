<?php

namespace App\Model;

use App\Interface\TicketInterface;

class Flight extends Ticket implements TicketInterface
{
    private string $flightNumber;
    private string $gate;
    private ?int $baggageDrop;
    private bool $connectedFlight;

    public function getFlightNumber(): string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(string $flightNumber): Flight
    {
        $this->flightNumber = $flightNumber;

        return $this;
    }

    public function getGate(): string
    {
        return $this->gate;
    }

    public function setGate(string $gate): Flight
    {
        $this->gate = $gate;

        return $this;
    }

    public function getBaggageDrop(): ?int
    {
        return $this->baggageDrop;
    }

    public function setBaggageDrop(?int $baggageDrop): Flight
    {
        $this->baggageDrop = $baggageDrop;

        return $this;
    }

    public function isConnectedFlight(): bool
    {
        return $this->connectedFlight;
    }

    public function setConnectedFlight(bool $connectedFlight): Flight
    {
        $this->connectedFlight = $connectedFlight;

        return $this;
    }

    public function displayTicket(): string
    {
        $connectingFlightText = $this->connectedFlight ?
            'Baggage will be automatically transferred from your last leg.' :
            '';

        if ('' !== (string) $this->baggageDrop) {
            $connectingFlightText = sprintf('Baggage drop at ticket counter %d.', $this->baggageDrop);
        }

        return sprintf(
            'From %s, take flight %s to %s. Gate %s, seat %s. %s',
            $this->departureName,
            $this->flightNumber,
            $this->arrivalName,
            $this->gate,
            $this->seat,
            $connectingFlightText
        );
    }
}

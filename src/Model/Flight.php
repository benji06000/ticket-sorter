<?php

namespace App\Model;

use App\Interface\TicketInterface;
use DateTimeInterface;

class Flight implements TicketInterface
{
    private ?string $seat;
    private string $flightNumber;
    private string $gate;
    private ?int $baggageDrop;
    private bool $connectedFlight;
    private string $departureName;
    private string $arrivalName;
    private DateTimeInterface $departureDate;
    private DateTimeInterface $arrivalDate;

    public function getSeat(): ?string
    {
        return $this->seat;
    }

    public function setSeat(?string $seat): Flight
    {
        $this->seat = $seat;
        return $this;
    }

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

    public function getDepartureName(): string
    {
        return $this->departureName;
    }

    public function setDepartureName(string $departureName): Flight
    {
        $this->departureName = $departureName;
        return $this;
    }

    public function getArrivalName(): string
    {
        return $this->arrivalName;
    }

    public function setArrivalName(string $arrivalName): Flight
    {
        $this->arrivalName = $arrivalName;
        return $this;
    }

    public function getDepartureDate(): DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(DateTimeInterface $departureDate): Flight
    {
        $this->departureDate = $departureDate;
        return $this;
    }

    public function getArrivalDate(): DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(DateTimeInterface $arrivalDate): Flight
    {
        $this->arrivalDate = $arrivalDate;
        return $this;
    }

    public function displayTicket(): string
    {
        $connectingFlightText =  $this->connectedFlight ?
            'Baggage will be automatically transferred from your last leg.' :
            '';

        if ('' !== (string)$this->baggageDrop) {
            $connectingFlightText =
                sprintf('Baggage drop at ticket counter %d.', $this->baggageDrop);
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
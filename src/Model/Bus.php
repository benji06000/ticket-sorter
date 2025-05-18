<?php

namespace App\Model;

use App\Interface\TicketInterface;
use DateTimeInterface;

class Bus implements TicketInterface
{
    public ?string $seat;
    public ?string $busNumber;
    public string $ticketType;
    public string $departureName;
    public string $arrivalName;
    public DateTimeInterface $departureDate;
    public DateTimeInterface $arrivalDate;

    public function getSeat(): ?string
    {
        return $this->seat;
    }

    public function setSeat(?string $seat): Bus
    {
        $this->seat = $seat;
        return $this;
    }

    public function getBusNumber(): string
    {
        return $this->busNumber;
    }

    public function setBusNumber(string $busNumber): Bus
    {
        $this->busNumber = $busNumber;
        return $this;
    }

    public function getTicketType(): string
    {
        return $this->ticketType;
    }

    public function setTicketType(string $ticketType): Bus
    {
        $this->ticketType = $ticketType;
        return $this;
    }

    public function getDepartureName(): string
    {
        return $this->departureName;
    }

    public function setDepartureName(string $departureName): Bus
    {
        $this->departureName = $departureName;
        return $this;
    }

    public function getArrivalName(): string
    {
        return $this->arrivalName;
    }

    public function setArrivalName(string $arrivalName): Bus
    {
        $this->arrivalName = $arrivalName;
        return $this;
    }

    public function getDepartureDate(): DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(DateTimeInterface $departureDate): Bus
    {
        $this->departureDate = $departureDate;
        return $this;
    }

    public function getArrivalDate(): DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(DateTimeInterface $arrivalDate): Bus
    {
        $this->arrivalDate = $arrivalDate;
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
            "Take the %s bus from %s to %s. %s.",
            $this->busNumber,
            $this->departureName,
            $this->arrivalName,
            $sit,
        );
    }
}
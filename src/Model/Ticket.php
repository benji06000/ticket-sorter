<?php

namespace App\Model;

use DateTimeInterface;

abstract class Ticket
{
    protected ?string $seat;
    protected string $departureName;
    protected string $arrivalName;
    protected DateTimeInterface $departureDate;
    protected DateTimeInterface $arrivalDate;

    public function getSeat(): ?string
    {
        return $this->seat;
    }

    public function setSeat(?string $seat): Ticket
    {
        $this->seat = $seat;

        return $this;
    }

    public function getDepartureName(): string
    {
        return $this->departureName;
    }

    public function setDepartureName(string $departureName): Ticket
    {
        $this->departureName = $departureName;

        return $this;
    }

    public function getArrivalName(): string
    {
        return $this->arrivalName;
    }

    public function setArrivalName(string $arrivalName): Ticket
    {
        $this->arrivalName = $arrivalName;

        return $this;
    }

    public function getDepartureDate(): DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(DateTimeInterface $departureDate): Ticket
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getArrivalDate(): DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(DateTimeInterface $arrivalDate): Ticket
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function compareByDateTo(Ticket $other): int
    {
        return $this->departureDate <=> $other->departureDate;
    }
}

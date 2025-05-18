<?php

namespace App\Model;

use App\Interface\TicketInterface;
use DateTimeInterface;

class Train implements TicketInterface
{
    public ?string $seat;
    public string $trainWagon;
    public string $departureName;
    public string $arrivalName;
    public DateTimeInterface $departureDate;
    public DateTimeInterface $arrivalDate;

    public function getSeat(): ?string
    {
        return $this->seat;
    }

    public function setSeat(?string $seat): Train
    {
        $this->seat = $seat;
        return $this;
    }

    public function getTrainWagon(): string
    {
        return $this->trainWagon;
    }

    public function setTrainWagon(string $trainWagon): Train
    {
        $this->trainWagon = $trainWagon;
        return $this;
    }

    public function getDepartureName(): string
    {
        return $this->departureName;
    }

    public function setDepartureName(string $departureName): Train
    {
        $this->departureName = $departureName;
        return $this;
    }

    public function getArrivalName(): string
    {
        return $this->arrivalName;
    }

    public function setArrivalName(string $arrivalName): Train
    {
        $this->arrivalName = $arrivalName;
        return $this;
    }

    public function getDepartureDate(): DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(DateTimeInterface $departureDate): Train
    {
        $this->departureDate = $departureDate;
        return $this;
    }

    public function getArrivalDate(): DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(DateTimeInterface $arrivalDate): Train
    {
        $this->arrivalDate = $arrivalDate;
        return $this;
    }

    public function displayTicket(): string
    {
        return sprintf(
            "Take train %s from %s to %s. Sit in seat %s.",
            $this->trainWagon,
            $this->departureName,
            $this->arrivalName,
            $this->seat,
        );
    }

}
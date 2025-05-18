<?php

namespace App\Interface;

use DateTimeInterface;

interface TicketInterface
{
    public function getSeat(): ?string;

    public function getDepartureName(): string;

    public function getArrivalName(): string;

    public function getDepartureDate(): DateTimeInterface;

    public function getArrivalDate(): DateTimeInterface;

    public function displayTicket(): string;
}
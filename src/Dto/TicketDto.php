<?php

namespace App\Dto;

use App\Enum\TicketType;
use App\Interface\TicketInterface;
use App\Model\Bus;
use App\Model\Flight;
use App\Model\Train;
use DateTimeImmutable;
use Exception;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use ValueError;

class TicketDto
{
    public function __construct(
        #[Assert\Length(max: 255)]
        #[OA\Property(example: '12A')]
        public ?string $seat,

        #[Assert\Length(max: 6)]
        #[OA\Property(example: 'flight')]
        public string $ticketType,

        #[Assert\Length(max: 255)]
        #[OA\Property(example: 'JFK Airport')]
        public string $departureName,

        #[Assert\Length(max: 255)]
        #[OA\Property(example: 'Heathrow')]
        public string $arrivalName,

        #[Assert\DateTime]
        #[OA\Property(example: 'Y-m-d h:i:s')]
        public string $departureDate,

        #[Assert\DateTime]
        #[OA\Property(example: 'Y-m-d h:i:s')]
        public string $arrivalDate,

        // -- Bus+
        #[Assert\Length(max: 255)]
        #[OA\Property(example: 'BUS-42')]
        public ?string $busNumber,

        // -- Train
        #[Assert\Length(max: 255)]
        #[OA\Property(example: 'Wagon 7')]
        public ?string $trainWagon,

        // -- Flight information
        #[Assert\Length(max: 255)]
        #[OA\Property(example: 'AF1984')]
        public ?string $flightNumber,

        #[Assert\Length(max: 255)]
        #[OA\Property(example: 'G12')]
        public ?string $gate,

        #[Assert\Positive]
        #[OA\Property(example: 422)]
        public ?int $baggageDrop,

        #[Assert\Type(type: 'bool')]
        #[OA\Property(example: false)]
        public ?bool $connectedFlight,
    ) {
    }

    #[Ignore]
    public function getTicketTypeEnum(): TicketType
    {
        return TicketType::from($this->ticketType);
    }

    /**
     * @throws Exception
     */
    #[Ignore]
    public function toClass(): TicketInterface
    {
        return match ($this->getTicketTypeEnum()) {
            TicketType::FLIGHT => (new Flight())
                ->setSeat($this->seat)
                ->setFlightNumber($this->flightNumber)
                ->setGate($this->gate)
                ->setBaggageDrop($this->baggageDrop)
                ->setConnectedFlight($this->connectedFlight)
                ->setDepartureName($this->departureName)
                ->setArrivalName($this->arrivalName)
                ->setDepartureDate(new DateTimeImmutable($this->departureDate))
                ->setArrivalDate(new DateTimeImmutable($this->arrivalDate))
            ,
            TicketType::TRAIN => (new Train())
                ->setSeat($this->seat)
                ->setTrainWagon($this->trainWagon)
                ->setDepartureName($this->departureName)
                ->setArrivalName($this->arrivalName)
                ->setDepartureDate(new DateTimeImmutable($this->departureDate))
                ->setArrivalDate(new DateTimeImmutable($this->arrivalDate))
            ,
            TicketType::BUS => (new Bus())
                ->setSeat($this->seat)
                ->setBusNumber($this->busNumber)
                ->setDepartureName($this->departureName)
                ->setArrivalName($this->arrivalName)
                ->setDepartureDate(new DateTimeImmutable($this->departureDate))
                ->setArrivalDate(new DateTimeImmutable($this->arrivalDate))
            ,
        };
    }

    #[Assert\Callback]
    public function validationByType(ExecutionContextInterface $context, mixed $payload): void
    {
        try {
            $ticketType = $this->getTicketTypeEnum();
        } catch (ValueError $e) {
            $context->buildViolation(sprintf(
                'Invalid ticket type %s, must be one of "%s"',
                $this->ticketType,
                implode('", "',
                    array_column(TicketType::cases(), 'value')
                )
            ))
                ->atPath('ticketType')
                ->addViolation();

            return;
        }

        // -- Check if date are valid
        if ($this->arrivalDate < $this->departureDate) {
            $context->buildViolation('Arrival date must be after departure date')
                ->atPath('arrivalDate')
                ->addViolation();
        }

        switch ($ticketType) {
            case TicketType::FLIGHT:
                if (null === $this->flightNumber) {
                    $context->buildViolation('Flight number is required for flight tickets')
                        ->atPath('flightNumber')
                        ->addViolation();
                }
                if (null === $this->gate) {
                    $context->buildViolation('Gate is required for flight tickets')
                        ->atPath('gate')
                        ->addViolation();
                }
                break;

            case TicketType::TRAIN:
                if (null === $this->trainWagon) {
                    $context->buildViolation('Train wagon is required for train tickets')
                        ->atPath('trainWagon')
                        ->addViolation();
                }
                break;

            case TicketType::BUS:
                if (null === $this->busNumber) {
                    $context->buildViolation('Bus number is required for bus tickets')
                        ->atPath('busNumber')
                        ->addViolation();
                }
                break;
        }
    }
}

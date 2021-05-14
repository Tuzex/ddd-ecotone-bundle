<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Infrastructure\Messaging\Ecotone;

use Ecotone\Modelling\EventBus as EcotoneEventBus;
use Tuzex\Ddd\Application\DomainEventBus;
use Tuzex\Ddd\Domain\DomainEvent;

final class EcotoneAwareDomainEventBus implements DomainEventBus
{
    public function __construct(
        private EcotoneEventBus $ecotoneEventBus
    ) {}

    public function publish(DomainEvent $domainEvent): void
    {
        $this->ecotoneEventBus->publish($domainEvent);
    }
}

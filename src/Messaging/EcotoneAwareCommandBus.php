<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Infrastructure\Messaging\Ecotone;

use Ecotone\Modelling\CommandBus as EcotoneCommandBus;
use Tuzex\Ddd\Application\CommandBus;
use Tuzex\Ddd\Domain\Command;

final class EcotoneAwareCommandBus implements CommandBus
{
    public function __construct(
        private EcotoneCommandBus $commandBus,
    ) {}

    public function execute(Command $command): void
    {
        $this->commandBus->send($command);
    }
}

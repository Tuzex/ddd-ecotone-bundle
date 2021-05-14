<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Ddd\Messaging\Interceptor;

use Ecotone\Messaging\Attribute\Interceptor\Around;
use Ecotone\Messaging\Handler\Processor\MethodInvoker\MethodInvocation;
use Ecotone\Modelling\EventBus;
use Tuzex\Ddd\Application\Service\CommandsSpooler;

final class CommandsAspectDispatcher implements AspectDispatcher
{
    public function __construct(
        private CommandsSpooler $commandsSpooler,
    ) {}

    #[Around(pointcut: EventBus::class)]
    public function dispatch(MethodInvocation $invocation): void
    {
        $invocation->proceed();

        $this->commandsSpooler->send();
    }
}

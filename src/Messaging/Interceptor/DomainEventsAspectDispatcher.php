<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Infrastructure\Messaging\Ecotone\Interceptor;

use Ecotone\Messaging\Attribute\Interceptor\Around;
use Ecotone\Messaging\Handler\Processor\MethodInvoker\MethodInvocation;
use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\EventBus;
use Tuzex\Ddd\Application\Service\DomainEventsEmitter;

final class DomainEventsAspectDispatcher implements AspectDispatcher
{
    public function __construct(
        private DomainEventsEmitter $domainEventEmitter,
    ) {}

//    #[Around(pointcut: CommandBus::class.'||'.EventBus::class)]
    public function dispatch(MethodInvocation $invocation): void
    {
        $invocation->proceed();

        $this->domainEventEmitter->emit();
    }
}

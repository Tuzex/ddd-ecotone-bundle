<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Infrastructure\Messaging\Ecotone\Interceptor;

use Ecotone\Messaging\Handler\Processor\MethodInvoker\MethodInvocation;

interface AspectDispatcher
{
    public function dispatch(MethodInvocation $invocation): void;
}

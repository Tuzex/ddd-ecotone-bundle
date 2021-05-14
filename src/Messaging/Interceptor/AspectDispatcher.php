<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Ddd\Messaging\Interceptor;

use Ecotone\Messaging\Handler\Processor\MethodInvoker\MethodInvocation;

interface AspectDispatcher
{
    public function dispatch(MethodInvocation $invocation): void;
}

<?php

declare(strict_types=1);

namespace Easy\Tooltip;

interface LoaderInterface
{
    public function addAction(string $tag, array $callable, int $priority = 10, int $acceptedArgs = 1): void;

    public function addFilter(string $tag, array $callable, int $priority, int $acceptedArgs): void;
}

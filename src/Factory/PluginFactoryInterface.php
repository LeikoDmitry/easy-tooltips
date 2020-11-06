<?php

declare(strict_types=1);

namespace Easy\Tooltip\Factory;

use Psr\Log\LoggerInterface;

interface PluginFactoryInterface
{
    public function getLogger(): LoggerInterface;

    public function initPlugin(): void;
}

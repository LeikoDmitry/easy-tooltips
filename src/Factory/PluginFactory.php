<?php

declare(strict_types=1);

namespace Easy\Tooltip\Factory;

use Easy\Tooltip\Admin;
use Easy\Tooltip\Exception\RuntimeException;
use Easy\Tooltip\Handler;
use Easy\Tooltip\HooksLoader;
use Easy\Tooltip\LoaderInterface;
use Easy\Tooltip\Plugin;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

use function sprintf;

class PluginFactory implements PluginFactoryInterface
{
    private string $version;
    private string $path;
    private const LOGGER_NAME = 'easy-tooltips';

    public function __construct(string $version, string $path)
    {
        $this->version = $version;
        $this->path    = $path;
    }

    public function getLogger(): LoggerInterface
    {
        $log = new Logger(static::LOGGER_NAME);
        $log->pushHandler(new StreamHandler(sprintf('%s/%s/%s.log', $this->path, 'log', static::LOGGER_NAME), Logger::WARNING));
        return $log;
    }

    public function initPlugin(): void
    {
        try {
            $instance = Plugin::getInstance();
            $instance->setVersion($this->version);
            $instance->setLoader($this->getLoader());
            $instance->setAdmin($this->getAdmin());
            $instance->setHandler($this->getPublic());
            $instance->runApplication();
        } catch (RuntimeException $exception) {
            $this->getLogger()->error($exception->getMessage());
        }
    }

    public function getLoader(): LoaderInterface
    {
        return new HooksLoader();
    }

    public function getAdmin(): Admin
    {
        return new Admin();
    }

    public function getPublic(): Handler
    {
        return new Handler($this->path);
    }
}

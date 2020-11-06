<?php

declare(strict_types=1);

namespace Easy\Tooltip;

class Plugin
{
    private string $version;
    private LoaderInterface $loader;
    private Admin $admin;
    private Handler $handler;
    private static ?Plugin $instance      = null;
    private const ENQUEUE_SCRIPT_PRIORITY = 200;

    public static function getInstance(): ?Plugin
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function runApplication(): self
    {
        $loader = $this->getLoader();
        $this->defineAdminHooks($loader);
        $this->definePublicHooks($loader);
        $loader->run();
        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getLoader(): LoaderInterface
    {
        return $this->loader;
    }

    public function setLoader(LoaderInterface $loader): void
    {
        $this->loader = $loader;
    }

    private function definePublicHooks(LoaderInterface $loader): void
    {
        $easyPublic = $this->getHandler();
        $loader->addAction('wp_enqueue_scripts', [$easyPublic, 'loadTooltips'], static::ENQUEUE_SCRIPT_PRIORITY);
    }

    private function defineAdminHooks(LoaderInterface $loader): void
    {
        $easyAdmin = $this->getAdmin();
        $loader->addAction('admin_menu', [$easyAdmin, 'addPluginPage']);
        $loader->addAction('admin_init', [$easyAdmin, 'pageInit']);
        $loader->addAction('admin_enqueue_scripts', [$easyAdmin, 'adminEnqueueScripts']);
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }

    public function setAdmin(Admin $admin): void
    {
        $this->admin = $admin;
    }

    public function getHandler(): Handler
    {
        return $this->handler;
    }

    public function setHandler(Handler $handler): void
    {
        $this->handler = $handler;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}

<?php

declare(strict_types=1);

namespace Easy\Tooltip;

class HooksLoader implements LoaderInterface
{
    private array $actions;
    private array $filters;

    public function __construct()
    {
        $this->actions = [];
        $this->filters = [];
    }

    public function addAction(string $tag, array $callable, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->actions = $this->add($this->actions, $tag, $callable, $priority, $acceptedArgs);
    }

    public function addFilter(string $tag, array $callable, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->filters = $this->add($this->filters, $tag, $callable, $priority, $acceptedArgs);
    }

    private function add(array $hooks, string $hook, array $callable, int $priority, int $acceptedArgs): array
    {
        $hooks[] = [
            'hook'          => $hook,
            'callback'      => $callable,
            'priority'      => $priority,
            'accepted_args' => $acceptedArgs,
        ];
        return $hooks;
    }

    public function run(): void
    {
        if ($this->filters) {
            foreach ($this->filters as $hook) {
                add_filter($hook['hook'], $hook['callback'], $hook['priority'], $hook['acceptedArgs']);
            }
        }
        if ($this->actions) {
            foreach ($this->actions as $hook) {
                add_action($hook['hook'], $hook['callback'], $hook['priority'], $hook['acceptedArgs']);
            }
        }
    }
}

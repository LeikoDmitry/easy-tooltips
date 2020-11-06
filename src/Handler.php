<?php

declare(strict_types=1);

namespace Easy\Tooltip;

use function compact;
use function sprintf;

class Handler
{
    private string $path;
    public const DEFAULT_THEME    = 'default';
    public const DEFAULT_WIDTH    = 250;
    public const DEFAULT_OPACITY  = .95;
    public const DEFAULT_POSITION = 'center';
    public const TOOLTIP_CLASS    = 'easy__tooltips';
    public const SHORT_CODE_NAME  = 'easy_tooltip';

    public function __construct(string $path = '')
    {
        $this->path = $path;
        add_shortcode(static::SHORT_CODE_NAME, [$this, 'addShortcode']);
    }

    public function loadTooltips(): void
    {
        if (! is_admin()) {
            $options = $this->getOptions();
            wp_enqueue_script(
                'easy_tooltips_jquery',
                plugins_url('assets/jquery-3.5.0.min.js', $this->path),
                [],
                EASY_TOOLTIPS_VERSION,
                true
            );
            wp_enqueue_script(
                'zebra_tooltips_base',
                plugins_url('assets/zebra_tooltips.js', $this->path),
                ['easy_tooltips_jquery'],
                EASY_TOOLTIPS_VERSION,
                true
            );
            wp_enqueue_script(
                'easy_tooltips_base',
                plugins_url('assets/easy-tooltips.js', $this->path),
                ['easy_tooltips_jquery', 'zebra_tooltips_base'],
                EASY_TOOLTIPS_VERSION,
                true
            );
            wp_localize_script('easy_tooltips_base', 'easyTooltipData', compact('options'));
            wp_enqueue_style(
                'easy_tooltips_style',
                plugins_url(sprintf('assets/zebra_%s_tooltips.css', $options['theme'] ?? static::DEFAULT_THEME), $this->path),
                EASY_TOOLTIPS_VERSION
            );
        }
    }

    public function getOptions(): array
    {
        $maxWidth = get_option('easy-tooltips_max_width', static::DEFAULT_WIDTH);
        $opacity  = get_option('easy-tooltips_opacity', static::DEFAULT_OPACITY);
        $position = get_option('easy-tooltips_position', static::DEFAULT_POSITION);
        $class    = static::TOOLTIP_CLASS;
        $theme    = get_option('easy-tooltips_theme', static::DEFAULT_THEME);
        return compact('maxWidth', 'opacity', 'position', 'class', 'theme');
    }

    public function addShortcode($attrs, $content = null): string
    {
        // [easy_tooltip content="I love the world" max_width="100%"][best_selling_products][/easy_tooltip]
        $attrs = shortcode_atts([
            'title'     => '',
            'content'   => '',
            'position'  => get_option('easy-tooltips_position', static::DEFAULT_POSITION),
            'opacity'   => get_option('easy-tooltips_opacity', static::DEFAULT_OPACITY),
            'max_width' => get_option('easy-tooltips_max_width', static::DEFAULT_WIDTH),
        ], $attrs);
        return sprintf(
            '<span class=\'%s\' title=\'%s\' data-ztt_position=\'%s\' data-ztt_opacity=\'%s\' data-ztt_max_width=\'%s\'>%s</span>',
            static::TOOLTIP_CLASS,
            $content ? do_shortcode($content) : do_shortcode(esc_attr($attrs['title'])),
            esc_attr($attrs['position']),
            esc_attr($attrs['opacity']),
            esc_attr($attrs['max_width']),
            esc_attr($attrs['content'])
        );
    }
}

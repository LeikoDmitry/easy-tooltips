<?php

declare(strict_types=1);

namespace Easy\Tooltip;

use function printf;
use function sprintf;

class Admin
{
    private const ADMIN_LABEL = 'Easy Tooltips';
    private const ADMIN_SLUG  = 'easy-tooltips';

    public function addPluginPage(): void
    {
        add_options_page(
            'Settings Admin',
            static::ADMIN_LABEL,
            'manage_options',
            sprintf('%s%s', static::ADMIN_SLUG, '-setting-admin'),
            [$this, 'createAdminPage']
        );
    }

    public function createAdminPage(): void
    {
        ?>
            <div class="wrap" style="position:relative;">
                <h2><?php echo esc_attr(static::ADMIN_LABEL) ?></h2>
                <form method="post" action="options.php">
                    <?php
                        settings_fields(sprintf('%s%s', static::ADMIN_SLUG, '_option_group'));
                        do_settings_sections(sprintf('%s%s', static::ADMIN_SLUG, '-setting-admin'));
                    ?>
                    <?php submit_button(); ?>
                </form>
            </div>
        <?php
    }

    public function pageInit(): void
    {
        add_settings_section(
            sprintf('%s%s', static::ADMIN_SLUG, '_setting_section'),
            'Configuration',
            function(): string {
                return '';
            },
            sprintf('%s%s', static::ADMIN_SLUG, '-setting-admin')
        );

        // Theme
        $fieldSlug  = 'theme';
        $fieldLabel = 'Choose the theme:';
        $fieldId    = sprintf('%s_%s', static::ADMIN_SLUG, $fieldSlug);
        register_setting(sprintf('%s%s', static::ADMIN_SLUG, '_option_group'), $fieldId);
        add_settings_field(
            $fieldId,
            $fieldLabel,
            [$this, 'createSelectInput'],
            sprintf('%s%s', static::ADMIN_SLUG, '-setting-admin'),
            sprintf('%s%s', static::ADMIN_SLUG, '_setting_section'),
            [
                'id'             => $fieldId,
                'desc'           => 'You might choose some theme.',
                'default'        => Handler::DEFAULT_THEME,
                'select_options' => [
                    ['label' => 'Default', 'value' => 'default'],
                    ['label' => 'Bubble', 'value' => 'bubble'],
                    ['label' => 'Mariner', 'value' => 'mariner'],
                    ['label' => 'Milan', 'value' => 'milan'],
                ],
            ]
        );

        // Position
        $fieldSlug  = 'position';
        $fieldLabel = 'Position:';
        $fieldId    = sprintf('%s_%s', static::ADMIN_SLUG, $fieldSlug);
        register_setting(sprintf('%s%s', static::ADMIN_SLUG, '_option_group'), $fieldId);
        add_settings_field(
            $fieldId,
            $fieldLabel,
            [$this, 'createSelectInput'],
            sprintf('%s%s', static::ADMIN_SLUG, '-setting-admin'),
            sprintf('%s%s', static::ADMIN_SLUG, '_setting_section'),
            [
                'id'             => $fieldId,
                'desc'           => 'The tooltip\'s position, relative to the trigger element.',
                'default'        => 'center',
                'select_options' => [
                    ['label' => 'Center', 'value' => 'center'],
                    ['label' => 'Left', 'value' => 'left'],
                    ['label' => 'Right', 'value' => 'right'],
                ],
            ]
        );

        // Input opacity
        $fieldSlug  = 'opacity';
        $fieldLabel = 'Opacity:';
        $fieldId    = sprintf('%s_%s', static::ADMIN_SLUG, $fieldSlug);
        register_setting(sprintf('%s%s', static::ADMIN_SLUG, '_option_group'), $fieldId);
        add_settings_field(
            $fieldId,
            $fieldLabel,
            [$this, 'creatTextInput'],
            sprintf('%s%s', static::ADMIN_SLUG, '-setting-admin'),
            sprintf('%s%s', static::ADMIN_SLUG, '_setting_section'),
            [
                'id'      => $fieldId,
                'desc'    => 'Set the opacity of the tooltip bubble. This should be a number <br>between 0 and 1. 0 makes the bubble invisible and 1 makes <br>the bubble totally solid. Default is .95.',
                'default' => '.95',
            ]
        );

        // Input width
        $fieldSlug  = 'max_width';
        $fieldLabel = 'Max width:';
        $fieldId    = sprintf('%s_%s', static::ADMIN_SLUG, $fieldSlug);
        register_setting(sprintf('%s%s', static::ADMIN_SLUG, '_option_group'), $fieldId);
        add_settings_field(
            $fieldId,
            $fieldLabel,
            [$this, 'creatTextInput'],
            sprintf('%s%s', static::ADMIN_SLUG, '-setting-admin'),
            sprintf('%s%s', static::ADMIN_SLUG, '_setting_section'),
            [
                'id'      => $fieldId,
                'desc'    => 'Set the max width for the tooltips.',
                'default' => '300',
            ]
        );
    }

    public function creatTextInput($args): void
    {
        $class           = $args['class'] ?? '';
        $placeholderHtml = $args['placeholder'] ?? '';
        $description     = $args['desc'] ?? '';
        printf(
            '<p><input type="text" placeholder="%s" id="%s" class="%s" name="%s" value="%s"></p>',
            $placeholderHtml,
            $args['id'],
            $class,
            $args['id'],
            get_option($args['id'], $args['default'])
        );
        printf('<p class="description">%s</p>', $description);
    }

    public function createSelectInput($args): void
    {
        $selectOptions = $args['select_options'] ?? [];
        $description   = $args['desc'] ?? '';
        $fieldId       = $args['id'] ?? '';
        $html          = '';
        $html         .= sprintf('<p>%s</p>', $description);
        $html         .= sprintf('<select id="%s" name="%s">', $fieldId, $fieldId);
        foreach ($selectOptions as $option) {
            $html .= sprintf('<option value="%s" %s>%s</option>', $option['value'], selected($option['value'], get_option($fieldId, $args['default']), false), $option['label']);
        }
        $html .= '</select>';
        echo $html;
    }

    public function adminEnqueueScripts(): void
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('easy-admin-script-handle', plugins_url('/assets/admin-easy-tooltips.js', EASY_TOOLTIPS_PATH), ['wp-color-picker'], false, true);
    }

    public function fixTooltipsAdminJquery(): void
    {
        if (isset($_GET['page']) && $_GET['page'] === sprintf('%s%s', static::ADMIN_SLUG, '-setting-admin')) {
            ?>
                <script>
                    (function($){
                        $(document).ready(function(){
                            $('tr.motech-color-field').removeClass('motech-color-field');
                        });
                    })(jQuery);
                </script>
            <?php
        }
    }
}
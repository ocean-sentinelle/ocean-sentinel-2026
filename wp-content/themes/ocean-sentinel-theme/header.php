<?php

declare(strict_types=1);

$os_data = function_exists('os_get_latest_ocean_data') ? os_get_latest_ocean_data(false) : [];
$os_state = is_array($os_data) ? ($os_data['state'] ?? 'unknown') : 'unknown';

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class('os-state-' . sanitize_html_class((string) $os_state)); ?>>
<?php wp_body_open(); ?>
<header class="os-header">
  <div class="os-header__inner">
    <div class="os-brand">
      <a class="os-brand__link" href="<?php echo esc_url(home_url('/')); ?>">
        <?php echo esc_html(get_bloginfo('name')); ?>
      </a>
    </div>

    <div class="os-network-status" aria-live="polite">
      <span class="os-network-status__label">Statut réseau</span>
      <span class="os-network-status__value <?php echo $os_state === 'critical' ? 'os-alert' : ''; ?>">
        <?php echo esc_html(strtoupper((string) $os_state)); ?>
      </span>
    </div>
  </div>
</header>

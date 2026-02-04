<?php

declare(strict_types=1);

get_header();

$os_data = function_exists('os_get_latest_ocean_data') ? os_get_latest_ocean_data(true) : [];
$os_state = is_array($os_data) ? ($os_data['state'] ?? 'unknown') : 'unknown';

$ph = is_array($os_data) ? ($os_data['ph'] ?? null) : null;
$temp = is_array($os_data) ? ($os_data['temp'] ?? null) : null;
$salinity = is_array($os_data) ? ($os_data['salinity'] ?? null) : null;
$omega = is_array($os_data) ? ($os_data['omega_arag'] ?? null) : null;

$alert_class = $os_state === 'critical' ? 'os-bento__card--critical' : ($os_state === 'vigilance' ? 'os-bento__card--vigilance' : '');

?>
<main id="site-content" class="os-main">
  <section class="os-bento" aria-label="Ocean Sentinel Bento">
    <div class="os-bento__card os-bento__card--header <?php echo esc_attr($alert_class); ?>">
      <h1 class="os-bento__title">Vigilance Scientifique</h1>
      <p class="os-bento__subtitle">Interface de résilience — Nouvelle-Aquitaine</p>
      <div class="os-bento__meta">
        <span class="os-badge">État: <?php echo esc_html(strtoupper((string) $os_state)); ?></span>
        <span class="os-badge">ΩArag: <?php echo $omega === null ? '—' : esc_html(number_format_i18n((float) $omega, 2)); ?></span>
      </div>
    </div>

    <div class="os-bento__card">
      <h2 class="os-bento__card-title">Slot A — pH temps réel</h2>
      <p class="os-bento__value"><?php echo $ph === null ? '—' : esc_html(number_format_i18n((float) $ph, 2)); ?></p>
    </div>

    <div class="os-bento__card <?php echo esc_attr($alert_class); ?>">
      <h2 class="os-bento__card-title">Slot B — Saturation Aragonite</h2>
      <p class="os-bento__value"><?php echo $omega === null ? '—' : esc_html(number_format_i18n((float) $omega, 2)); ?></p>
      <p class="os-bento__hint">Findlay Alert</p>
    </div>

    <div class="os-bento__card">
      <h2 class="os-bento__card-title">Slot C — Risque économique</h2>
      <p class="os-bento__value">ABACODE</p>
      <p class="os-bento__hint">(à connecter)</p>
    </div>

    <div class="os-bento__card os-bento__card--wide">
      <h2 class="os-bento__card-title">Mesures</h2>
      <div class="os-measures">
        <div class="os-measure">
          <span class="os-measure__label">Temp</span>
          <span class="os-measure__value"><?php echo $temp === null ? '—' : esc_html(number_format_i18n((float) $temp, 2)); ?>°C</span>
        </div>
        <div class="os-measure">
          <span class="os-measure__label">Salinité</span>
          <span class="os-measure__value"><?php echo $salinity === null ? '—' : esc_html(number_format_i18n((float) $salinity, 2)); ?></span>
        </div>
      </div>
    </div>
  </section>
</main>
<?php

get_footer();

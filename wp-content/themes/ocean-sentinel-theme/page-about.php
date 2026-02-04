<?php
/**
 * Template Name: À Propos
 */

declare(strict_types=1);

get_header();

?>
<main id="site-content" class="os-main">
  <section class="os-hero" aria-label="À Propos — Ocean Sentinel">
    <div class="os-hero__inner">
      <div class="os-hero__panel">
        <h1 class="os-hero__title">À Propos d’OCÉAN-SENTINELLE 2026</h1>
        <p class="os-hero__lead">La convergence de la science et de l’intention — une infrastructure de résilience numérique pour la filière conchylicole de Nouvelle-Aquitaine.</p>
      </div>
    </div>
  </section>

  <section class="os-bento os-bento--about" aria-label="Contenu — À Propos">
    <article class="os-bento__card os-bento__card--wide">
      <h2 class="os-about__h2">Notre Vision</h2>
      <p class="os-about__p">OCÉAN-SENTINELLE n’est pas un simple site de surveillance météo. Nous transformons des flux biogéochimiques complexes en décisions claires pour l’ostréiculture de Nouvelle-Aquitaine — avec une priorité absolue : la vigilance scientifique.</p>
      <p class="os-about__p">Notre approche d’ingénierie centrée sur l’intention (Vibecoding) vise à rendre la donnée lisible, actionnable et fiable, sans dépendre d’extensions lourdes ni d’appels externes au rendu.</p>
    </article>

    <article class="os-bento__card">
      <h2 class="os-about__h2">Le Référentiel Findlay 2025</h2>
      <p class="os-about__p">Le cœur du système repose sur Findlay et al. (2025). Au-delà du pH, nous suivons l’état de saturation de l’aragonite (<span class="os-mono">ΩArag</span>), indicateur clé du risque de dissolution pour les organismes calcificateurs.</p>

      <div class="os-about__alert" role="note" aria-label="Seuil scientifique">
        <strong class="os-about__alert-title">Seuil de rupture : <span class="os-mono">ΩArag &lt; 1.75</span></strong>
        <p class="os-about__p os-about__p--tight">Sous <span class="os-mono">1.75</span>, l’eau devient corrosive : le système force l’état visuel <span class="os-about__tag">CRITIQUE</span> pour déclencher une lecture immédiate.</p>
      </div>
    </article>

    <article class="os-bento__card">
      <h2 class="os-about__h2">L’Impact ABACODE</h2>
      <p class="os-about__p">ABACODE convertit le stress biologique en impact financier réel (€/ha). L’objectif : fournir un langage commun entre biologistes, producteurs et gestionnaires de risques, afin d’accélérer la décision.</p>

      <div class="os-about__highlight" aria-label="Mise en avant ABACODE">
        <div class="os-about__highlight-kicker">Protocole ABACODE</div>
        <div class="os-about__highlight-body">
          <p class="os-about__p os-about__p--tight">Chaque heure d’exposition sous les seuils est traduite en <strong>perte d’exploitation</strong> (€/ha). Ce “prix de l’inaction” rend le risque visible et comparable.</p>
        </div>
      </div>
    </article>

    <article class="os-bento__card os-bento__card--wide">
      <h2 class="os-about__h2">Architecture de Confiance</h2>
      <p class="os-about__p">Les seuils scientifiques sont verrouillés dans un MU-Plugin, séparé du thème. Les données sont lues localement depuis <span class="os-mono">wp-content/sentinel-data/data.csv</span> et mises en cache pour garantir un rendu rapide (LCP &lt; 1.8s) sans dépendances externes.</p>
    </article>
  </section>
</main>
<?php

get_footer();

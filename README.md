# 🛡️ Ocean Sentinel 2026
> **Plateforme de Résilience Conchylicole & Surveillance de l'Acidification (Région Nouvelle-Aquitaine)**

Ocean Sentinel est une infrastructure sentinelle de pointe conçue pour surveiller la septième limite planétaire : l'acidification des océans. En s'appuyant sur les référentiels de **Findlay et al. (2025)**, le système transforme les flux de données biogéochimiques en indicateurs de risque exploitables pour l'ostréiculture.

---

## 🧬 Fondations Scientifiques (Findlay 2025)

Le système surveille principalement la **Saturation de l'Aragonite ($\Omega_{arag}$)**, paramètre pivot pour la calcification des huîtres.

| État du Risque | Seuil ($\Omega_{arag}$) | Action du Système |
| :--- | :--- | :--- |
| **Sécurité** | > 1.80 | Surveillance standard (Bleu Abyssal) |
| **Vigilance** | 1.80 - 1.75 | Alerte jaune : Stress métabolique détecté |
| **Rupture** | < 1.75 | **ALERTE CRITIQUE (Orange Acide)** |

---

## 🛠️ Architecture Technique (Vibecoding 2026)

Ce projet utilise l'**Architecture de l'Intention** pour garantir performance et sécurité sans dépendances superflues.

* **Framework :** WordPress (Thème natif haute performance).
* **Infrastructure :** Hostinger Business (Optimisé HTTP/3 QUIC).
* **Accessibilité :** Standard **APCA Lc 90** (Lisibilité maximale en extérieur/plein soleil).
* **Sécurité Agentique :** Conformité **OWASP ASI 2026** (Protection par Intent Capsule).
* **Design :** Structure "Bento Box" pour une clarté cognitive immédiate.

---

## 📊 Flux de Données

Le système ingère les données issues des réseaux nationaux gérés par l'IR ILICO :
1.  **COAST-HF :** Monitoring haute fréquence (Bouée 13 - Bassin d'Arcachon).
2.  **SOMLIT :** Calibration bi-mensuelle de haute précision.
3.  **Local Cache :** Les données sont traitées via un *Data Fetcher Agentique* natif situé dans `/sentinel-data/`.

---

## 🛡️ Gouvernance & Sécurité

- **Calculs Critiques :** Logique métier verrouillée dans des `MU-Plugins` (Must-Use).
- **Immuabilité :** Les seuils scientifiques sont définis dans `wp-config.php`.
- **Zéro-Plugin :** Aucune extension tierce n'est autorisée pour le traitement des données afin de prévenir le *Slopsquatting* (ASI04).

---

## 👥 Contributeurs & Crédits
- **Architecte de l'Intention :** [Votre Nom/Organisation]
- **Cadre Scientifique :** Findlay et al. (2025) / Planetary Health Check.
- **Données :** IFREMER / SOMLIT / COAST-HF.

---
*Projet généré et maintenu via le système GeM (Intent Architect Pro).*

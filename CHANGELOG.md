# Changelog – wtl/hio-typo3-connector-frontend

Alle relevanten Änderungen an diesem Paket werden hier dokumentiert.
Das Format orientiert sich an [Keep a Changelog](https://keepachangelog.com/de/1.0.0/).
Die Versionierung folgt [Semantic Versioning](https://semver.org/lang/de/).

Dieses Paket ist das **Frontend-Rendering-Paket** und setzt
`wtl/hio-typo3-connector` als Abhängigkeit voraus.
Es stellt Fluid-Templates, Data Processors und Backend-Vorschau-Renderer bereit.

---

## [1.2.0] – 2026-04-22

### Hinweise für Redakteure

Das Erscheinungsbild im TYPO3-Backend (Plugin-Auswahl, FlexForm-Einstellungen) wird
durch `wtl/hio-typo3-connector` gesteuert. Dieses Paket beeinflusst ausschließlich
das **Frontend-Rendering** und die **Backend-Vorschau** der Inhaltselemente.

Redakteure sehen beim Bearbeiten eines HIO-Publisher-Inhaltselements eine
kompakte Vorschau der anzuzeigenden Daten (z. B. „Publikationsliste – 3 Einträge").
Diese Vorschau wird von den `Preview`-Klassen in `Classes/Backend/Preview/` gerendert.

### Hinweise für Agentur-Entwickler

#### Abhängigkeit und Versionskompatibilität

```json
// composer.json
"require": {
    "typo3/cms-core": "^12.4 || ^13.0 || ^14.3",
    "typo3/cms-fluid-styled-content": "^12.4 || ^13.4 || ^14.3",
    "b13/container": "^3.1.10 || ^3.2.0",
    "wtl/hio-typo3-connector": "^1.0.7 || ^1.1.1"
}
```

```php
// ext_emconf.php
'constraints' => [
    'depends' => [
        'typo3' => '12.4.0-14.3.99',
    ],
],
```

> **Konvention:** In `ext_emconf.php` wird die maximal getestete TYPO3-Version eingetragen.
> `14.3.99` signalisiert, dass das Paket für alle v14-Releases freigegeben ist (noch kein
> konkretes Patch-Level bekannt). In `composer.json` wird der `^`-Operator verwendet, damit
> Composer automatisch neuere kompatible Versionen auflöst.

#### Data Processors

Das Paket enthält zwei Data Processors, die in TypoScript-`FLUIDTEMPLATE`-Definitionen
eingebunden werden:

| Klasse | Zweck |
|---|---|
| `DataProcessing\PublicationDataProcessor` | Bereitet Publikationsdaten für das Fluid-Template auf (Sortierung, Gruppierung, Link-Generierung) |
| `DataProcessing\ProjectDataProcessor` | Bereitet Projektdaten für das Fluid-Template auf |

**Verwendung in TypoScript:**

```typo3_typoscript
10 = TYPO3\CMS\Frontend\DataProcessing\FlexFormProcessor
10.fieldName = pi_flexform
10.as = flexform

20 = Wtl\HioTypo3ConnectorWtl\DataProcessing\PublicationDataProcessor
20.as = publications
```

#### Backend-Vorschau-Renderer

Die Klassen in `Classes/Backend/Preview/` implementieren das TYPO3-Interface
`PreviewRendererInterface` und werden über `TCA/Overrides/tt_content.php`
dem jeweiligen CType zugeordnet. Sie liefern eine kompakte HTML-Vorschau
im Page-Modul.

#### TypoScript: Sets vs. klassisches TypoScript

Das Paket stellt **zwei Site Sets** bereit:

| Set-Name | config.yaml | Inhalt |
|---|---|---|
| `wtl/hio-typo3-connector-frontend` | `Configuration/Sets/HioTypo3ConnectorFrontend/` | Vollständiges Rendering (Templates, DataProcessors, Assets). Bindet automatisch `wtl/hio-typo3-connector` als Set-Abhängigkeit ein. |
| `wtl/hio-typo3-connector-frontend-css` | `Configuration/Sets/HioTypo3ConnectorFrontendCss/` | Optionales CSS-Bundle (Standard-Styling). Separat einbindbar, falls eigenes Design verwendet wird. |

**Einbindung über Site Sets (empfohlen, TYPO3 v12+):**

Im Site-Konfigurationsmodul (oder `config/sites/<site>/config.yaml`) das Set
`wtl/hio-typo3-connector-frontend` als Abhängigkeit hinzufügen:

```yaml
# config/sites/main/config.yaml
dependencies:
  - wtl/hio-typo3-connector-frontend
  # CSS optional:
  # - wtl/hio-typo3-connector-frontend-css
```

Dadurch wird `wtl/hio-typo3-connector` automatisch mitgeladen (Set-Dependency in
`Configuration/Sets/HioTypo3ConnectorFrontend/config.yaml`).

**Einbindung über klassisches TypoScript (TYPO3 v12/v13 alternativ):**

```typo3_typoscript
@import 'EXT:hio_typo3_connector_wtl/Configuration/TypoScript/setup.typoscript'
```

> **Empfehlung für neue Projekte:** Site Sets verwenden – sie ersetzen statische Templates
> und sind expliziter in der Abhängigkeitsverwaltung.
> **Bestehende Projekte:** klassisches TypoScript weiterhin voll unterstützt.

#### Fluid-Templates anpassen (Agentur-Entwickler)

Die Standard-Templates liegen in `Resources/Private/`. Für projektspezifisches Styling
sollten die Templates **nicht direkt bearbeitet** werden, sondern über TypoScript
überschrieben werden:

```typo3_typoscript
plugin.tx_hiotypo3connector.view {
    templateRootPaths.10  = EXT:my_site/Resources/Private/HioTemplates/
    partialRootPaths.10   = EXT:my_site/Resources/Private/HioPartials/
    layoutRootPaths.10    = EXT:my_site/Resources/Private/HioLayouts/
}
```

So bleiben Projekt-Templates bei Updates des Pakets erhalten.

---

## Migration auf TYPO3 v14

> ⚠️ **Achtung für Redakteure und Entwickler**

Dieses Paket folgt der Migration von `wtl/hio-typo3-connector` (siehe dort für Details).

**Was sich für das Frontend-Paket ändert:**

- Die Data Processors und Fluid-Templates sind **versionsunabhängig** und benötigen
  keine Anpassung.
- Die Backend-Preview-Renderer sind **versionsunabhängig** und benötigen keine Anpassung.
- Das TypoScript-Set `wtl/hio-typo3-connector-frontend` bleibt unverändert.
- `b13/container` muss in einer mit TYPO3 v14 kompatiblen Version vorliegen
  (`^13.2.0` oder höher).

**Migrationsschritte (nach dem v14-Upgrade des Gesamtprojekts):**

1. `composer update wtl/hio-typo3-connector wtl/hio-typo3-connector-frontend`
2. TYPO3 Upgrade-Wizards ausführen: `vendor/bin/typo3 upgrade:run`
3. Inhaltselemente auf neue CTypes migrieren (siehe CHANGELOG von `wtl/hio-typo3-connector`)
4. Frontend-Vorschau prüfen – Templates bleiben kompatibel

---

## [1.0.x] – 2024-xx-xx

- Initiale stabile Version mit Unterstützung für TYPO3 v12
- Data Processors: `PublicationDataProcessor`, `ProjectDataProcessor`
- Backend-Vorschau für alle HIO-Publisher-Plugins
- Site Set `wtl/hio-typo3-connector-frontend` und optionales CSS-Set


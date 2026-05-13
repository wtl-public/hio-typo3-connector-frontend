# Changelog – wtl/hio-typo3-connector-frontend

Alle relevanten Änderungen an diesem Paket werden hier dokumentiert.
Das Format orientiert sich an [Keep a Changelog](https://keepachangelog.com/de/1.0.0/).
Die Versionierung folgt [Semantic Versioning](https://semver.org/lang/de/).

Dieses Paket ist das **Frontend-Rendering-Paket** und setzt
`wtl/hio-typo3-connector` als Abhängigkeit voraus.
Es stellt Fluid-Templates, Data Processors und Backend-Vorschau-Renderer bereit.

---

## [1.3.0] – 2026-05-12

### ⚠️ Breaking Change – Link-Handling für Personen und Organisationseinheiten geändert

Bisher wurden Detail-Links (z. B. zur Personen-Detailseite) über die **HISinOne `object_id`**
als URL-Parameter aufgebaut. Ab dieser Version werden Links über die **TYPO3-interne `uid`**
des Datensatzes generiert, um sprechende URLs via RouteEnhancer zu ermöglichen.

**Was bedeutet das konkret?**

- Fluid-Templates und Partials, die den alten `objectId`-Parameter zum Aufbau von Links
  verwendet haben, wurden auf `uid`-basierte Links umgestellt.
- Bestehende URLs, die eine `object_id` als Parameter enthalten (z. B.
  `?tx_hiotypo3connector_selectedperson[objectId]=12345`), werden **nicht mehr korrekt
  aufgelöst** und liefern eine leere Detailseite oder einen 404-Fehler.
- Dies betrifft insbesondere **gespeicherte/geteilte Links** sowie
  **externe Verlinkungen** auf Detailseiten.

**Migrationsschritte:**

1. Prüfen, ob eigene Template-Overrides den `objectId`-Parameter verwenden – diese müssen
   auf `uid` umgestellt werden.
2. Bekannte Altlinks durch TYPO3-Redirects oder `.htaccess`-Weiterleitungen abfangen.
3. RouteEnhancer-Konfiguration einrichten (Beispiel siehe
   `ExampleConfigs/RouteEnhancer.yaml` in `wtl/hio-typo3-connector`).

---

### Hinweise für Redakteure

Für Redakteure ändert sich im täglichen Umgang nichts. Die Detailseiten von Personen,
Projekten, Publikationen und Organisationseinheiten sind wie gewohnt erreichbar –
die URLs sind nun jedoch **sprechend** und suchmaschinenfreundlicher (sofern RouteEnhancer
konfiguriert wurden).

Der neue „Zurück"-Button auf Detailseiten navigiert intelligent: Wurde die Seite aus dem
gleichen Ursprung aufgerufen, wird der Browser-Verlauf genutzt. Andernfalls wird auf die
konfigurierte Listenseite verlinkt.

### Hinweise für Agentur-Entwickler

#### TASK: Link-Handling auf uid-Basis und RouteEnhancer-Vorbereitung (HIO-347)

Alle Fluid-Templates und Partials, die Links zu Detailseiten erzeugen, wurden überarbeitet:

- **Personen** (`Person/List/Item.html`, `Person/Show.html`, alle `PersonLink.html`-Partials)
- **Projekte** (`Project/List/Item.html`, `Project/Show.html`, `Project/List/Person.html`)
- **Publikationen** (`Publication/List/Item.html`, `Publication/Show.html`,
  `Publication/List/Person.html`, `Publication/Show/PersonLink.html`)
- **Organisationseinheiten** (`OrgUnit/List/Item.html`, `OrgUnit/Show.html` und alle
  zugehörigen Tab-Partials)
- **SelectedPerson/SelectedOrgUnit**-Partials (Projekt- und Publikations-Items)

Alle Links bauen nun auf der `uid` des Datensatzes auf. In Kombination mit den
RouteEnhancern aus `ExampleConfigs/RouteEnhancer.yaml`
(`wtl/hio-typo3-connector`) entstehen daraus sprechende URLs wie
`/personen/max-mustermann`.

#### Neues Partial: BackButton

Das neue Partial `Resources/Private/Partials/Navigation/BackButton.html` kapselt die
„Zurück"-Logik für alle Detail-Show-Templates. Es prüft per JavaScript, ob der `document.referrer`
denselben Ursprung hat, und navigiert entsprechend via `history.back()` oder auf die
konfigurierte Listenseite. Das Partial wird von allen Show-Templates eingebunden und
ersetzt die bisherigen inline-Back-Link-Implementierungen.

#### Neuer ViewHelper: FindByPropertyViewHelper

Die neue Klasse `Classes/ViewHelpers/FindByPropertyViewHelper.php` erlaubt es in Fluid,
ein einzelnes Objekt aus einer Collection anhand einer beliebigen Eigenschaft zu finden:

```html
{hio:findByProperty(items: orgUnits, property: 'uid', value: someUid)}
```

Der ViewHelper ist als `hio:findByProperty` registriert und vollständig typsicher
implementiert.

#### Abhängigkeit auf wtl/hio-typo3-connector 1.3.0

Diese Version setzt `wtl/hio-typo3-connector ^1.3.0` voraus, da die neuen Slugs
und das uid-basierte Routing im Connector-Paket implementiert wurden.

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


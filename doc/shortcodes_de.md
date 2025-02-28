# Wie kann ich Shortcodes verwenden um Widgets in WordPress auszugeben?

## Voraussetzungen

* Ein ProvenExpert-Konto.
* Das kostenfreie Wordpress-Plugin ProvenExpert: https://de.wordpress.org/plugins/provenexpert/
* Das WordPress-Plugin muss mit Ihrem ProvenExpert-Konto verbunden sein.

## Grundsätzliches

Shortcodes sind in WordPress wie folgt aufgebaut:

`[shortcode_name attribute="wert"]`

wobei alle Bestandteile hierbei variabel sind und je nach Shortcode unterschiedliche Attribute möglich.

## ProvenExpert Widgets

Die folgenden Widgets stehen als Shortcodes zur Verfügung:

* Awards
* Bar
* Circle
* Landing
* ProSeal
* Seal

Dazu im folgenden Details zu deren Shortcode-Aufbau und Möglichkeiten.

### Awards

Aufbau: `[provenexpert_awards width="100"]`

Mögliche Attribute:

* width
  * Legt die Breite fest.
  * Zahlen-Wert in Pixel
  * Standard-Wert: 100
* fixed
  * Legt fest, ob das Widgets fixiert positioniert wird oder nicht.
  * Wert 1 oder 0
  * Standard-Wert: 0
* origin
  * Legt fest, in welcher Richtung das Widget positioniert wird.
  * Werte "top" oder "bottom"
  * Standard-Wert: "top"
* position
  * Legt die Widget-Position fest.
  * Zahlen-Wert
  * Standard-Wert: 0
* award_type
  * Legt den Award-Typ fest der angezeigt werden soll.
  * Mögliche Werte: "recommend", "topservice" oder "toprecommend"
  * Standard-Wert: "recommend"

### Bar

Aufbau: `[provenexpert_bar stye="black"]`

Mögliche Attribute:

* style
  * Legt den Style des Widgets fest.
  * Werte "white" oder "black"
  * Standard-Wert: "white"
* feedback
  * Legt fest, ob Feedback mit angezeigt werden soll.
  * Wert 1 oder 0
  * Standard-Wert: 0

### Circle

Aufbau: `[provenexpert_circle]`

Mögliche Attribute:

* width
  * Legt die Breite fest.
  * Zahlen-Wert in Pixel
  * Standard-Wert: 100
* fixed
  * Legt fest, ob das Widgets fixiert positioniert wird oder nicht.
  * Wert 1 oder 0
  * Standard-Wert: 0
* origin
  * Legt fest, in welcher Richtung das Widget positioniert wird.
  * Werte "top" oder "bottom"
  * Standard-Wert: "top"
* position
  * Legt die Widget-Position fest.
  * Zahlen-Wert
  * Standard-Wert: 0
* side
  * Legt die seitliche Ausrichtung des Widgets fest.
  * Mögliche Werte: "left" oder "right"
  * Standard-Wert: "left"

### Landing

Aufbau: `[provenexpert_landing stye="black"]`

Mögliche Attribute:

* style
  * Legt den Style des Widgets fest.
  * Werte "white" oder "black"
  * Standard-Wert: "white"
* feedback
  * Legt fest, ob Feedback mit angezeigt werden soll.
  * Wert 1 oder 0
  * Standard-Wert: 0
* Avatar
  * Legt fest, ob der Avatar angezeigt werden soll.
  * Wert 1 oder 0
  * Standard-Wert 0

### Landing

Aufbau: `[provenexpert_proseal]`

Mögliche Attribute:

* bannercolor
  * Legt die Farbe für das Banner fest.
  * Wert muss ein gültiger Hexcode einer Farbe sein, z.B. #121212
  * Standard-Wert: #000000
* textcolor
  * Legt die Textfarbe fest.
  * Wert muss ein gültiger Hexcode einer Farbe sein, z.B. #121212
  * Standard-Wert: #ffffff
* showbackpage
  * Legt fest, ob die Rückseite angezeigt werden soll.
  * Wert 1 oder 0
  * Standard-Wert: 0
* showreviews
  * Legt fest, ob Reviews angezeigt werden sollen.
  * Wert 1 oder 0
  * Standard-Wert: 0
* hidedate
  * Legt fest, ob das Datum versteckt werden soll.
  * Wert 1 oder 0
  * Standard-Wert: 0
* hidename
  * Legt fest, ob der Name versteckt werden soll.
  * Wert 1 oder 0
  * Standard-Wert: 0
* googlestars
  * Legt fest, ob Google Bewertungssterne mit angezeigt werden sollen.
  * Wert 1 oder 0
  * Standard-Wert: 0
* displayreviewerlastname
  * Legt fest, ob der Name vom letzten Reviewer angezeigt werden soll.
  * Wert 1 oder 0
  * Standard-Wert: 0
* bottom
  * Legt den Abstand von unten fest.
  * Zahlen-Wert
  * Standard-Wert: 100
* stickytoside
  * Legt fest, ob das Widget an der Seite angezeigt werden soll.
  * Wert 1 oder 0
  * Standard-Wert: 0
* zindex
  * Legt die Ebenen-Zahl fest, um Überlappungen in der Ausgabe zu vermeiden.
  * Zaheln-Wert
  * Standard-Wert: 9999

### Seal

Aufbau: `[provenexpert_seal width="100"]`

Mögliche Attribute:

* width
  * Legt die Breite fest.
  * Zahlen-Wert in Pixel
  * Standard-Wert: 100
* fixed
  * Legt fest, ob das Widgets fixiert positioniert wird oder nicht.
  * Wert 1 oder 0
  * Standard-Wert: 0
* origin
  * Legt fest, in welcher Richtung das Widget positioniert wird.
  * Werte "top" oder "bottom"
  * Standard-Wert: "top"
* position
  * Legt die Widget-Position fest.
  * Zahlen-Wert
  * Standard-Wert: 0
* side
  * Legt die seitliche Ausrichtung des Widgets fest.
  * Mögliche Werte: "left" oder "right"
  * Standard-Wert: "left"
* seal_type
  * Legt den Siegel-Typ fest.
  * Werte: "portrait", "square" oder "landscape"
  * Standard-Wert: "portrait"
* slider
  * Legt fest, ob der Slider angezeigt werden soll oder nicht.
  * Werte 1 oder 0
  * Standard-Wert: 0

## Fragen?

Bei Fragen zu Shortcodes gerne im Supportforum des WordPress-Plugins melden: https://wordpress.org/support/plugin/provenexpert/

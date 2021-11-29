# Rhein Neckar Kreis Vaccine Bot

Bot der die interne API von https://c19.rhein-neckar-kreis.de/impftermin abfragt und Benachrichtigungen über freie Impftermine versendet.

API Stand 27.11.2021


## SaaS
Ich habe diesen Bot auf meinem Server installiert und folgende Pushover Subscription eingerichtet:


We möchte kann diese Abonnieren. Dafür muss nur ein Account bei pushover.net eingerichtet und über den Link https://pushover.net/subscribe/CoronaVaccine-bv894h72chwwxij die Subscription eingerichtet werden.
Pushover.net bietet einen kostenfreien 30 Tage Testzeitraum an. Dieser reicht für die Subscription aus.

## Konfiguration

1. `config.example.php` umbenennen in `config.php`
2. Pushover API Keys in `config.php` eintragen

# API Abfragen
`index.php` per Konsole oder Browser aufrufen. Idealerweise per Conjob.

## Benachrichitungen
Benachrichtigungen laufen aktuell über pushover.net
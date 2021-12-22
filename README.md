# broken_link_scanner
Script for checking pages for links to online shops and then checking these shoplinks for certain text.

Dieses Script extrahiert Urls aus einer Webseite und scannt gewünschte Anbieter (siehe config.php) nach einem Text.
Beispielsweise die Amazon Seite nach "Produkt ist nicht verfügbar". Vorraussetzung ist ein Webserver(apache/nginx WAMP(windows)oder andere) mit php

Das Script funktioniert nicht nur mit Amazon sonder nauch für andere Anbieter! Ebay fehlt noch, brauch Beispiele für Seiten die "broken" sind. Dann inkludiere ich das.

# Konfiguration config.php
1. Anbieter und Text angeben, Beispiele vorhanden. Für Amazon keine Änderungen nötig!
2. Sitemap angeben die Seite listet. Zeile 16 config.php

Das Script muss auf einer shell/dos-box laufen gelassen werden.
In den Ordner navigieren cd /ordnername und dann "php linkchecker.php" (ohne Anführunsgzeichen) eingeben. Unbedingt die shell/cli/dosbox nutzen, weil php da kein timeout hat.

Das Script überspringt Links die es bereits getestet hat, Effizienz!

## Farben
1. "Toter Rand mit grauem Hintergrund" Produkt ist "nicht mehr verfügbar"
2. "Orangeer Rand" bedeutet, dass es keine produktseite ist! Es ist eine andere Amazon seite wie Suche/Listing etc.
3. "Roter Hintergrund" mit weißer Schrift. 404, ASIN weg. Seite ungültig.
4. Bonus: "Hellblauer Hinmtergrund" bedeutet, dass der Link vorher schon geprüft wurde und aus effizienzgrünen nicht erneut geprüft wird. Das Log kann danach per str+f durchsucht werden.

## Weiteres
1. Ein url_log.txt wird im root erzeut, welches die Seiten listet
2. Im Ordner /results gibt es ein grafisch aufbereitetets Logfile. Das wollen wohl die Meisten nutzen!
3. Lasst das Script niemals auf dem Webserver laufen wo eure Seiten sind! Es kann passieren das Amazon die IP des Servers bannt, weil es wie ein scraper wirkt.
4. Per Zufall pausiert das Script 5-10 Sekunden nach jeder Anfrage. Das soll bans/hammer/throtteling verhindern.

All das kann man auch per PAAPI machen aber dieses Script funtioniert nicht nur mit AMazon sondern mit jeder x-beliebigen Webseite, da es nach speziellen Texten sucht!

-------------------------------------------------------------------------------

This script is for scanning a page for broken links or invalid affiliate links.

# Setup
1. You need to input the sitemap.xml as a list of urls to check. See config.php

After that run the script on a shell/cli because there is no tiemout! If you run it via browser you will get a timeout, doesnt work!

## Colors
1. "Red border with gray background" means a product is not "avaliable no more"
2. "Orange border" means that it is not a Product page. It may be a listing/search/top list
3. "Red background" with white font means 404 error page
4. -"Light blue background" means that this link was already checked before(efficiency). You may want to search
the log page for that link so you see where it is used and replace if neccessary.

## Misc
1. A url_log is written to to root folder
2. -Inside /results you find a html results.html with the scanner results.
3. Don´t abuse it and never run it on the webserver  your site is hosted on! As this can result of amazon banning the servers IP because of scraping attempts.
4. Random sleep 5 - 10 sconds after every request to not hammer and avoid bands as this script could be categorized as a web-scraper.
DO NOT USE IT AS SUCH!


You could do all of this using the PAAPI but the script can easily extended to also work with beay and every other online stores page as it scans for a certain text.



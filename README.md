# broken_link_scanner
Script for checking pages for links to online shops and then checking these shoplinks for certain text.

Dieses Script extrahiert Urls aus einer Webseite und scannt gewünschte Anbieter (siehe config.php) nach einem Text.
Beispielsweise die Amazon Seite nach "Produkt ist nicht verfügbar". Vorraussetzung ist ein Webserver(apache/nginx WAMP(windows)oder andere) mit php

Das Script funktioniert nicht nur mit Amazon sonder nauch für andere Anbieter! Ebay fehlt noch, brauch Beispiele für Seiten die "broken" sind. Dann inkludiere ich das.

Konfiguration config.php
-Anbieter und Text angeben, Beispiele vorhanden. Für Amazon keine Änderungen nötig!
-Sitemap angeben die Seite listet. Zeile 16 config.php

Das Script muss auf einer shell/dos-box laufen gelassen werden.
In den Ordner navigieren cd /ordnername und dann "php linkchecker.php" (ohne Anführunsgzeichen) eingeben. Unbedingt die shell/cli/dosbox nutzen, weil php da kein timeout hat.

Das Script überspringt Links die es bereits getestet hat, Effizienz!

##Farben
-"Toter Rand mit grauem Hintergrund" Produkt ist "nicht mehr verfügbar"
-"Orangeer Rand" bedeutet, dass es keine produktseite ist! Es ist eine andere Amazon seite wie Suche/Listing etc.
-"Roter Hintergrund" mit weißer Schrift. 404, ASIN weg. Seite ungültig.
-Bonus: "Hellblauer Hinmtergrund" bedeutet, dass der Link vorher schon geprüft wurde und aus effizienzgrünen nicht erneut geprüft wird. Das Log kann danach per str+f durchsucht werden.

##Weiteres
-Ein url_log.txt wird im root erzeut, welches die Seiten listet
-Im Ordner /results gibt es ein grafisch aufbereitetets Logfile. Das wollen wohl die Meisten nutzen!
-Lasst das Script niemals auf dem Webserver laufen wo eure Seiten sind! Es kann passieren das Amazon die IP des Servers bannt, weil es wie ein scraper wirkt.
-Per Zufall pausiert das Script 5-10 Sekunden nach jeder Anfrage. Das soll bans/hammer/throtteling verhindern.

All das kann man auch per PAAPI machen aber dieses Script funtioniert nicht nur mit AMazon sondern mit jeder x-beliebigen Webseite, da es nach speziellen Texten sucht!

-------------------------------------------------------------------------------

This script is for scanning a page for broken links or invalid affiliate links.
You need to input the sitemap.xml as a list of urls to check. See config.php

After that run the script on a shell/cli because there is no tiemout! If you run it via browser you will get a timeout, doesnt work!

##Colors
-"Red border with gray background" means a product is not "avaliable no more"
-"Orange border" means that it is not a Product page. It may be a listing/search/top list
-"Red background" with white font means 404 error page
-"Light blue background" means that this link was already checked before(efficiency). You may want to search
the log page for that link so you see where it is used and replace if neccessary.

##Misc
-A url_log is written to to root folder
-Inside /results you find a html results.html with the scanner results.
-Don´t abuse it and never run it on the webserver  your site is hosted on! As this can result of amazon banning the servers IP because of scraping attempts.
-Random sleep 5 - 10 sconds after every request to not hammer and avoid bands as this script could be categorized as a web-scraper.
DO NOT USE IT AS SUCH!


You could do all of this using the PAAPI but the script can easily extended to also work with beay and every other online stores page as it scans for a certain text.



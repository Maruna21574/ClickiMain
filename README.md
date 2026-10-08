# Clicki — webová prezentácia agentúry

Čisté PHP 8 + vanilla JS, bez Node/build kroku. Databáza je SQLite súbor, ktorý sa vytvorí
automaticky pri prvom spustení — netreba nič manuálne zakladať.

## Lokálne spustenie (Laragon)

Projekt je v `c:\laragon\www\Clicki`, Laragon ho automaticky servíruje. Odporúčame nastaviť
mu vlastný virtual host (napr. `clicki.test`), pretože web používa **absolútne cesty od
koreňa domény** (`/assets/...`, `/kontakt.php`...) — pri otvorení cez podpriečinok
(`localhost/Clicki/`) sa štýly a odkazy nenačítajú správne. V Laragone stačí reštartovať
Apache (Menu → Apache → reštart) a mal by sa `clicki.test` vytvoriť automaticky vďaka
"Auto Virtual Hosts".

## Nasadenie na Websupport

1. V administrácii Websupportu over, že balík má **PHP 8.1+** a povolené rozšírenia
   `pdo_sqlite`, `gd`, `fileinfo`, `mbstring` (bežná súčasť ich webhostingu).
2. Cez FTP/File Manager nahraj **celý obsah tohto priečinka** do `public_html`
   (teda tak, aby `index.php` bol priamo v `public_html`, nie v podpriečinku).
3. Over práva na zápis pre priečinky `data/` a `uploads/` (na zdieľanom hostingu zvyčajne
   stačia predvolené práva 755, DB súbor a uploady si vytvára PHP samo).
4. Otvor `https://tvojadomena.sk/inc/config.php` a over, že vracia **403** (blokované
   `.htaccess` súbormi) — to isté pre `/data/clicki.sqlite`. Ak by náhodou vrátilo iný kód,
   over, že hosting povoľuje `.htaccess` (`AllowOverride`) — u Websupportu je to štandard.
5. Otvor `https://tvojadomena.sk/admin/login.php` — pri prvom vstupe sa zobrazí sprievodca
   na vytvorenie administrátorského účtu (meno + heslo). Toto je jediné miesto, kde sa
   prihlasovacie údaje nastavujú — v kóde nie sú žiadne predvolené heslá.
6. V `inc/config.php` uprav `ADMIN_EMAIL` (kam chodia dopyty z kontaktného formulára) a
   `MAIL_FROM` (odosielacia adresa pre `mail()`) na reálnu doménu, aby e-maily neskončili v spame.

## Čo je pripravené na výmenu

- **Portfólio** — reálne projekty sú v lokálnej databáze `data/clicki.sqlite` a ich fotky
  v `uploads/projects/` (ani jedno nie je v gite). Pri nasadení nahraj na hosting aj tieto dva,
  inak začne web s prázdnym portfóliom. Nové projekty sa pridávajú cez `/admin` (Projekty →
  Nový projekt), vrátane fotiek, galérie a odkazu na live ukážku. Vo filtri portfólia sa
  zobrazujú len kategórie, ktoré majú aspoň jeden projekt.
- **Texty webu** (nadpisy, popisy služieb, referencie, FAQ, hodnoty, tím) — `inc/i18n/sk.php`
  a `inc/i18n/en.php`. Referencie klientov (`testimonials`) sú zámerne vzorové — nahraď
  reálnymi, keď budú k dispozícii.
- **Kontaktné údaje** — telefón a adresa v `kontakt.php` sú placeholder, uprav priamo v súbore
  (alebo presuň do `inc/config.php`, ak budeš pridávať ďalšie kontaktné kanály).
- **Sociálne siete** — odkazy vo footeri (`templates/footer.php`) vedú zatiaľ na `#`, doplň
  reálne URL.
- **Logá a favicon** — `assets/img/clicki_logo_white.png`, `clicki_hero.png`,
  `favicon_clicki.png`, `clicki_favicon_small.png` sú tie, čo si nahral na začiatku.

## Architektúra v skratke

- `inc/` — konfigurácia, DB (PDO SQLite, auto-schéma + seed), pomocné funkcie, i18n slovníky.
  Priamy HTTP prístup je zablokovaný cez `inc/.htaccess`.
- `templates/` — hlavička/pätička verejného webu a admin rozhrania.
- `admin/` — chránené rozhranie (vlastná session-based autentifikácia, CSRF na všetkých
  formulároch, žiadny externý framework).
- `assets/img/placeholder.php` — generuje značkové SVG náhľady pre demo projekty; keď má
  projekt nahratú reálnu fotku, tento generátor sa už nepoužije.
- Jazyk (SK/EN) sa prepína cez `?lang=` a ukladá do session; `t()` / `td()` v
  `inc/functions.php` čítajú z `inc/i18n/*.php`.

## Bezpečnosť

Heslá sú hashované (`password_hash`), formuláre majú CSRF token, SQL dotazy idú cez
pripravené PDO príkazy, nahrávané obrázky sa validujú podľa prípony aj skutočného MIME typu
a ukladajú pod náhodným názvom. Priečinky `inc/`, `data/` a spúšťanie PHP v `uploads/` sú
zablokované cez `.htaccess`.

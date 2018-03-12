# SEO robot pro průběžné denní testování webů

Denně se na webech, kde pracujete na SEO může měnit fůra věcí, protože obvykle nemáte pod kontrolou všechny úpravy na webu - běžné releasy, hot fixy, atp.
SEO robot umí otestovat vámi zadaná pravidla pro typové stránky webu jako:
* Vrací URL správný HTTP kód?
* Je na webu správně nastaven titulek, popisek, meta robots, OG tagy, atd. ?
* Je soubor robots.txt dostupný, kde má být a nezměnil se?
* Je v HTML ten obsah, který tam má být?

SEO robot ve vámi vybraném intervalu projde web a otestuje vše, co mu zadáte a když narazí na chybu, tak vám pošle email:

![Náhled emailu](https://www.hlavinka.cz/seoskoleni/nahled-emailu.png "Title")

Tak se můžete spolehnout na to, že vám pod rukama nikdo nemění technické SEO a obsah stránek.

Testy dělejte na produkci, ale naučte své programátory, aby před každým releasem test spustili na testovacím prostředí. Zachytíte tak chyby, které mohou na webu znamenat problémy.


# Instalace Linux
Požadavky:
* běžící Linux server s PHP a Cron

## Jak na to?
* Stáhněte si ZIP - vpravo nahoře
* nainstalujte, kam jste zvyklí
* do cron si přidejte spouštění skriptu, např. 0,20,40 * * * * root curl http://web-kde-je-seo-robot-nainstalovany.cz/index.php?id=sbazar.cz >> /dev/null 2>&1

# Nastavení
TBD



# SEO robot pro průběžné každodenní testování webů a monitoring

Denně se na webech, kde pracujete na SEO může měnit fůra věcí, protože obvykle nemáte pod kontrolou všechny úpravy na webu - běžné releasy, hot fixy, atp.
SEO robot umí otestovat vámi definovaná pravidla pro typové stránky webu jako:
* Vrací URL správný HTTP kód?
* Je na webu správně nastaven titulek, popisek, meta robots, OG tagy, atd. ?
* Je soubor robots.txt dostupný, je tam, kde má být a nezměnil se?
* Je v HTML takový obsah, jaký tam má být?

SEO robot projde web a otestuje vše, co mu zadáte a když narazí na chybu, tak vám pošle email:

![Náhled emailu](https://www.hlavinka.cz/seoskoleni/nahled-emailu.png "Title")

Tak se můžete spolehnout na to, že vám pod rukama nikdo nemění technické SEO a obsah stránek.

## Jak správně nastavit proces testování SEO?
### Testy v testovacím prostředí
Už teď pravděpodobně testujete web předtím, než nasadíte novou verzi do provozu. Testujete, jestli fungují všechny stránky, stavy aplikace, formuláře, emaily, jestli web vypadá dobře i v IE, atd. Na SEO se většinou nemyslí a netestuje se, jestli se při úpravách neudělala chyba, která rozbije už hotové technické nastavení SEO.

**Naučte vaše programátory testovat na testu i SEO** (klidně i jinak, než SEO robotem).

SEO robot může běžet na testovacím prostředí a před nasazením jen ručně, nebo automaticky spusťte předem definované testy, které otestují SEO na TESTovací verzi webu. Pokud se stane nějaká chyba - pak pošle email SEO konzultantovi a rovnou i odpovědné osobě za web - produktový manažer, webmaster, kdokoliv, kdo může odhalenou chybu začít opravovat (případně rozhodnout, že je to maličkost, která nemá vliv na nasazení.

Odhalení chyby na testu je výrazně lepší, než chybu najít až po nasazení v provozu.

### Testy v produkčním prodstředí
Druhý typ testů v produkci je průběžný a spouští se automaticky Cronem každý den (v Seznamu to mám nastaveno každý den co 20 minut). Tím  se nejpozději do 20 minut po změné na webu dozvím, že se změnilo něco, co může ovlivnit SEO. To je relativně hodně rychlé a snižuje to pŕípadné problémy.

# Instalace
## Požadavky na prostředí:
* Linux server s PHP a Cron (třeba VPS)

## Jednoduchá instalace pro začátečníky:
* Stáhněte .ZIP - vpravo nahoře
* Rozbalte SEO robota do adresáře, kde máte webové aplikace
* Nastavte SEO robota tak, aby byl dostupný na URL dostupné z Cron - může být i veřejně dostupný, ale pak je lepší nastavit přístup jen z Cron a z vašich IP, aby se tam nedostal někdo, kdo tam nemá co dělat :) 
* Nastavte práva k souborům (chmod)

## Instalace pro profíky
* programátoři, webaři a jiní profíci, vy ode mne návod k instalaci nepotřebujete ;-)

# Nastavení
TBD



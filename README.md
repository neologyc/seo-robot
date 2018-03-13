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
## 1. Nastavení emailů a SMTP 
Nastavení najdete v **/settings/settings.php v poli $generalSettings**.
Při chybě posílá SEO robot email - nejprve nastavíme SMTP, aby se emaily mohly posílat. Pro posílání emailů používá SEO robot knihovnu PHPmailer - tzn. nastavení SEO robota je rochu ořezané nastavení PHPmailera.
Příklad nastavení pro Gmail (to je takové nejvíce specifické):
```
'smtpDebugLevel' => 4, // hodnota 4 je nejvíce ukecaná a řekne vám toho hodně. Po otestování nastavte zpět na 0. 
'emailHost' => 'smtp.gmail.com',
'emailPort' => 587,
'emailSMTPSecure' => 'tls',
'emailSMTPAuth' => true,
'emailUsername' => 'nove-vytvoreny-email-pro-posilani-notifikaci@gmail.com',
'emailPassword' => 'heslodoemailu', // do budoucna bude xoauth2, tzn. heslo do emailu nebude nikde vidět
```
! Pro odesílání emailů je nejlepší vytvořit si úplně nový email na Seznamu, nebo Gmailu. 

## 2. Vytvoření nového testu

### 2.1 Založení nového projektu
Nový test vytvoříte v **/settings/settings.php v poli $testsSettings**.
Stačí přidat nové pole s názvem služby do pole $testsSettings. Název služby je důležitý - podle něj budete pak test spouštět.

```
'sbazar.cz' => // ID projektu
    array( 	
        'robotsTxtURL' => 'https://www.sbazar.cz/robots.txt', // cesta k robots.txt na serveru
        'robotsTxtFile' => './settings/robots.txt/sbazar.txt', // cesta k robots.txt uloženému lokálně 
        'testRules' => './settings/tests/sbazar.txt', // cesta k souboru se samotnými SEO testy
        'curl_useragent' => 'SEO test', // user-agent
        'email' => 'jaroslav.hlavinka@firma.seznam.cz', // adresát
    ),
```
Detailně vysvětleno:
* **ID projektu** - musí být unikátní pro každý projekt. Tímto ID se spouští projekty např. /index.php?id=sbazar.php
* **cesta k robots.txt na serveru**
* **cesta k robots.txt uloženému lokálně** - pro ověření, jestli se robots.txt na serveru nezměnil. Při tvorbě testu stáhněte soubor robots.txt z webu a uložte ho SEO robotovi do adresáře /settings/robots.txt/
* **user-agent** - toho můžete měnit a monitorovat případné specifické chování pro jiné useragenty -Facebot, GoogleBot mobile, SeznamBot, atd. Pro test s jiným user-agentem si založte nový projekt 
* **adresát** - komu se pošle email v případě chyby v testech

### 2.2 Vytvoření souboru s testy




**Tip:** pro první spuštění si udělejte v testech záměrnou chybu, aby se email poslal a vy ho viděli

### 2.3 Spouštění testů
Každá ze služeb se spouští zvláště. Nejsou nijak zřetězeny.

Já doporučuji testy spouštět takto často:
* velký web, kde se pořád něco děje (eshop, atp.) - co 20 minut
* malý web, kde se nic neděje - stačí jednou denně (pokud to vůbec má smysl monitorovat SEO robotem)
* střední web - někde mezi 20 minutami a jedním dnem 

Spuštění uděláte nejlépe pomocí Cron. 
* Pro Linux: 
```
0,20,40 * * * * root curl https://seo.dev.dszn.cz/seorobot/index.php?id=sbazar.cz >> /dev/null 2>&1
2,22,42 * * * * root curl https://seo.dev.dszn.cz/seorobot/index.php?id=zbozi.cz >> /dev/null 2>&1
4,24,44 * * * * root curl https://seo.dev.dszn.cz/seorobot/index.php?id=firmy.cz >> /dev/null 2>&1
6,26,46 * * * * root curl https://seo.dev.dszn.cz/seorobot/index.php?id=firmy-mobilni.cz >> /dev/null 2>&1


```
* Pro hostované weby: V nastavení hostingu určitě máte naklikávátko Cron - naklikejte každou 

**Tip:** Jednou za nějaký čas si spusťte testovací skript napříč všemi vašimi weby, který projde z každého webu dvě URL - jednu bez chyby, druhou se záměrnou chybou. Takový monitoring funkčnosti SEO robota - abyste věděli, že stále funguje a nežili v milé nevědomosti, že když nechodí emaily, tak je to dobře. Obvykle není :-)


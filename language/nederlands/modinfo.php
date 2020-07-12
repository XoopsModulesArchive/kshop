<?php
// ver 1.15
define("_MI_KSHOP_USERS","Gebruikers");
define("_MI_KSHOP_CATEGORY","Categorieën");
define("_MI_KSHOP_PRODUCTS","Producten");
define("_KS_DEBUG","Debug");
define("_KS_DEBUGDSC","Aangezet, krijg je extra informatie over Kshop");
define("_KS_SHOPNAME","k shop");
define("_KS_SHOPNAMEDSC","De naam van jouw winkel");
define("_KS_SHOWSEARCH","Bekijk zoeken");
define("_KS_SHOWSEARCHDSC","Aangezet, de zoek box zal in je winkel verschijnen.");
define("_KS_MAILORDER","Email voor bestellingen");
define("_KS_MAILORDERDSC","Dit is het emailadres waarnaar de bestellingen verzonden worden.");
define("_KS_MAILRETURN","Email voor retouren");
define("_KS_MAILRETURNDSC","Het emailadres die klanten gebruiken voor de retouren.");
define("_KS_MAILCONTACT","Email voor Contact");
define("_KS_MAILCONTACTDSC","Jouw contact email.");
define("_KS_CURRENCY","Munteenheid");
define("_KS_CURRENCYDSC","Munteenheid kan in elke vorm, maar moet kort. Bijv: gebruik $ en niet Dollars");
define("_KS_CURRENCYFIRST","Munteenheid eerst");
define("_KS_CURRENCYFIRSTDSC","Dit zet de munteenheid voor het bedrag. Bijv: EUR 100.00");
define("_KS_MINORDER","Minimum bestelling");
define("_KS_MINORDDSC","Voor gebruikers om een minimum bestelbedrag te eisen, zet dit op dat bedrag.");
define("_KS_PAYCOD","Betaalmethode 1 (Rembours)");
define("_KS_PAYCODDSC","Betaalmethode is ON by default");
define("_KS_PAYINVOICE","Betaalmethode 2 (Factuur)");
define("_KS_PAYINVOICEDSC","Betaalmethode is ON by default");
define("_KS_PAYPREPAID","Betaalmethode 3 (Vooruitbetaling)");
define("_KS_PAYPREPAIDDSC","Betaalmethode is ON by default");
define("_KS_DEFCOUNTRY","Default land");
define("_KS_DEFCOUNTRYDSC","De naam moet EXACT gelijk zijn aan die in de lijst. Hoofdletters moeten in acht genomen worden."); // changed for 2.2
define("_KS_KMAILOPTION","Kies email methode");
define("_KS_KMAILOPTIONDSC","Kshop kan email verzenden door z'n eigen systeem of door het Xoops systeem");
define("_KS_KMAILOPTION_SEL1","Kshop mail");
define("_KS_KMAILOPTION_SEL2","Xoops mail");
define("_KSHOP_CAT_SETTINGS","Instellingen");
define("_KSHOP_CAT_SETTINGS_DSC","Algemene instellingen voor Kshop");
define("_KSHOP_CAT_PAYMENT","Betaling");
define("_KSHOP_CAT_PAYMENT_DSC","Betalingsinstellingen voor kshop");

//ver 1.17  -----------------------------------------------------------------------

define("_KSHOP_CAT_REQUIRED","Vereiste velden voor klant bestelling");
define("_KSHOP_CAT_REQUIRED_DSC","Je kan hier instellen welke velden vereist zijn voor je klanten om in te vullen. Email is altijd mandatory voor kshop om te werken, daarom zie je die niet.");

define("_KS_REQFIRSTNAME","Vereist veld: Voornaam");
define("_KS_REQFIRSTNAMEDSC","Voornaam");
define("_KS_REQLASTNAME","Vereist veld: Achternaam");
define("_KS_REQLASTNAMEDSC","Achternaam");
define("_KS_REQSTREET","Vereist veld: Straat");
define("_KS_REQSTREETDSC","Straat");
define("_KS_REQZIP","Vereist veld: Postcode");
define("_KS_REQZIPDSC","Postcode");
define("_KS_REQCITY","Vereist veld: Woonplaats");
define("_KS_REQCITYDSC","Woonplaats");
define("_KS_REQCOUNTRY","Vereist veld: Land");
define("_KS_REQCOUNTRYDSC","Land");
define("_KS_REQFAX","Vereist veld: Fax");
define("_KS_REQFAXDSC","Fax");
define("_KS_REQCOMMENTS","Vereist veld: Reactie");
define("_KS_REQCOMMENTSDSC","Reactie");
define("_KS_REQTELEFONE","Vereist veld: Telefoon");
define("_KS_REQTELEFONEDSC","Telefoon");
define("_KS_REQCOMPANY","Vereist veld: Bedrijf");
define("_KS_REQCOMPANYDSC","Bedrijf");

//---------------------------------
// kshop 1.21
define("_MI_KSHOP_MAIN","Hoofd");
define("_MI_KSHOP_PLUGINS","Plugins");
define("_KS_GLOBALTAX","BTW");
define("_KS_GLOBALTAXDSC","Mogelijkheid om een BTW waarde aan te geven wat geldt voot alle producten.");
define("_KSHOP_CAT_LAYOUT","Layout and Design");
define("_KSHOP_CAT_LAYOUT_DSC","Hier kun je design and layout opties definieren zoals hoeveel rijen en er in beeld komen.");
define("_KS_COLPERCAT","Kolommen per Initiele Categorie");
define("_KS_COLPERCATDSC","Defineer hoeveel kolommen getoond worden per initiele categorie");

define("_KS_SHOWCARTDSC","Schakel dit uit als je alleen het block wil gebruiken");
define("_KS_ROWSPERPAGE","Rijen per pagina");
define("_KS_ROWSPERPAGEDSC","Defineer hoeveel rijen getoond moeten worden per pagina. Kshop zal een volgende- en vorige button op de pagina toevoegen om de rest van de resultaten te tonen.");
define("_KS_GROUPSPECIALS","Groep Specials");
define("_KS_GROUPSPECIALSDSC","Dit toont een categorie genaamd Specials en plaatst alle producten gemarkeerd Special OOK in deze categorie, dit betekent dat een product gemarkeerd als Special getoond wordt in 2 categorieen");

define("_KS_ACCEPTRETURN","Vereist accepteer Retouren");
define("_KS_ACCEPTRETURNDSC","Als JA, gebruikers moeten accepteer retouren aanvinken om afrekenen te voltooien.");
define("_KS_ACCEPTTERMS","Vereist accepteer Voorwaarden");
define("_KS_ACCEPTTERMSDSC","Als JA, gebruikers moeten accepteer voorwaarden aanvinken om afrekenen te voltooien.");
define("_KS_SHOWSPECIAL","Toon Special/Nieuwe Items");
define("_KS_SHOWSPECIALDSC","Dit toont specials/new box onderaan de start pagina.");

define("_KS_SUBJECTLINE","Onderwerp regel gebruikt in mail voor bestellingen");
define("_KS_SUBJECTLINEDSC","Dit is het onderwerp van de verzonden email wanneer klanten hun bestelling hebben voltooid.");

/*
define("_KS_BLCK_SHOPCART","Winkelmandje");
define("_KS_BLCK_SHOPCARTDSC","Toont het winkelmandje block");
define("_KS_BLCK_CATEGORIES","Winkel categorieen");
define("_KS_BLCK_CATEGORIESDSC","Toont beschikbare categorieen in Kshop");
*/

define("_KS_PROFILE_FIRSTNAME","Voornaam");
define("_KS_PROFILE_LASTNAME","Achternaam");
define("_KS_PROFILE_ADDRESS","Adres");
define("_KS_PROFILE_COMPANY","Bedrijf");
define("_KS_PROFILE_ZIPCODE","Postcode");
define("_KS_PROFILE_CITY","Woonplaats");
define("_KS_PROFILE_TEL","Telefoon");
define("_KS_PROFILE_FAX","Fax");
define("_KS_PROFILE_EMAIL","Email");
define("_KS_PROFILE_COUNTRY","Land");
define("_KS_PROFILE_FIRSTNAMEDSC","Omschrijving");

define("_MI_KSHOP_ORDERS","Bestellingen");
define("_KS_DECHOU","Aantal decimalen");
define("_KS_DECHOUDSC","Definieer het aantal decimalen in de getoonde prijs. 0 geeft aan dat je geen decimalen wenst.");
define("_KS_EDITOR","Kies editor");
define("_KS_EDITORDESC","Kies welke editor gebruikt moet worden in tekst gebieden. Om dit te kunnen gebruiken moet de editor wel geinstalleerd zijn!");
define("_KS_SLOGAN","Slogan voor je winkel");
define("_KS_SLOGANDESC","Deze tekst wordt getoond op de startpagina.");
define("_KS_THUMBWIDTH","Breedte van thumbnail");
define("_KS_THUMBWIDTHDSC","Breedte van thumbnail.");
define("_KS_THUMBHEIGHT","Hoogte van thumbnails.");
define("_KS_THUMBHEIGHTDSC","Hoogte van thumbnails.");
define("_KS_REQLOGIN","Vereist login");
define("_KS_REQLOGINDSC","Vereist login om te kopen. Als JA, anonieme gebruikers kunnen niet afrekenen of producten toevoegen aan het winkelmandje.");

//kshop 2.20
define("_KS_NUMSPECIALS","Aantal producten in Specials box");
define("_KS_NUMSPECIALSDSC","Defineer het aantal producten dat getoond wordt in de specials box");
define("_KS_INCOTPLUGS","Voegt besteltotaal plugins in mandje totaal?");
define("_KS_INCOTPLUGSDESC","Hier geef je aan of het besteltotaal getoond moet worden in winkelmandje header en block.");

define("_KS_BLKSWSHDESC","Toon korte omschrijving van het product? 1=JA 0=NEE ");
define("_KS_BLKNUMPROD","Aantal producten ");
define("_KS_BLKNUMCOL","Aantal kolommen ");
define("_KS_BLKONLYSPEC","Toon alleen specials? 1=JA 0=NEE ");
define("_KS_MAINMYORDS","Mijn bestellingen");

//kshop 2.2 snapshot B
define("_KS_THUMBLIB","Image Library om thumbnails te maken");
define("_KS_THUMBLIBDSC","Om thumbnail te maken, gebruikt kshop een image library die default GD is. Linux servers hebben the de library voorgeinstalleerd. Windows servers echter hebben geen library geinstalleerd. Dus als je een windows server gebruikt, moet je NetPBM of ImageMagick kiezen, selecteer daarna het path naar je library in de volgende instelling.");
define("_KS_THUMBLIBPATH","Path naar image library");
define("_KS_THUMBLIBPATHDSC","Dit moet je alleen invullen als je een windows server gebruikt of wanneer je kiest voor NetPBM of ImageMagick.");

?>
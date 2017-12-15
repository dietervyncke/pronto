## Pagina's ({{ ?-module_timing }})

Men kan pagina's aanmaken, aanpassen en verwijderen. Pagina's kunnen binnen **drie niveaus** op de website geplaatst worden.
Zo heeft men volledige controle over zowel de hoofdnavigatie als de subnavigatie van elk hoofditem. De navigatie kan eenvoudig
worden aangepast door de pagina's zowel van volgorde als van niveau te verslepen. Pagina's kunnen ook uitgesloten worden van de navigatie, 
deze pagina's zijn dan niet zichtbaar in de navigatie en zijn enkel te bezoeken door expliciet de url van die pagina op te geven.

Bij de aanmaak van een pagina kan men volgende gegevens opgeven:

{{ repeat( 'Add a standard field?' ) }}

* **{{ ?-field( 'title', 'blocks', 'photo' ) }}** (){{ ?-field_required( 'verplicht', 'optioneel' ) }})

{{ if ?-field equals 'title' }}
Dit is een beschrijvende titel voor de pagina. Het CMS zal automatisch een SEO-vriendelijk url genereren op basis van deze titel.
Indien men dit wil bijsturen, kan men deze automatisch gegenereerde url steeds aanpassen.
{{ /if }}

{{ if ?-field equals 'title' }}
Men kan per pagina meerdere tekstblokken toevoegen, aanpassen en verwijderen. Elke tekstblok heeft op zijn beurt weer meerdere opties.

Per tekstblok kan men een foto selecteren, een doorlopende tekst invoeren en een "lees meer"-link voorzien. Per tekstblok kan men ook kiezen of de tekst in één of twee kolommen zal verschijnen.
De "lees meer"-link kan gekoppeld worden aan eender welke andere pagina binnen de website. Zo zorgen we ervoor dat de gebruiker steeds de mogelijkheden toegereikt krijgt om binnen de website verder te surfen.

De volgorde van deze tekstblokken kan achteraf nog worden bepaald door deze in de gewenste volgorde te slepen.
{{ /if }}
	
{{ /repeat }}
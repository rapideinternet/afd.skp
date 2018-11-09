<?php

namespace SIVI\AFDSKP\Enums;

use MyCLabs\Enum\Enum;

class SKPActionTypes extends Enum
{
    public const DOEFUNCTIE = 'doeFunctieVraag';
    public const DOEFUNCTIERESPONSE = 'doeFunctieAntwoord';
    public const GEEFRESULTATENOVERZICHT = 'geefResultatenOverzichtVraag';
    public const GEEFRESULTATENOVERZICHTRESPONSE = 'geefResultatenOverzichtAntwoord';
    public const GEEFRESULTATEN = 'geefResultatenVraag';
    public const GEEFRESULTATENRESPONSE = 'geefResultatenAntwoord';
    public const ONTVANGSTBEVESTIGING = 'ontvangstBevestigingVraag';
    public const ONTVANGSTBEVESTIGINGRESPONSE = 'ontvangstBevestigingAntwoord';
}

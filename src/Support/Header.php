<?php

namespace Inertia\Support;

enum Header : string
{
    case INERTIA = 'X-Inertia';

    case ERROR_BAG = 'X-Inertia-Error-Bag';

    case LOCATION = 'X-Inertia-Location';

    case VERSION = 'X-Inertia-Version';

    case PARTIAL_COMPONENT = 'X-Inertia-Partial-Component';

    case PARTIAL_ONLY = 'X-Inertia-Partial-Data';

    case PARTIAL_EXCEPT = 'X-Inertia-Partial-Except';

    case RESET = 'X-Inertia-Reset';
}
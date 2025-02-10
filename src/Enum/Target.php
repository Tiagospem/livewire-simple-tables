<?php

namespace TiagoSpem\SimpleTables\Enum;

enum Target: string
{
    case BLANK = '_blank';
    case SELF = '_self';
    case PARENT = '_parent';
    case TOP = '_top';
    case NONE = '_none';
}

<?php

namespace App\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;


final class EnumCategory extends AbstractEnumType
{
    public final const VEGETARIEN = 'Végétarien';
    public final const VIANDE = 'Viande';
    

    protected static array $choices = [
        self::VEGETARIEN => 'Végétarien',
        self::VIANDE => 'Viande'
       
    ];
}
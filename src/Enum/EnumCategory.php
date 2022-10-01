<?php

namespace App\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;


final class EnumCategory extends AbstractEnumType
{
    public final const VEGETARIEN = 'Vegetarien';
    public final const VIANDE = 'Viande';
    public final const BOISSON = 'Boisson';
    public final const DESSERT = 'Dessert';
    

    protected static array $choices = [
        self::VEGETARIEN => 'Vegetarien',
        self::VIANDE => 'Viande',
        self::BOISSON => 'Boisson',
        self::DESSERT => 'Dessert'
       
    ];
}
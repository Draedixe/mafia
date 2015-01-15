<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 15/01/2015
 * Time: 00:55
 */

namespace Mafia\RolesBundle\Entity;

abstract class Options_Roles {

    private static $options = array(
        Roles_Enum::SHERIFF =>  array(
                    Options_Roles_Enum::SHERIFF_DETECT_MAFIA_TRIADE => 1,
                    Options_Roles_Enum::SHERIFF_DETECT_TUEUR_EN_SERIE => 1,
                    Options_Roles_Enum::SHERIFF_DETECT_PYROMANE => 1,
                    Options_Roles_Enum::SHERIFF_DETECT_CULTE => 1,
                    Options_Roles_Enum::SHERIFF_DETECT_TUEUR_DE_MASSE => 1
                    )
    );



    public static function getOptions($role)
    {
        return self::$options[$role];
    }
} 
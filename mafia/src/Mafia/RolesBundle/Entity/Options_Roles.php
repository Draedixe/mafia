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
        0 =>  array(array('nom' => 'detectMafiaTriade','active' => true),array('nom' => 'detectTueurEnSerie','active' => true),array('nom' => 'detectPyromane','active' => true),
                    array('nom' => 'detectCulte','active' => true),array('nom' => 'detectTueurDeMasse','active' => true)
                    )
    );



    public static function getOptions($role)
    {
        return self::$options[$role];
    }
} 
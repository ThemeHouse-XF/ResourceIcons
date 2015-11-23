<?php

class ThemeHouse_ResourceIcons_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_ResourceIcons' => array(
                'model' => array(
                    'XenResource_Model_Resource'
                ), 
                'image' => array(
                    'XenForo_Image_Gd'
                ), 
            ), 
        );
    }

    public static function loadClassImage($class, array &$extend)
    {
        $loadClassImage = new ThemeHouse_ResourceIcons_Listener_LoadClass($class, $extend, 'image');
        $extend = $loadClassImage->run();
    }

    public static function loadClassModel($class, array &$extend)
    {
        $loadClassModel = new ThemeHouse_ResourceIcons_Listener_LoadClass($class, $extend, 'model');
        $extend = $loadClassModel->run();
    }
}
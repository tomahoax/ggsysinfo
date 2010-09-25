<?php
/**
 * @author G. Giunta
 * @version $Id: contentstats.php 2570 2008-11-25 11:35:44Z ezsystems $
 * @copyright (C) G. Giunta 2010
 * @license Licensed under GNU General Public License v2.0. See file license.txt
 */

$module = $Params['Module'];
$view = $module->currentView();

// rely on system policy instead of creating our own, but allow also PolicyOmitList
$ini = eZINI::instance();
if ( !in_array( "sysinfo/$view", $ini->variable( 'RoleSettings', 'PolicyOmitList' ) ) )
{
    $user = eZUser::currentUser();
    $access = $user->hasAccessTo( 'setup', 'system_info' );
    if ( $access['accessWord'] != 'yes' )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

require_once( "kernel/common/template.php" );
$tpl = templateInit();
$tpl->setVariable( 'title', sysinfoModule::viewTitle( $view ) );

include( "extension/ggsysinfo/modules/sysinfo/$view.php" );

$Result = array();
$Result['content'] = $tpl->fetch( "design:sysinfo/$view.tpl" );

$Result['left_menu'] = 'design:parts/sysinfo/menu.tpl';
$url1stlevel = array( array( 'url' => 'sysinfo/index',
                             'text' => ezi18n( 'SysInfo', 'System information' ) ) );
if ( $view == 'index' )
{
    $url1stlevel[0]['url'] = false;
    $url2ndlevel = array();
}
else
{
    $url2ndlevel = array( array( 'url' => false,
                                 'text' => ezi18n( 'SysInfo', sysinfoModule::viewName( $view ) ) ) );
}
$Result['path'] = array_merge( $url1stlevel, $url2ndlevel );
?>
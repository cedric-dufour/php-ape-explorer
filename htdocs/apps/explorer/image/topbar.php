<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
/** PHP Application Programming Environment (PHP-APE)
 *
 * <P><B>COPYRIGHT:</B></P>
 * <PRE>
 * PHP Application Programming Environment (PHP-APE)
 * Copyright (C) 2005-2006 Cedric Dufour
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 * </PRE>
 *
 * @package PHP_APE_Explorer
 * @subpackage WUI
 */

/** Image HTML top bar
 */

// Start session
session_start();

// Load PHP-APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/data/load.php' );
require_once( PHP_APE_ROOT.'/lib/util/file/load.php' );
require_once( PHP_APE_ROOT.'/apps/explorer/load.php' );

// Controller
global $roController;
try
{
  $roController =& PHP_APE_Explorer_WorkSpace::useImageController();
  echo $roController->htmlTopBar();
}
catch( PHP_APE_Exception $e )
{
  echo PHP_APE_HTML_Components::htmlUnexpectedException( $e );
}

// End session
session_write_close();

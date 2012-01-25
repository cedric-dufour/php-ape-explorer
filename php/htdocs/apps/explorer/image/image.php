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

/** Image display handler
 */

// Load PHP-APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/data/load.php' );
require_once( PHP_APE_ROOT.'/lib/util/file/load.php' );
require_once( PHP_APE_ROOT.'/lib/util/image/load.php' );
require_once( PHP_APE_ROOT.'/apps/explorer/load.php' );

// Parameters
$amParameters = PHP_APE_Explorer_Image_Controller::getImageParameters();
$sFilePath = PHP_APE_Util_File_Any::encodePath( $amParameters['path'] );
$aiGauge = array_key_exists( 'gauge', $amParameters ) ? $amParameters['gauge'] : null;

// HTTP
PHP_APE_Util_BrowserControl::noCache( filemtime( $sFilePath ) );
if( !is_array( $aiGauge ) )
{
  // ... download
  PHP_APE_Util_BrowserControl::download( $sFilePath );
}
else
{
  // ... check cache
  $sCacheSignature = $sFilePath.'@'.$aiGauge[0].'x'.$aiGauge[1];
  $sCachePath = PHP_APE_CACHE.'/PHP_APE_Explorer_Image#'.sha1( $sCacheSignature ).md5( $sCacheSignature ).'.img';
  if( !file_exists( $sCachePath ) or filemtime( $sCachePath ) <= filemtime( $sFilePath ) )
  {
    //PHP_APE_Util_Image_Any::resampleWithIM( $sFilePath, $aiGauge, $sCachePath );
    PHP_APE_Util_Image_Any::resampleWithGD( $sFilePath, $aiGauge, $sCachePath );
    chmod( $sCachePath, 0600 );
  }

  // ... output
  header( 'Content-Type: '.mime_content_type( $sCachePath ) );
  header( 'Content-Length: '.filesize( $sCachePath ) );
  readfile( $sCachePath );
}

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
 * @subpackage WorkSpace
 */

/** Explorer workspace
 *
 * <P><B>USAGE:</B></P>
 * <P>The following static parameters (properties) are provisioned by this workspace:</P>
 * <UL>
 * <LI><SAMP>php_ape.explorer.title</SAMP>: default title [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.htdocs.url</SAMP>: explorer's root HTML documents URL [default: <SAMP>/php-ape/apps/explorer</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.file.htdocs.url</SAMP>: file explorer's root HTML documents URL [default: <SAMP>php_ape.explorer.htdocs.url</SAMP>.'/file']</LI>
 * <LI><SAMP>php_ape.explorer.image.htdocs.url</SAMP>: image explorer's root HTML documents URL [default: <SAMP>php_ape.explorer.htdocs.url</SAMP>.'/image']</LI>
 * <LI><SAMP>php_ape.explorer.data.path</SAMP>: root data (server) path [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.data.filter</SAMP>: files (basename) filtering regular expression [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.noconf</SAMP>: authorize access to directory with missing configuration file [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.read.any</SAMP>: grant read permission to any user [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.read.dirmatch</SAMP>: grant read permission to user/group matching directory name [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.read.users</SAMP>: read authorized users [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.read.groups</SAMP>: read authorized groups [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.insert.any</SAMP>: grant insert permission to any user [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.insert.dirmatch</SAMP>: grant insert permission to user/group matching directory name [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.insert.users</SAMP>: insert authorized users [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.insert.groups</SAMP>: insert authorized groups [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.update.any</SAMP>: grant update permission to any user [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.update.dirmatch</SAMP>: grant update permission to user/group matching directory name [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.update.users</SAMP>: update authorized users [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.update.groups</SAMP>: update authorized groups [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.delete.any</SAMP>: grant delete permission to any user [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.delete.dirmatch</SAMP>: grant delete permission to user/group matching directory name [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.delete.users</SAMP>: delete authorized users [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.delete.groups</SAMP>: delete authorized groups [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.image.download.any</SAMP>: grant image download permission to any user [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.image.download.dirmatch</SAMP>: grant image download permission to user/group matching directory name [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.image.download.users</SAMP>: image download authorized users [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.auth.image.download.groups</SAMP>: image download authorized groups [default: <SAMP>array()</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.file.controller</SAMP>: file's data page controller [default: '<SAMP>PHP_APE_Explorer_File_Controller</SAMP>']</LI>
 * <LI><SAMP>php_ape.explorer.image.controller</SAMP>: image's data page controller [default: '<SAMP>PHP_APE_Explorer_Image_Controller</SAMP>']</LI>
 * <LI><SAMP>php_ape.explorer.image.size.list</SAMP>: image (maximum) size in list view [pixels; default: <SAMP>50</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.image.size.detail.choices</SAMP>: choosable image (maximum) size(s) in detail view [pixels; default: <SAMP>array( 400 )</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.image.size.detail</SAMP>: image (maximum) size in detail view [pixels; default: <SAMP>400</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.image.size.thumbnail</SAMP>: image (maximum) size in thumbnail [pixels; default: <SAMP>100</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.image.thumbnail.list.use</SAMP>: use thumbnail in list view [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.image.thumbnail.detail.use</SAMP>: use thumbnail in detail view [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.frameset.topbar.height</SAMP>: frameset top-bar's dimension (height) [pixels; default: <SAMP>90</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.frameset.leftbar.use</SAMP>: frameset left-bar's usage [default: <SAMP>true</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.frameset.leftbar.width</SAMP>: frameset left-bar's dimension (width) [pixels; default: <SAMP>180</SAMP>]</LI>
 * <LI><SAMP>php_ape.explorer.directory.browser.use</SAMP>: directory browser usage [default: <SAMP>true</SAMP>]</LI>
 * </UL>
 *
 * <P><B>USAGE:</B> directory-specific configuration</P>
 * <P>Most work space parameters can be overridden on a per-directory basis, by using the so-called <I>directory-specific configuration file</I>,
 * named <SAMP>.php-ape.explorer.conf.php</SAMP>.</P>
 *
 * <P><B>USAGE:</B> file/directory permissions</P>
 * <P>PHP-APE explorer is executed with the UID/GID of the enclosing HTTP daemon. Files and directories MUST thus have permissions allowing
 * this UID/GID to perform the required (read/insert/update/delete) actions.</P>
 *
 * @package PHP_APE_Explorer
 * @subpackage WorkSpace
 */
class PHP_APE_Explorer_WorkSpace
extends PHP_APE_HTML_WorkSpace
{

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Work space singleton
   * @var PHP_APE_Explorer_WorkSpace */
  private static $oWORKSPACE;

  /** (File) controller singleton
   * @var PHP_APE_Explorer_File_Controller */
  private static $oCONTROLLER_FILE;

  /** (Image) controller singleton
   * @var PHP_APE_Explorer_Image_Controller */
  private static $oCONTROLLER_IMAGE;


  /*
   * METHODS: factory
   ********************************************************************************/

  /** Returns the (singleton) environment instance (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @return PHP_APE_Explorer_WorkSpace
   */
  public static function &useEnvironment()
  {
    if( is_null( self::$oWORKSPACE ) ) self::$oWORKSPACE = new PHP_APE_Explorer_WorkSpace();
    return self::$oWORKSPACE;
  }

  /** Returns the (singleton) file controller instance (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @return PHP_APE_Explorer_File_Controller
   */
  public static function &useFileController()
  {
    if( is_null( self::$oCONTROLLER_FILE ) )
    {
      $sController = self::useEnvironment()->getStaticParameter( 'php_ape.explorer.file.controller' );
      PHP_APE_Resources::loadDefinition( $sController );
      $oController = new $sController();
      if( !( $oController instanceof PHP_APE_Explorer_File_Controller ) )
        throw new PHP_APE_Explorer_Exception( __METHOD__, 'Invalid controller; Class: '.get_class( $oController ) );
      self::$oCONTROLLER_FILE =& $oController;
    }
    return self::$oCONTROLLER_FILE;
  }

  /** Returns the (singleton) image controller instance (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @return PHP_APE_Explorer_Image_Controller
   */
  public static function &useImageController()
  {
    if( is_null( self::$oCONTROLLER_IMAGE ) ) 
    {
      $sController = self::useEnvironment()->getStaticParameter( 'php_ape.explorer.image.controller' );
      PHP_APE_Resources::loadDefinition( $sController );
      $oController = new $sController();
      if( !( $oController instanceof PHP_APE_Explorer_Image_Controller ) )
        throw new PHP_APE_Explorer_Exception( __METHOD__, 'Invalid controller; Class: '.get_class( $oController ) );
      self::$oCONTROLLER_IMAGE =& $oController;
    }
    return self::$oCONTROLLER_IMAGE;
  }


  /*
   * METHODS: verification
   ********************************************************************************/

  /** Verify and sanitize the supplied parameters
   *
   * @param array|string $rasParameters Input/output parameters (<B>as reference</B>)
   */
  protected function _verifyParameters( &$rasParameters )
  {
    // Parent environment
    parent::_verifyParameters( $rasParameters );

    // Default title
    if( array_key_exists( 'php_ape.explorer.title', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.title' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = null;
    }

    // Explorer's root HTML documents URL
    if( array_key_exists( 'php_ape.explorer.htdocs.url', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.htdocs.url' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = '/php-ape/apps/explorer';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // File explorer's root HTML documents URL
    if( array_key_exists( 'php_ape.explorer.file.htdocs.url', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.file.htdocs.url' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = $rasParameters[ 'php_ape.explorer.htdocs.url' ].'/file';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Image explorer's root HTML documents URL
    if( array_key_exists( 'php_ape.explorer.image.htdocs.url', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.image.htdocs.url' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = $rasParameters[ 'php_ape.explorer.htdocs.url' ].'/image';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Root data (server) path
    if( array_key_exists( 'php_ape.explorer.data.path', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.data.path' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = null;
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Files (basename) filtering regular expression
    if( array_key_exists( 'php_ape.explorer.data.filter', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.data.filter' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = null;
    }

    // Authorize access to directory with missing configuration file
    if( array_key_exists( 'php_ape.explorer.auth.noconf', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.noconf' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Grant read-permission to any user
    if( array_key_exists( 'php_ape.explorer.auth.read.any', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.read.any' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Grant read-permission to user/group matching directory name
    if( array_key_exists( 'php_ape.explorer.auth.read.dirmatch', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.read.dirmatch' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Read-authorized users
    if( array_key_exists( 'php_ape.explorer.auth.read.users', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.read.users' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Read-authorized groups
    if( array_key_exists( 'php_ape.explorer.auth.read.groups', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.read.groups' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Grant insert-permission to any user
    if( array_key_exists( 'php_ape.explorer.auth.insert.any', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.insert.any' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Grant insert-permission to user/group matching directory name
    if( array_key_exists( 'php_ape.explorer.auth.insert.dirmatch', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.insert.dirmatch' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Insert-authorized users
    if( array_key_exists( 'php_ape.explorer.auth.insert.users', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.insert.users' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Insert-authorized groups
    if( array_key_exists( 'php_ape.explorer.auth.insert.groups', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.insert.groups' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Grant update-permission to any user
    if( array_key_exists( 'php_ape.explorer.auth.update.any', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.update.any' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Grant update-permission to user/group matching directory name
    if( array_key_exists( 'php_ape.explorer.auth.update.dirmatch', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.update.dirmatch' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Update-authorized users
    if( array_key_exists( 'php_ape.explorer.auth.update.users', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.update.users' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Update-authorized groups
    if( array_key_exists( 'php_ape.explorer.auth.update.groups', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.update.groups' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Grant delete-permission to any user
    if( array_key_exists( 'php_ape.explorer.auth.delete.any', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.delete.any' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Grant delete-permission to user/group matching directory name
    if( array_key_exists( 'php_ape.explorer.auth.delete.dirmatch', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.delete.dirmatch' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Delete-authorized users
    if( array_key_exists( 'php_ape.explorer.auth.delete.users', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.delete.users' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Delete-authorized groups
    if( array_key_exists( 'php_ape.explorer.auth.delete.groups', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.delete.groups' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Grant image-download-permission to any user
    if( array_key_exists( 'php_ape.explorer.auth.image.download.any', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.image.download.any' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Grant image-download-permission to user/group matching directory name
    if( array_key_exists( 'php_ape.explorer.auth.image.download.dirmatch', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.image.download.dirmatch' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Image-download-authorized users
    if( array_key_exists( 'php_ape.explorer.auth.image.download.users', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.image.download.users' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // Image-download-authorized groups
    if( array_key_exists( 'php_ape.explorer.auth.image.download.groups', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.auth.image.download.groups' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( !empty( $rValue ) )
      {
        foreach( $rValue as &$rItem )
          $rItem = PHP_APE_Type_String::parseValue( $rItem );
        $rValue = array_unique( $rValue );
      }
    }

    // File's data page controller
    if( array_key_exists( 'php_ape.explorer.file.controller', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.file.controller' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'PHP_APE_Explorer_File_Controller';
    }

    // Image's data page controller
    if( array_key_exists( 'php_ape.explorer.image.controller', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.image.controller' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'PHP_APE_Explorer_Image_Controller';
    }

    // Image (maximum) size in list view
    if( array_key_exists( 'php_ape.explorer.image.size.list', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.image.size.list' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( $rValue < 50 )
        $rValue = 50;
    }

    // Choosable image (maximum) size(s) in detail view
    if( array_key_exists( 'php_ape.explorer.image.size.detail.choices', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.image.size.detail.choices' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = array( 400 );
      else
      {
        foreach( $rValue as &$rItem )
          if( $rItem < 100 ) $rItem = 100;
        $rValue = array_unique( $rValue );
        sort( $rValue );
      }
    }

    // Image (maximum) size in detail view
    if( array_key_exists( 'php_ape.explorer.image.size.detail', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.image.size.detail' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( is_null( $rValue ) or $rValue < 0 )
        $rValue = 400;
    }

    // Image (maximum) size in thumbnail
    if( array_key_exists( 'php_ape.explorer.image.size.thumbnail', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.image.size.thumbnail' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( $rValue < 100 )
        $rValue = 100;
    }

    // Use thumbnail in list view
    if( array_key_exists( 'php_ape.explorer.image.thumbnail.list.use', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.image.thumbnail.list.use' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Use thumbnail in detail view
    if( array_key_exists( 'php_ape.explorer.image.thumbnail.detail.use', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.image.thumbnail.detail.use' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Frameset top-bar's dimension (height) [pixels]
    if( array_key_exists( 'php_ape.explorer.frameset.topbar.height', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.frameset.topbar.height' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( is_null( $rValue ) or $rValue < 0 )
        $rValue = 90;
    }

    // Frameset left-bar's usage
    if( array_key_exists( 'php_ape.explorer.frameset.leftbar.use', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.frameset.leftbar.use' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = true;
    }

    // Frameset left-bar's dimension (width) [pixels]
    if( array_key_exists( 'php_ape.explorer.frameset.leftbar.width', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.frameset.leftbar.width' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( is_null( $rValue ) or $rValue < 0 )
        $rValue = 180;
    }

    // Left-bar's directory browser usage
    if( array_key_exists( 'php_ape.explorer.directory.browser.use', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.explorer.directory.browser.use' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = true;
    }

  }

  /** Verify and sanitize the supplied parameters
   *
   * @param array|string $rasParameters Input/output parameters (<B>as reference</B>)
   */
  public function verifyParameters( &$rasParameters )
  {
    $this->_verifyParameters( $rasParameters );
  }


  /*
   * METHODS: static parameters - OVERRIDE
   ********************************************************************************/

  protected function _mandatoryStaticParameters()
  {
    return array_merge( parent::_mandatoryStaticParameters(),
                        array(
                              'php_ape.explorer.htdocs.url' => null, 'php_ape.explorer.file.htdocs.url' => null, 'php_ape.explorer.image.htdocs.url' => null,
                              'php_ape.explorer.data.path' => null, 'php_ape.explorer.data.filter' => null,
                              'php_ape.explorer.auth.noconf' => null,
                              'php_ape.explorer.auth.read.any' => null, 'php_ape.explorer.auth.read.dirmatch' => null, 'php_ape.explorer.auth.read.users' => null, 'php_ape.explorer.auth.read.groups' => null,
                              'php_ape.explorer.auth.insert.any' => null, 'php_ape.explorer.auth.insert.dirmatch' => null, 'php_ape.explorer.auth.insert.users' => null, 'php_ape.explorer.auth.insert.groups' => null,
                              'php_ape.explorer.auth.update.any' => null, 'php_ape.explorer.auth.update.dirmatch' => null, 'php_ape.explorer.auth.update.users' => null, 'php_ape.explorer.auth.update.groups' => null,
                              'php_ape.explorer.auth.delete.any' => null, 'php_ape.explorer.auth.delete.dirmatch' => null, 'php_ape.explorer.auth.delete.users' => null, 'php_ape.explorer.auth.delete.groups' => null,
                              'php_ape.explorer.auth.image.download.any' => null, 'php_ape.explorer.auth.image.download.dirmatch' => null, 'php_ape.explorer.auth.image.download.users' => null, 'php_ape.explorer.auth.image.download.groups' => null,
                              'php_ape.explorer.file.controller' => null, 'php_ape.explorer.image.controller' => null,
                              'php_ape.explorer.image.size.list' => null, 'php_ape.explorer.image.size.detail.choices' => null, 'php_ape.explorer.image.size.detail' => null, 'php_ape.explorer.image.size.thumbnail' => null,
                              'php_ape.explorer.image.thumbnail.list.use' => null, 'php_ape.explorer.image.thumbnail.detail.use' => null,
                              'php_ape.explorer.frameset.topbar.height' => null,
                              'php_ape.explorer.frameset.leftbar.use' => null, 'php_ape.explorer.frameset.leftbar.width' => null,
                              'php_ape.explorer.directory.browser.use' => null
                              )
                        );
  }

}

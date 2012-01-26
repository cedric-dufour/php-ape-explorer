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
 * @subpackage Views
 */

/** File (generic) view
 *
 * @package PHP_APE_Explorer
 * @subpackage Views
 */
abstract class PHP_APE_Explorer_File_view
extends PHP_APE_Util_File_ResultSet
implements PHP_APE_Data_hasAuthorization, PHP_APE_HTML_hasOutputHandler, PHP_APE_Data_isInsertAble, PHP_APE_Data_isUpdateAble, PHP_APE_Data_isDeleteAble
{
  
  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID, $sName = null, $sDescription = null )
  {
    // Controller
    $roController =& PHP_APE_Explorer_WorkSpace::useFileController();

    // Check authorization
    if( !$roController->isReadAuthorized() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Access denied' );

    // Get explorer's path and filter
    $sDirectoryPath = $roController->getFullPath();
    $sBasenameRegEx = $roController->getDirectoryParameter( 'php_ape.explorer.data.filter' );

    // Parent constructor
    parent::__construct( $mID, $sDirectoryPath, $sBasenameRegEx, $sName, $sDescription );
  }


  /*
   * METHODS: PHP_APE_Data_hasAuthorization - IMPLEMENT
   ********************************************************************************/

  public function hasAuthorization()
  {
    return PHP_APE_Explorer_WorkSpace::useFileController()->isReadAuthorized();
  }


  /*
   * METHODS: PHP_APE_HTML_hasOutputHandler - IMPLEMENT
   ********************************************************************************/

  public function getHTMLOutput( $mKey = null )
  {
    // Controller
    $roController =& PHP_APE_Explorer_WorkSpace::useFileController();

    // Output override
    if( $mKey == 'name' )
    {
      // ... file
      $sFileName = $this->getBasename();
      $sFilePath = PHP_APE_Util_File_Any::encodePath( $this->getDirectoryPath().'/'.$sFileName );
      if( $sFileName == PHP_APE_EXPLORER_CONF ) return null;

      // ... hyperlink
      if( !is_dir( $sFilePath ) )
        $sURL = $roController->makeDownloadURL( $sFileName );
      else
      {
        $sExplorerPath = $roController->getExplorerPath().'/'.$sFileName;
        if( !PHP_APE_Explorer_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.explorer.auth.noconf' ) and
            !$roController->hasDirectoryParameters( $sExplorerPath ) )
          return null;
        $sURL = $roController->makeRequestURL( 'index.php', $sExplorerPath, 'list' );
      }
      return PHP_APE_HTML_Tags::htmlAnchor( $sURL, $this->useElement( 'name' )->useContent()->getValue() );
    }
    elseif( $mKey == 'size' )
    {
      $iSize = $this->useElement( 'size' )->useContent()->getValue();
      if( $iSize > 1073741824 ) $sSize = round( (float)$iSize / 1073741824, 1 ).'GB';
      elseif( $iSize > 1048576 ) $sSize = round( (float)$iSize / 1048576, 1 ).'MB';
      elseif( $iSize > 1024 ) $sSize = round( (float)$iSize / 1024, 1 ).'kB';
      else $sSize = $iSize.'B';
      return $sSize;
    }

    // Default
    return null;
  }


  /*
   * METHODS: PHP_APE_Data_isInsertAble - IMPLEMENT
   ********************************************************************************/

  public function getInsertFunction()
  {
    return new PHP_APE_Explorer_File_insert();
  }

  public function isInsertAuthorized()
  {
    return PHP_APE_Explorer_WorkSpace::useFileController()->isInsertAuthorized() && $this->useElement( 'name' )->useContent()->isNull();
  }


  /*
   * METHODS: PHP_APE_Data_isUpdateAble - IMPLEMENT
   ********************************************************************************/

  public function getUpdateFunction()
  {
    return new PHP_APE_Explorer_File_update();
  }

  public function isUpdateAuthorized()
  {
    return PHP_APE_Explorer_WorkSpace::useFileController()->isUpdateAuthorized() && $this->getBasename() != PHP_APE_EXPLORER_CONF && is_writable( PHP_APE_Util_File_Any::encodePath( $this->getDirectoryPath().'/'.$this->getBasename() ) );
  }


  /*
   * METHODS: PHP_APE_Data_isDeleteAble - IMPLEMENT
   ********************************************************************************/

  public function getDeleteFunction()
  {
    return new PHP_APE_Explorer_File_delete();
  }

  public function isDeleteAuthorized()
  {
    return PHP_APE_Explorer_WorkSpace::useFileController()->isDeleteAuthorized() && $this->getBasename() != PHP_APE_EXPLORER_CONF && is_writable( PHP_APE_Util_File_Any::encodePath( $this->getDirectoryPath().'/'.$this->getBasename() ) );
  }

}

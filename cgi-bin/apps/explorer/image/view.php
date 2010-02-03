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

/** Image (generic) view
 *
 * @package PHP_APE_Explorer
 * @subpackage Views
 */
abstract class PHP_APE_Explorer_Image_view
extends PHP_APE_Util_Image_ResultSet
implements PHP_APE_Data_hasAuthorization, PHP_APE_HTML_hasOutputHandler, PHP_APE_Data_isInsertAble, PHP_APE_Data_isUpdateAble, PHP_APE_Data_isDeleteAble
{
  
  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID, $sName = null, $sDescription = null )
  {
    // Controller
    $roController =& PHP_APE_Explorer_WorkSpace::useImageController();

    // Check authorization
    if( !$roController->isReadAuthorized() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Access denied' );

    // Get explorer's path and filter
    $sDirectoryPath = $roController->getFullPath();
    $sBasenameRegEx = $roController->getDirectoryParameter( 'php_ape.explorer.data.filter' );

    // Parent constructor
    parent::__construct( $mID, $sDirectoryPath, $sBasenameRegEx, true, $sName, $sDescription );
  }


  /*
   * METHODS: initialization
   ********************************************************************************/

  /** Retrieves the fields (template) objects
   *
   * @param string $sKeyPrefix Fields keys prefix
   * @return array|PHP_APE_Database_Field
   */
  public static function getFieldsTemplates( $sKeyPrefix = null )
  {
    // Sanitize input
    $sKeyPrefix = PHP_APE_Type_Index::parseValue( $sKeyPrefix );

    // Resources
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Explorer_Image_Resources' );

    // Fields
    $aoFields =
      array(

            $sKeyPrefix.'image' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'image',
                                    new PHP_APE_Type_Boolean(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_ShowIfEmpty,
                                    $asResources['name.image'],
                                    $asResources['description.image']
                                    )

            );

    // End
    return array_merge( $aoFields, parent::getFieldsTemplates( $sKeyPrefix ) );
  }


  /*
   * METHODS: PHP_APE_Data_hasAuthorization - IMPLEMENT
   ********************************************************************************/

  public function hasAuthorization()
  {
    return PHP_APE_Explorer_WorkSpace::useImageController()->isReadAuthorized();
  }


  /*
   * METHODS: PHP_APE_HTML_hasOutputHandler - IMPLEMENT
   ********************************************************************************/

  public function getHTMLOutput( $mKey = null )
  {
    // Controller
    $roController =& PHP_APE_Explorer_WorkSpace::useImageController();

    // Output override
    if( $mKey == 'name' )
    {
      // ... check authorization
      if( !$roController->isImageDownloadAuthorized() )
        return null;

      // ... hyperlink
      $sFileBasename = $this->getBasename();
      $sFilePath = PHP_APE_Util_File_Any::encodePath( $this->getDirectoryPath().'/'.$sFileBasename );
      $sURL = $roController->makeDownloadURL( $sFileBasename );
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
    elseif( $mKey == 'width' )
    {
      return $this->useElement( 'width' )->useContent()->getValue().'px';
    }
    elseif( $mKey == 'height' )
    {
      return $this->useElement( 'height' )->useContent()->getValue().'px';
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
    return PHP_APE_Explorer_WorkSpace::useImageController()->isInsertAuthorized() && $this->useElement( 'name' )->useContent()->isNull();
  }


  /*
   * METHODS: PHP_APE_Data_isUpdateAble - IMPLEMENT
   ********************************************************************************/

  public function getUpdateFunction()
  {
    return new PHP_APE_Explorer_Image_update();
  }

  public function isUpdateAuthorized()
  {
    return PHP_APE_Explorer_WorkSpace::useImageController()->isUpdateAuthorized() && $this->getFormat() == 'jpeg' && is_writable( PHP_APE_Util_File_Any::encodePath( $this->getDirectoryPath().'/'.$this->getBasename() ) );
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
    return PHP_APE_Explorer_WorkSpace::useImageController()->isDeleteAuthorized() && is_writable( PHP_APE_Util_File_Any::encodePath( $this->getDirectoryPath().'/'.$this->getBasename() ) );
  }

}

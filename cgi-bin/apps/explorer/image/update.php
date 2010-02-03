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
 * @subpackage Functions
 */

/** Image update function
 *
 * @package PHP_APE_Explorer
 * @subpackage Functions
 */
class PHP_APE_Explorer_Image_update
extends PHP_APE_Explorer_Image_function
{
  
  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = 'ImageUpdate' )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_Image_Resources' );

    // Parent constructor
    parent::__construct( $mID, new PHP_APE_Type_String(), $asResources['name.function.update'], $asResources['description.function.update'] );

    // Set arguments
    $this->setArgumentSet( new PHP_APE_Data_ArgumentSet() );
    $this->_setArguments( array( 'name', 'iptc_name', 'iptc_headline', 'iptc_caption', 'iptc_author', 'iptc_copyright', 'iptc_category', 'iptc_subcategories', 'iptc_keywords' ) );
    // ... customize
    $roArgumentSet = $this->useArgumentSet();
    $roArgumentSet->useElementByID( 'name' )->addMeta( PHP_APE_Data_Argument::Value_Lock );
  }


  /*
   * METHODS: PHP_APE_Data_hasAuthorization - IMPLEMENT
   ********************************************************************************/

  public function hasAuthorization()
  {
    
    $roController =& PHP_APE_Explorer_WorkSpace::useImageController();
    $sFilePath = PHP_APE_Util_File_Any::encodePath( $roController->getFullPath().'/'.$this->useArgumentSet()->useElementByID( 'name' )->useContent()->getValue() );
    return $roController->isUpdateAuthorized() && is_writable( $sFilePath );
  }


  /*
   * METHODS: PHP_APE_Data_Function - IMPLEMENT
   ********************************************************************************/

  public function execute()
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $roController =& PHP_APE_Explorer_WorkSpace::useImageController();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_Image_Resources' );

    // Rename file
    try
    {
      // ... file name
      $roArgumentSet =& $this->useArgumentSet();
      $sFileName = basename( $roArgumentSet->useElementByID( 'name' )->useContent()->getValue() );
      $sFilePath = PHP_APE_Util_File_Any::encodePath( $roController->getFullPath().'/'.$sFileName );

      // ... embed IPTC data
      $roJPEGMetaData =& new PHP_APE_Util_Image_JPEG( $sFilePath );
      $roJPEGMetaData->setIPTCField( 'ObjectName', $roArgumentSet->useElementByID( 'iptc_name' )->useContent()->getValue() );
      $roJPEGMetaData->setIPTCField( 'Headline', $roArgumentSet->useElementByID( 'iptc_headline' )->useContent()->getValue() );
      $roJPEGMetaData->setIPTCField( 'Caption', $roArgumentSet->useElementByID( 'iptc_caption' )->useContent()->getValue() );
      $roJPEGMetaData->setIPTCField( 'Byline', $roArgumentSet->useElementByID( 'iptc_author' )->useContent()->getValue() );
      $roJPEGMetaData->setIPTCField( 'CopyrightNotice', $roArgumentSet->useElementByID( 'iptc_copyright' )->useContent()->getValue() );
      $roJPEGMetaData->setIPTCField( 'Category', $roArgumentSet->useElementByID( 'iptc_category' )->useContent()->getValue() );
      $roJPEGMetaData->setIPTCField( 'SuplementalCategories', $roArgumentSet->useElementByID( 'iptc_subcategories' )->useContent()->getValue() );
      $roJPEGMetaData->setIPTCField( 'Keywords', $roArgumentSet->useElementByID( 'iptc_keywords' )->useContent()->getValue() );
      if( !$roJPEGMetaData->save() )
        throw new PHP_APE_Explorer_Image_Exception( __METHOD__, $asResources['error.update'] );

      // ... sucess
      $this->useContent()->setValue( $sFileName );
    }
    catch( PHP_APE_Explorer_Image_Exception $e )
    {
      // ... error
      $this->asErrors['__GLOBAL'] = $e->getMessage();
      $this->useContent()->setValue( null );
    }

    // Output
    return $this->getContent();
  }

}

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

/** File delete function
 *
 * @package PHP_APE_Explorer
 * @subpackage Functions
 */
class PHP_APE_Explorer_File_delete
extends PHP_APE_Explorer_File_function
{
  
  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = 'FileDelete' )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_File_Resources' );

    // Parent constructor
    parent::__construct( $mID, new PHP_APE_Type_Boolean(), $asResources['name.function.delete'], $asResources['description.function.delete'] );

    // Set arguments
    $this->setArgumentSet( new PHP_APE_Data_ArgumentSet() );
    $this->_setArguments( array( 'name' ) );
  }


  /*
   * METHODS: PHP_APE_Data_hasAuthorization - IMPLEMENT
   ********************************************************************************/

  public function hasAuthorization()
  {
    $roController =& PHP_APE_Explorer_WorkSpace::useFileController();
    $sFilePath = PHP_APE_Util_File_Any::encodePath( $roController->getFullPath().'/'.$this->useArgumentSet()->useElementByID( 'name' )->useContent()->getValue() );
    return $roController->isDeleteAuthorized() && is_writable( $sFilePath );
  }


  /*
   * METHODS: PHP_APE_Data_Function - IMPLEMENT
   ********************************************************************************/

  public function execute()
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $roController =& PHP_APE_Explorer_WorkSpace::useFileController();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_File_Resources' );

    // Delete file
    try
    {
      // ... delete
      $roArgumentSet =& $this->useArgumentSet();
      $sFilePath = PHP_APE_Util_File_Any::encodePath( $roController->getFullPath().'/'.$roArgumentSet->useElementByID( 'name' )->useContent()->getValue() );
      if( !( is_dir( $sFilePath ) ? @rmdir( $sFilePath ) : unlink( $sFilePath ) ) )
        throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.delete'] );

      // ... sucess
      $this->useContent()->setValue( true );
    }
    catch( PHP_APE_Explorer_File_Exception $e )
    {
      // ... error
      $this->asErrors['__GLOBAL'] = $e->getMessage();
      $this->useContent()->setValue( false );
    }

    // Output
    return $this->getContent();
  }

}

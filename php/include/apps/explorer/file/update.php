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

/** File update function
 *
 * @package PHP_APE_Explorer
 * @subpackage Functions
 */
class PHP_APE_Explorer_File_update
extends PHP_APE_Explorer_File_function
{
  
  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = 'FileUpdate' )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_File_Resources' );

    // Parent constructor
    parent::__construct( $mID, new PHP_APE_Type_String(), $asResources['name.function.update'], $asResources['description.function.update'] );

    // Set arguments
    $this->setArgumentSet( new PHP_APE_Data_ArgumentSet() );
    $this->_setArguments( array( 'name', 'rename' ) );
    // ... customize
    $roArgumentSet = $this->useArgumentSet();
    $roArgumentSet->useElementByID( 'name' )->addMeta( PHP_APE_Data_Argument::Value_Lock );
  }


  /*
   * METHODS: PHP_APE_Data_hasAuthorization - IMPLEMENT
   ********************************************************************************/

  public function hasAuthorization()
  {
    
    $roController =& PHP_APE_Explorer_WorkSpace::useFileController();
    $sFileName = $this->useArgumentSet()->useElementByID( 'name' )->useContent()->getValue();
    if( $sFileName == PHP_APE_EXPLORER_CONF ) return false;
    $sFilePath = PHP_APE_Util_File_Any::encodePath( $roController->getFullPath().'/'.$sFileName );
    return $roController->isUpdateAuthorized() && is_writable( $sFilePath );
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

    // Rename file
    try
    {
      // ... check file name(s)
      $roArgumentSet =& $this->useArgumentSet();
      $sFileName_old = basename( $roArgumentSet->useElementByID( 'name' )->useContent()->getValue() );
      $sFileName_new = basename( $roArgumentSet->useElementByID( 'rename' )->useContent()->getValue() );
      if( !empty( $sFileName_new ) )
      {
        // ... check extension
        $asFileInfo = pathinfo( $sFileName_old );
        $sFileExtension_old = array_key_exists( 'extension', $asFileInfo ) ? $asFileInfo['extension'] : null;
        $asFileInfo = pathinfo( $sFileName_new );
        $sFileExtension_new = array_key_exists( 'extension', $asFileInfo ) ? $asFileInfo['extension'] : null;
        if( !is_null( $sFileExtension_new ) ) // strip extension from new name
          $sFileName_new = basename( $sFileName_new, '.'.$sFileExtension_new );
        if( !is_null( $sFileExtension_old ) ) // add extension of old name
          $sFileName_new = $sFileName_new.'.'.$sFileExtension_old;

        // ... check file name
        if( $sFileName_old == PHP_APE_EXPLORER_CONF or $sFileName_new == PHP_APE_EXPLORER_CONF )
          throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.update'] );

        // ... check existency
        $sFilePath_new = PHP_APE_Util_File_Any::encodePath( $roController->getFullPath().'/'.$sFileName_new );
        if( file_exists( $sFilePath_new ) )
        {
          if( !$roArgumentSet->useElementByID( 'rename' )->useContent()->isEmpty() )
            $this->asErrors['rename'] = $asResources['error.update.overwrite'];
          throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.update.overwrite'] );
        }

        // ... rename
        $sFilePath_old = PHP_APE_Util_File_Any::encodePath( $roController->getFullPath().'/'.$sFileName_old );
        if( !rename( $sFilePath_old, $sFilePath_new ) )
          throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.update'] );
      }

      // ... sucess
      $this->useContent()->setValue( $sFileName_new );
    }
    catch( PHP_APE_Explorer_File_Exception $e )
    {
      // ... error
      $this->asErrors['__GLOBAL'] = $e->getMessage();
      $this->useContent()->setValue( null );
    }

    // Output
    return $this->getContent();
  }

}

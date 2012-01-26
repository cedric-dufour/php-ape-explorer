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

/** File insert function
 *
 * @package PHP_APE_Explorer
 * @subpackage Functions
 */
class PHP_APE_Explorer_File_insert
extends PHP_APE_Explorer_File_function
{
  
  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = 'FileInsert' )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_File_Resources' );

    // Parent constructor
    parent::__construct( $mID, new PHP_APE_Type_String(), $asResources['name.function.insert'], $asResources['description.function.insert'] );

    // Set arguments
    $this->setArgumentSet( new PHP_APE_Data_ArgumentSet() );
    $this->_setArguments( array( 'upload', 'rename', 'overwrite' ) );
    // ... customize
    $roArgumentSet = $this->useArgumentSet();
    $roArgumentSet->useElementByID( 'upload' )->addMeta( PHP_APE_Data_Argument::Feature_RequireInForm );
  }


  /*
   * METHODS: PHP_APE_Data_hasAuthorization - IMPLEMENT
   ********************************************************************************/

  public function hasAuthorization()
  {
    return PHP_APE_Explorer_WorkSpace::useFileController()->isInsertAuthorized();
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

    // Create file
    try
    {
      // ... check upload
      try { $roUploadedFile =& PHP_APE_Type_FileFromUpload::useUploadedFile( 'upload' ); }
      catch( PHP_APE_Type_Exception $e )
      {
        $this->asErrors['upload'] = $e->getMessage();
        throw new PHP_APE_Explorer_File_Exception( __METHOD__, $this->asErrors['upload'] );
      }

      // ... check file name(s)
      $roArgumentSet =& $this->useArgumentSet();
      $sFileName_old = basename( $roUploadedFile['name'] );
      $sFileName_new = basename( $roArgumentSet->useElementByID( 'rename' )->useContent()->getValue() );
      $bOverwrite = $roArgumentSet->useElementByID( 'overwrite' )->useContent()->getValue();
      if( !empty( $sFileName_new ) )
      {
        $asFileInfo = pathinfo( $sFileName_old );
        $sFileExtension_old = array_key_exists( 'extension', $asFileInfo ) ? $asFileInfo['extension'] : null;
        $asFileInfo = pathinfo( $sFileName_new );
        $sFileExtension_new = array_key_exists( 'extension', $asFileInfo ) ? $asFileInfo['extension'] : null;
        if( !is_null( $sFileExtension_new ) ) // strip extension from new name
          $sFileName_new = basename( $sFileName_new, '.'.$sFileExtension_new );
        if( !is_null( $sFileExtension_old ) ) // add extension of old name
          $sFileName_new = $sFileName_new.'.'.$sFileExtension_old;
      }
      else
        $sFileName_new = $sFileName_old;

      // ... check file name
      if( $sFileName_new == PHP_APE_EXPLORER_CONF )
        throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.insert'] );

      // ... check existency
      $sFilePath_new = PHP_APE_Util_File_Any::encodePath( $roController->getFullPath().'/'.$sFileName_new );
      if( file_exists( $sFilePath_new ) )
      {
        // ... check overwrite
        if( !$bOverwrite )
        {
          $this->asErrors['overwrite'] = $asResources['error.insert.overwrite.denied'];
          throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.insert.overwrite.denied'] );
        }
        if( !is_writable( $sFilePath_new ) )
        {
          if( !$roArgumentSet->useElementByID( 'rename' )->useContent()->isEmpty() )
            $this->asErrors['rename'] = $asResources['error.insert.overwrite.denied'];
          throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.insert.overwrite.denied'] );
        }
        if( is_dir( $sFilePath_new ) )
        {
          if( !$roArgumentSet->useElementByID( 'rename' )->useContent()->isEmpty() )
            $this->asErrors['rename'] = $asResources['error.insert.overwrite.directory'];
          throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.insert.overwrite.directory'] );
        }

        // ... delete first
        if( !unlink( $sFilePath_new ) )
          throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.insert.overwrite.delete'] );
      }

      // ... rename
      if( !rename( $roUploadedFile['tmp_name'], $sFilePath_new ) )
        throw new PHP_APE_Explorer_File_Exception( __METHOD__, $asResources['error.insert'] );

      // ... success
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

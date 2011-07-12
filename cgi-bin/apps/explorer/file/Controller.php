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
 * @subpackage Control
 */

/** File controller
 *
 * @package PHP_APE_Explorer
 * @subpackage Control
 */
class PHP_APE_Explorer_File_Controller
extends PHP_APE_Explorer_Controller
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Returns a new instance of this controller for the given path
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Explorer_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param string $sPath Explorer's (relative) path (default to the REQUEST path if <SAMP>null</SAMP>)
   */
  public function __construct( $sPath = null )
  {
    // Parent constructor
    parent::__construct( self::$roEnvironment->getStaticParameter( 'php_ape.explorer.file.htdocs.url' ), $sPath );
  }


  /*
   * METHODS: actions/view - OVERRIDE
   ********************************************************************************/

  public function htmlViewOrAction()
  {
    // Output
    $sOutput = null;

    // Controller
    global $oController;
    $oController = $this;
    $rasRequestData =& $this->useRequestData();
    $amPassthruVariables = $this->getPassthruVariables();
    $sSource = $this->getSource();
    $sDestination = $this->getDestination();
    $mPrimaryKey = $this->getPrimaryKey();
    $bIsPopup = $this->isPopup();

    // Actions / Views
    switch( $sDestination )
    {


      // DELETE

    case 'delete':
      // Database object
      $oFunction = new PHP_APE_Explorer_File_delete();

      // Try
      $rasErrors = null;
      try
      {
        // Execute
        $this->executeDeleteFunction( $oFunction, $rasErrors );

        // Redirect
        PHP_APE_Util_BrowserControl::redirect( $this->makeRequestURL( $this->getRequestURL(), null, 'list' ), null, true );
      }
      catch( PHP_APE_HTML_Data_Exception $e )
      {
        // Redirect
        PHP_APE_Util_BrowserControl::redirect( $this->makeRequestURL( $this->getRequestURL(), null,'list', null, $rasRequestData, $rasErrors ), null, true );
      }
      break;


      // INSERT

    case 'new':
      // Database object
      $oFunction = new PHP_APE_Explorer_File_insert();

      // Prepare
      $this->prepareInsertFunction( $oFunction );

      // Output
      $oHTML = $this->getFormView( $oFunction, null, null, $amPassthruVariables );
      // ... sub-title
      $sOutput .= $this->htmlSubTitle( $oFunction, 'S-new' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      // ... errors
      $asErrors = $oHTML->getErrors();
      if( count( $asErrors ) )
      {
        $e = null;
        if( array_key_exists( '__GLOBAL', $asErrors ) ) $e = new PHP_APE_HTML_Data_Exception( null, $asErrors['__GLOBAL'] );
        $sOutput .= PHP_APE_HTML_Components::htmlDataException( $e, false, true );
      }
      // ... data
      $sOutput .= $oHTML->html();
      break;

    case 'insert':
      // Database object
      $oFunction = new PHP_APE_Explorer_File_insert();

      // Try
      $rasErrors = null;
      try
      {
        // Execute
        $mPrimaryKey = $this->executeInsertFunction( $oFunction, $rasErrors );

        // Redirect
        if( $bIsPopup ) 
        {
          PHP_APE_Util_BrowserControl::refresh( 'opener' );
          PHP_APE_Util_BrowserControl::close();
        }
        else
          PHP_APE_Util_BrowserControl::redirect( $this->makeRequestURL( $this->getRequestURL(), null, 'list' ), null, true );
      }
      catch( PHP_APE_HTML_Data_Exception $e )
      {
        // Redirect
        PHP_APE_Util_BrowserControl::redirect( $this->makeRequestURL( $this->getRequestURL(), null, $sSource, $mPrimaryKey, $rasRequestData, $rasErrors, $bIsPopup ), null, true );
      }
      break;


      // UPDATE

    case 'edit':
      // Database object
      $oFunction = new PHP_APE_Explorer_File_update();
      $oView = new PHP_APE_Explorer_File_detail();

      // Prepare
      $this->prepareInsertFunction( $oFunction );
      $this->prepareDetailView( $oView );

      // Output
      $oHTML = $this->getFormView( $oFunction, $oView, PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables );
      // ... sub-title
      $sOutput .= $this->htmlSubTitle( $oFunction, 'S-edit' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      // ... errors
      $asErrors = $oHTML->getErrors();
      if( count( $asErrors ) )
      {
        $e = null;
        if( array_key_exists( '__GLOBAL', $asErrors ) ) $e = new PHP_APE_HTML_Data_Exception( null, $asErrors['__GLOBAL'] );
        $sOutput .= PHP_APE_HTML_Components::htmlDataException( $e, false, true );
      }
      // ... data
      $sOutput .= $oHTML->html();
      break;

    case 'update':
      // Database object
      $oFunction = new PHP_APE_Explorer_File_update();

      // Try
      $rasErrors = null;
      try
      {
        // Execute
        $mPrimaryKey = $this->executeUpdateFunction( $oFunction, $rasErrors );

        // Redirect
        if( $bIsPopup ) 
        {
          PHP_APE_Util_BrowserControl::refresh( 'opener' );
          PHP_APE_Util_BrowserControl::close();
        }
        else
          PHP_APE_Util_BrowserControl::redirect( $this->makeRequestURL( $this->getRequestURL(), null, 'list' ), null, true );
      }
      catch( PHP_APE_HTML_Data_Exception $e )
      {
        // Redirect
        PHP_APE_Util_BrowserControl::redirect( $this->makeRequestURL( $this->getRequestURL(), null, $sSource, $mPrimaryKey, $rasRequestData, $rasErrors, $bIsPopup ), null, true );
      }
      break;


      // SELECT

    case 'detail':
      // Database object
      $oView = new PHP_APE_Explorer_File_detail();

      // Prepare
      $this->prepareDetailView( $oView );

      // Output
      $oHTML = $this->getDetailView( $oView, PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables );
//       // ... sub-title
//       $sOutput .= $this->htmlSubTitle( $oView->getBasename(), $oView->getDescription(), 'S-detail' );
//       $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      // ... errors
      $asErrors = $oHTML->getErrors();
      if( count( $asErrors ) )
      {
        $e = null;
        if( array_key_exists( '__GLOBAL', $asErrors ) ) $e = new PHP_APE_HTML_Data_Exception( null, $asErrors['__GLOBAL'] );
        $sOutput .= PHP_APE_HTML_Components::htmlDataException( $e, false, true );
      }
      // ... data
      $sOutput .= $oHTML->html();
    break;

    default:
    case 'list':
      // Database object
      $oView = new PHP_APE_Explorer_File_list();
      $oFilter = new PHP_APE_Data_Filter( 'FileFilter', new PHP_APE_Data_FilterCriteria( 'mode', 'd', PHP_APE_Data_LogicalOperator::NNot | PHP_APE_Data_ComparisonOperator::Proportional ) );
      $oView->setSubsetFilter( $oFilter );

      // Prepare
      $this->prepareListView( $oView );

      // Output
      $oHTML = $this->getListView( $oView, PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables );
//       // ... sub-title
//       $sOutput .= $this->htmlSubTitle( $oView, 'S-list' );
//       $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      // ... errors
      $asErrors = $oHTML->getErrors();
      if( count( $asErrors ) )
      {
        $e = null;
        if( array_key_exists( '__GLOBAL', $asErrors ) ) $e = new PHP_APE_HTML_Data_Exception( null, $asErrors['__GLOBAL'] );
        $sOutput .= PHP_APE_HTML_Components::htmlDataException( $e, false, true );
      }
      // ... data
      $sOutput .= $oHTML->html();
      break;

    }

    // End
    return $sOutput;
  }

}

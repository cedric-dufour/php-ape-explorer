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

/** Image controller
 *
 * @package PHP_APE_Explorer
 * @subpackage Control
 */
class PHP_APE_Explorer_Image_Controller
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
    parent::__construct( self::$roEnvironment->getStaticParameter( 'php_ape.explorer.image.htdocs.url' ), $sPath );
  }


  /*
   * METHODS: HTML components
   ********************************************************************************/

  /** Returns this controller's preferences-setting bar
   *
   * @param string $sStyle Associated (cell) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sTableStyle Associated (table) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @return string
   */
  public static function htmlPreferencesControllerBar( $sStyle = null, $sTableStyle = null )
  {
    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( $sStyle, $sTableStyle );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'S-control', null, null, null, true );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( self::$asResources['label.preferences.image.thumbnails'].':', null, null, self::$asResources['tooltip.preferences.image.thumbnails'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $bUseThumbnails_inList = self::$roEnvironment->getUserParameter( 'php_ape.explorer.image.thumbnail.list.use' );
    $sOutput .= '<INPUT TYPE="checkbox" CLASS="checkbox" ONCLICK="javascript:self.location.replace(PHP_APE_URL_addQuery(\''.self::$oDataSpace_JavaScript->encodeData( ltrim( self::$roEnvironment->makePreferencesURL( array( 'php_ape.explorer.image.thumbnail.list.use' => $bUseThumbnails_inList ? 0 : 1 ), null ), '?' ) ).'\',self.location.href.toString()));"'.( $bUseThumbnails_inList ? ' CHECKED': null ).'/>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= '<P>'.self::$oDataSpace_HTML->encodeData(self::$asResources['text.preferences.image.thumbnails.inlist'],false,true).'</P>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $bUseThumbnails_inDetail = self::$roEnvironment->getUserParameter( 'php_ape.explorer.image.thumbnail.detail.use' );
    $sOutput .= '<INPUT TYPE="checkbox" CLASS="checkbox" ONCLICK="javascript:self.location.replace(PHP_APE_URL_addQuery(\''.self::$oDataSpace_JavaScript->encodeData( ltrim( self::$roEnvironment->makePreferencesURL( array( 'php_ape.explorer.image.thumbnail.detail.use' => $bUseThumbnails_inDetail ? 0 : 1 ), null ), '?' ) ).'\',self.location.href.toString()));"'.( $bUseThumbnails_inDetail ? ' CHECKED': null ).'/>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= '<P>'.self::$oDataSpace_HTML->encodeData(self::$asResources['text.preferences.image.thumbnails.indetail'],false,true).'</P>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( self::$asResources['label.preferences.image.detail.size'].':', null, null, self::$asResources['tooltip.preferences.image.detail.size'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $iDetailSize = self::$roEnvironment->getUserParameter( 'php_ape.explorer.image.size.detail' );
    $aiDetailSize_Choices = self::$roEnvironment->getStaticParameter( 'php_ape.explorer.image.size.detail.choices' );
    if( !in_array( $iDetailSize, $aiDetailSize_Choices ) ) $iDetailSize = $aiDetailSize_Choices[0];
    $sOutput .= '<SELECT ONCHANGE="javascript:self.location.replace(PHP_APE_URL_addQuery(this.value,self.location.href.toString()));">';
    foreach( $aiDetailSize_Choices as $iChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( self::$roEnvironment->makePreferencesURL( array( 'php_ape.explorer.image.size.detail' => $iChoice ), null ), '?' ).'"'.( $iChoice == $iDetailSize ? ' SELECTED' : null).'>'.$iChoice.'</OPTION>';
    $sOutput .= '</SELECT>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
    $sOutput .= '<P>px</P>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    return $sOutput;
  }


  /*
   * METHODS: HTML components - OVERRIDE
   ********************************************************************************/

  /** Returns the HTML page's (top) title
   *
   * @return string
   */
  public function htmlTitle()
  {
    return PHP_APE_HTML_SmartTags::htmlLabel( $this->getTitle(), 'M-image', null, null, null, true, false, 'H1' );
  }

  /** Returns the HTML page's footer
   *
   * @return string
   */
  public function htmlFooter()
  {
    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlSeparator();
    $sOutput .= '<DIV STYLE="FLOAT:left;PADDING:2px;">'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.display'].':', 'S-display', null, $asResources['tooltip.preferences.display'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING:2px !important;', false );
    $sOutput .= PHP_APE_HTML_Components::htmlPreferenceCSS();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '<DIV CLASS="do-not-print" STYLE="FLOAT:left;PADDING:2px;">'."\r\n";
    $sOutput .= self::htmlPreferencesControllerBar();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '<DIV CLASS="do-not-print" STYLE="FLOAT:right;PADDING:2px;">'."\r\n";
    $sOutput .= PHP_APE_HTML_Components::htmlPreferences();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '<DIV STYLE="CLEAR:both;"></DIV>'."\r\n";
    return $sOutput;
  }


  /*
   * METHODS: image
   ********************************************************************************/

  /** Returns the explorer's image URL for the (relative) file path and resizing gauge
   *
   * <P><B>NOTE:</B> The image parameters are encrypted, in order to avoid having them supplied from the client
   * without prior server proposal.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sFileBasename Explorer's file basename (without any path)
   * @param array|integer $aiGauge Image's resizing gauge
   * @return string
   */
  final public function makeImageURL( $sFileBasename, $aiGauge = null )
  {
    // Sanitize input
    $sFileBasename = trim( basename( PHP_APE_Type_Path::parseValue( $sFileBasename ) ) );
    if( !is_null( $aiGauge ) )
    {
      $aiGauge = PHP_APE_Type_Array::parseValue( $aiGauge );
      if( count( $aiGauge ) < 2 )
        throw new PHP_APE_Explorer_Image_Exception( __METHOD__, 'Invalid gauge' );
    }

    // Parameters
    $amParameters = array();
    $amParameters['salt'] = sha1( rand() ).md5( rand() ); // let's add some random (cryptographic) salt to the parameters
    $amParameters['path'] = $this->getFullPath().'/'.$sFileBasename;
    if( !is_null( $aiGauge ) ) $amParameters['gauge'] = $aiGauge;

    // URL
    $sURL = $this->getURL().'/image.php';
    $sURL = PHP_APE_Util_URL::addVariable( $sURL, array( 'PHP_APE_Explorer_Image' => self::$roEnvironment->encryptData( serialize( $amParameters ) ) ) );
    return $sURL;
  }

  /** Returns the (decrypted) image's parameters from the (GET only) request
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Explorer_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|mixed
   */
  final static public function getImageParameters()
  {
    // Check request
    if( !array_key_exists( 'PHP_APE_Explorer_Image', $_GET ) )
      throw new PHP_APE_Explorer_Exception( __METHOD__, 'Missing parameters' );
      
    // File key
    return unserialize( self::$roEnvironment->decryptData( $_GET['PHP_APE_Explorer_Image'] ) );
  }


  /*
   * METHODS: actions/view - OVERRIDE
   ********************************************************************************/

  public function htmlSideBar_Add()
  {
    // Output
    $sOutput = null;

    // ... Thumbnails
    if( $this->isReadAuthorized() )
    {
      $bThumbnails_Use = self::$roEnvironment->getUserParameter( 'php_ape.explorer.sidebar.image.thumbnail.use' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( 'WIDTH:100%;', 'WIDTH:100%;' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( self::$asResources['name.sidebar.image.thumbnails'], 'M-image', null, self::$asResources['description.sidebar.image.thumbnails'], null, true, false, 'H1' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
      $sOutput .= PHP_APE_HTML_Components::htmlControlFrameset( array( 'php_ape.explorer.sidebar.image.thumbnail.use' ),
                                                                array( self::$asResources['description.sidebar.image.thumbnails.use'] ),
                                                                array( $bThumbnails_Use ? 'S-minimize' : 'S-maximize' ),
                                                                'self' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();

      // ... content
      if( $bThumbnails_Use )
      {

        // Database object
        $oView = new PHP_APE_Explorer_Image_thumbnails();

        // Prepare  
        $this->prepareListView( $oView );

        // Thumbnails
        $oHTML = $this->getListView( $oView, PHP_APE_Data_isQueryAbleResultSet::Query_All );
        $oHTML->prefUseControls( false );
        $oHTML->prefUseHeader( false );
        $oHTML->prefUseFooter( false );
        $oHTML->prefUseDisplayPreferences( false );
        $oHTML->prefUseOrderPreferences( false );
        $sOutput .= $oHTML->html();

      }
    }

    // End
    return $sOutput;
  }

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
      $oFunction = new PHP_APE_Explorer_Image_update();
      $oView = new PHP_APE_Explorer_Image_detail();

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
      $oFunction = new PHP_APE_Explorer_Image_update();

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
          PHP_APE_Util_BrowserControl::redirect( $this->makeRequestURL( $this->getRequestURL(), null, 'detail', $mPrimaryKey ), null, true );
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
      $oView = new PHP_APE_Explorer_Image_detail();

      // Prepare
      $this->prepareDetailView( $oView );

      // Output
      $oHTML = $this->getDetailView( $oView, PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables );
//       // ... sub-title
//       $sOutput .= $this->htmlSubTitle( $oView, 'S-image' );
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
      $oView = new PHP_APE_Explorer_Image_list();

      // Prepare
      $this->prepareListView( $oView );

      // Output
      $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full;
      if( self::$roEnvironment->getUserParameter( 'php_ape.explorer.image.thumbnail.list.use' ) )
        $iQueryMeta |= PHP_APE_Data_isQueryAbleResultSet::Disable_DeleteAble | PHP_APE_Data_isQueryAbleResultSet::Disable_InsertAble | PHP_APE_Data_isQueryAbleResultSet::Disable_UpdateAble | PHP_APE_Data_isQueryAbleResultSet::Disable_DetailAble;
      $oHTML = $this->getListView( $oView, $iQueryMeta, $amPassthruVariables );
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

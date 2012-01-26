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

/** Explorer (generic) controller
 *
 * @package PHP_APE_Explorer
 * @subpackage Control
 */
class PHP_APE_Explorer_Controller
extends PHP_APE_HTML_Controller
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Working environment
   * @var PHP_APE_Explorer_WorkSpace */
  static public $roEnvironment;

  /** Resources
   * @var array|string */
  static public $asResources;

  /** Directory parameters
   * @var array|string */
  private $asDirectoryParameters;

  /** Read authorization (cache)
   * @var boolean */
  private $bReadAuthorized;

  /** Insert authorization (cache)
   * @var boolean */
  private $bInsertAuthorized;

  /** Update authorization (cache)
   * @var boolean */
  private $bUpdateAuthorized;

  /** Delete authorization (cache)
   * @var boolean */
  private $bDeleteAuthorized;

  /** Image download authorization (cache)
   * @var boolean */
  private $bImageDownloadAuthorized;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Returns a new instance of this controller for the given path
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Explorer_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param string $sURL Controller's (root) URL
   * @param string $sPath Explorer's (relative) path (default to the REQUEST path if <SAMP>null</SAMP>)
   */
  public function __construct( $sURL, $sPath = null )
  {
    // Sanitize input
    if( !is_null( $sPath ) )
      $sPath = trim( PHP_APE_Type_Path::parseValue( $sPath ), '/' );
    
    // Parent constructor
    parent::__construct( 'PHP_APE_Explorer', $sURL, 'list' );

    // Load directory parameters
    if( file_exists( $this->__getDirectoryParametersFilepath( $sPath ) ) )
      $this->__loadDirectoryParameters( $sPath );
    else
      $this->asDirectoryParameters = array();
  }

  public static function __static()
  {
    if( is_null( self::$roEnvironment ) ) self::$roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    if( is_null( self::$asResources ) ) self::$asResources = self::$roEnvironment->loadProperties( 'PHP_APE_Explorer_Resources' );
  }


  /*
   * METHODS: HTML components
   ********************************************************************************/

  /** Returns the current directory's sub-directories browser (HTML list)
   *
   * @return string
   */
  public function htmlDirectoryBrowser( $bIsLeftBar = false )
  {
    // Environment
    $oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();

    // Output
    $sOutput .= '<UL>'."\r\n";

    // ... parent
    $sDirectoryName = ltrim( basename( $this->getExplorerPath() ), './' );
    if( strlen( $sDirectoryName ) > 0 )
    {
      $sExplorerPath = ltrim( dirname( $this->getExplorerPath() ), './' );
      $sURL_Content = $this->makeRequestURL( 'index.php', $sExplorerPath );
      if( $bIsLeftBar )
      {
        $sURL_LeftBar = $this->makeLeftBarURL( $sExplorerPath );
        $sOutput .= '<LI>'.PHP_APE_HTML_Tags::htmlAnchor( "javascript:parent.PHP_APE_Explorer_LeftBar.location.replace('".$oDataSpace_JavaScript->encodeData($sURL_LeftBar)."');parent.PHP_APE_Explorer_Content.location.href='".$oDataSpace_JavaScript->encodeData($sURL_Content)."';", '..' ).'</LI>'."\r\n";
      } else
        $sOutput .= '<LI>'.PHP_APE_HTML_Tags::htmlAnchor( "javascript:location.href='".$oDataSpace_JavaScript->encodeData($sURL_Content)."';", '..' ).'</LI>'."\r\n";
    }
    // ... children
    if( $this->isReadAuthorized() )
    {
      $asDirectoriesPaths = glob( PHP_APE_Util_File_Any::encodePath( $this->getFullPath().'/*' ) );
      foreach( $asDirectoriesPaths as $sDirectoryPath )
      {
        // ... check path
        if( !is_dir( $sDirectoryPath ) ) continue;
        if( !is_readable( $sDirectoryPath ) ) continue;
        $sExplorerPath = $this->getExplorerPath().'/'.PHP_APE_Util_File_Any::decodePath( basename( $sDirectoryPath ) );
        if( !PHP_APE_Explorer_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.explorer.auth.noconf' ) and
            !$this->hasDirectoryParameters( $sExplorerPath ) ) continue;

        // ... link
        $sURL_Content = $this->makeRequestURL( 'index.php', $sExplorerPath );
        if( $bIsLeftBar )
        {
          $sURL_LeftBar = $this->makeLeftBarURL( $sExplorerPath );
          $sOutput .= '<LI>'.PHP_APE_HTML_Tags::htmlAnchor( "javascript:parent.PHP_APE_Explorer_LeftBar.location.replace('".$oDataSpace_JavaScript->encodeData($sURL_LeftBar)."');parent.PHP_APE_Explorer_Content.location.href='".$oDataSpace_JavaScript->encodeData($sURL_Content)."';", basename( $sExplorerPath ) ).'</LI>'."\r\n";
        } else
          $sOutput .= '<LI>'.PHP_APE_HTML_Tags::htmlAnchor( "javascript:location.href='".$oDataSpace_JavaScript->encodeData($sURL_Content)."';", basename( $sExplorerPath ) ).'</LI>'."\r\n";
      }
    }

    // End
    $sOutput .= '</UL>'."\r\n";
    return $sOutput;
  }

  /** Returns this controller's preferences-setting bar
   *
   * @return string
   */
  public function htmlPreferencesControllerBar()
  {
    // Output
    $sOutput = null;
    // ... Directory browser
    $bUseLeftBar = self::$roEnvironment->getUserParameter( 'php_ape.explorer.frameset.leftbar.use' );
    if( !$bUseLeftBar )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
      $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'S-control', null, null, null, false );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( self::$asResources['label.preferences.directory.browser'].':', null, null, self::$asResources['tooltip.preferences.directory.browser'], null, false, false );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
      $bDirectoryBrowser_Use = self::$roEnvironment->getUserParameter( 'php_ape.explorer.directory.browser.use' );
      $sOutput .= '<INPUT TYPE="checkbox" CLASS="checkbox" ONCLICK="javascript:self.location.replace(PHP_APE_URL_addQuery(\''.self::$oDataSpace_JavaScript->encodeData( ltrim( self::$roEnvironment->makePreferencesURL( array( 'php_ape.explorer.directory.browser.use' => $bDirectoryBrowser_Use ? 0 : 1 ), null ), '?' ) ).'\',self.location.href.toString()));"'.( $bDirectoryBrowser_Use ? ' CHECKED': null ).'/>';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    }

    // End
    return $sOutput;
  }


  /*
   * METHODS: HTML components - OVERRIDE
   ********************************************************************************/

  /** Returns the page's header
   *
   * @return string
   */
  public function htmlHeader()
  {
    // Output
    $sOutput = null;

    // ... Application name
    $sOutput .= '<TABLE STYLE="WIDTH:100%;" CELLSPACING="0"><TR>'."\r\n";
    $sOutput .= '<TD STYLE="TEXT-ALIGN:left;VERTICAL-ALIGN:top;">'."\r\n";
    $sOutput .= '<H1>'.PHP_APE_WorkSpace::useEnvironment()->getVolatileParameter( 'php_ape.application.name' ).'</H1>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
    $sOutput .= '<DIV CLASS="do-not-print">'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
    $iSeparator = 0;

    // ... Frameset control
    $sOutput .= PHP_APE_HTML_Components::htmlControlFrameset( array( 'php_ape.explorer.frameset.leftbar.use' ),
                                                              array( self::$asResources['description.frameset.leftbar'] ),
                                                              array( 'S-frameset-left' ),
                                                              'top' );
    $iSeparator++;
      
    // ... Authentication
    if( PHP_APE_Auth_WorkSpace::useEnvironment()->isAuthenticationAllowed() )
    {
      $sQuery = preg_replace( '/&?FRAME=[^&]*/is', null , $_SERVER['QUERY_STRING'] );
      $sQuery = ltrim( $sQuery, '&' );
      $sAuthBackURL = PHP_APE_Util_URL::addVariable( $_SERVER['SCRIPT_NAME'], $sQuery );
      if( $iSeparator++ ) $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      $sOutput .= PHP_APE_HTML_Components::htmlAuthentication( $sAuthBackURL, 'top' );
    }

    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '</TD>'."\r\n";

    // ... Logo
    $sOutput .= '<TD STYLE="TEXT-ALIGN:right;VERTICAL-ALIGN:top;">'."\r\n";
    $sOutput .= '<IMG ALT="PHP-APE" TITLE="PHP-APE" WIDTH="48" HEIGHT="48" SRC="'.PHP_APE_HTML_WorkSpace::useEnvironment()->getVolatileParameter( 'php_ape.html.htdocs.url' ).'/img/php-ape-48x48.png"/>';
    $sOutput .= '</TD>'."\r\n";
    $sOutput .= '</TR></TABLE>'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlSeparator();

    // End
    return $sOutput;
  }

  /** Returns the page's title
   *
   * @return string
   */
  public function getTitle()
  {
    static $sTitle;

    // Check cached data
    // NOTE: data CAN NOT change within the same script, since the REQUEST data will necessarly be the same
    if( !is_null( $sTitle ) )
      return $sTitle;

    // Check cache
    if( strlen( $sTitle ) <= 0 and $this->hasDirectoryParameter( 'php_ape.explorer.title' ) )
      $sTitle = $this->getDirectoryParameter( 'php_ape.explorer.title' );
    if( strlen( $sTitle ) <= 0 )
      $sTitle = basename( $this->getExplorerPath() );
    if( strlen( $sTitle ) <= 0 and self::$roEnvironment->hasStaticParameter( 'php_ape.explorer.title' ) )
      $sTitle = self::$roEnvironment->getStaticParameter( 'php_ape.explorer.title' );
    if( strlen( $sTitle ) <= 0 )
      $sTitle = self::$asResources['title'];

    // End
    return $sTitle;
  }

  /** Returns the HTML page's (top) title
   *
   * @return string
   */
  public function htmlTitle()
  {
    return PHP_APE_HTML_SmartTags::htmlLabel( $this->getTitle(), 'M-browse', null, null, null, true, false, 'H1' );
  }

  /** Returns the HTML page's footer
   *
   * @return string
   */
  public function htmlFooter()
  {
    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlSeparator();
    $sOutput .= '<DIV CLASS="do-not-print" STYLE="FLOAT:left;PADDING:2px;">'."\r\n";
    $sOutput .= $this->htmlPreferencesControllerBar();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '<DIV CLASS="do-not-print" STYLE="FLOAT:right;PADDING:2px;">'."\r\n";
    $sOutput .= PHP_APE_HTML_Components::htmlPreferences();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '<DIV STYLE="CLEAR:both;"></DIV>'."\r\n";
    return $sOutput;
  }


  /*
   * METHODS: request
   ********************************************************************************/

  /** Returns the controller form/input handling URL
   *
   * @return string
   */
  public function getFormURL()
  {
    // Return frame-aware URL
    if( isset( $_GET['FRAME'] ) ) return PHP_APE_Util_URL::addVariable( parent::getFormURL(), array( 'FRAME' => $_GET['FRAME'] ) );
    return parent::getFormURL();
  }

  /** Returns the explorer's page URL for the given path and destination (view/action)
   *
   * <P><B>USAGE:</B> This methods returns the given URL with the appropriate query parameters,
   * so the explorer retrieves the appropriate path for display.</P>
   * <P><B>NOTE:</B> The explorer's path is encrypted, in order to avoid having it supplied from the client
   * without prior server proposal.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param string $sURL Target URL
   * @param string $sPath Explorer's path
   * @param string $sDestination Page controller destination (view/action)
   * @param mixed $mPrimaryKey Result set's data primary key (required for any destination other than 'list')
   * @param array|mixed $amPassthruVariables Variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   * @param array|string $asErrors Error messages (associating: <I>id</I> => <I>message</I>)
   * @param boolean $bUsedPopup Popup used to open form
   * @return string
   */
  public function makeRequestURL( $sURL = null, $sPath = null, $sDestination = null, $mPrimaryKey = null, $amPassthruVariables = null, $asErrors = null, $bUsedPopup = null )
  {
    // Frameset tag
    $sURL = PHP_APE_Type_Path::parseValue( $sURL );
    $sURL = preg_replace( '/&?FRAME=[^&]*/is', null, $sURL );
    $sURL = ltrim( $sURL, '&' );
    if( isset( $_GET['FRAME'] ) ) $sURL = PHP_APE_Util_URL::addVariable( $sURL, array( 'FRAME' => 'C' ) );

    // Additional passthru variables
    $amPassthruVariables_add = $this->getPassthruVariables( $sPath );
    if( is_array( $amPassthruVariables ) )
      $amPassthruVariables = array_merge( $amPassthruVariables, $amPassthruVariables_add );
    else
      $amPassthruVariables = $amPassthruVariables_add;

    // Retrieve URL
    return parent::makeRequestURL( $sURL, $sDestination, $mPrimaryKey, $amPassthruVariables, $asErrors, $bUsedPopup );
  }


  /** Returns the explorer's left-bar URL for the given path and destination (view/action)
   *
   * <P><B>USAGE:</B> This methods returns the given URL with the appropriate query parameters,
   * so the explorer retrieves the appropriate path for display.</P>
   * <P><B>NOTE:</B> The explorer's path is encrypted, in order to avoid having it supplied from the client
   * without prior server proposal.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param string $sPath Explorer's path
   * @return string
   */
  public function makeLeftBarURL( $sPath = null )
  {
    // Frameset tag
    $sURL = PHP_APE_Util_URL::addVariable( 'index.php', array( 'FRAME' => 'L' ) );

    // Additional passthru variables
    $amPassthruVariables = $this->getPassthruVariables( $sPath );

    // Retrieve URL
    return parent::makeRequestURL( $sURL, null, null, $amPassthruVariables );
  }

  /** Returns the explorer's passthru variables for the given path
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return array|string
   */
  public function getPassthruVariables( $sPath = null )
  {
    // Sanitize input
    if( is_null( $sPath ) )
      $sPath = $this->getExplorerPath();
    else
      $sPath = trim( PHP_APE_Type_Path::parseValue( $sPath ), '/' );

    // Passthru variables
    if( !empty( $sPath ) )
      $amPassthruVariables = array( '__PATH' => self::$roEnvironment->encryptData( $sPath ) );
    else
      $amPassthruVariables = array();

    // Output
    return $amPassthruVariables;
  }

  /** Returns the explorer's base path
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Explorer_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getBasePath()
  {
    static $sPath;

    // Check cached data
    // NOTE: data CAN NOT change within the same script, since the REQUEST data will necessarly be the same
    if( !is_null( $sPath ) )
      return $sPath;

    // Retrieve path
    $sPath = self::$roEnvironment->getStaticParameter( 'php_ape.explorer.data.path' );
    if( empty( $sPath ) )
      throw new PHP_APE_Explorer_Exception( __METHOD__, 'Missing data path' );

    // End
    return $sPath;
  }

  /** Returns the explorer's (relative) path
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getExplorerPath()
  {
    static $sPath;

    // Check cached data
    // NOTE: data CAN NOT change within the same script, since the REQUEST data will necessarly be the same
    if( !is_null( $sPath ) )
      return $sPath;

    // Arguments
    $rasRequestData =& $this->useRequestData();

    // Retrieve path
    if( array_key_exists( '__PATH', $rasRequestData ) )
      $sPath = self::$roEnvironment->decryptData( PHP_APE_Type_Path::parseValue( $rasRequestData['__PATH'] ) );
    if( is_null( $sPath ) )
      $sPath = '';

    // End
    return $sPath;
  }

  /** Returns the explorer's full path
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sPath Explorer's (relative) path (default to the REQUEST path if <SAMP>null</SAMP>)
   * @return string
   */
  final public function getFullPath( $sPath = null )
  {
    // Sanitize input
    if( is_null( $sPath ) ) $sPath = $this->getExplorerPath();
    else $sPath = trim( PHP_APE_Type_Path::parseValue( $sPath ), '/' );

    // End
    if( strlen( $sPath ) > 0 )
      return $this->getBasePath().'/'.$sPath;
    else
      return $this->getBasePath();
  }


  /*
   * METHODS: download
   ********************************************************************************/

  /** Returns the explorer's download URL for the (relative) file path and name
   *
   * <P><B>NOTE:</B> The download parameters are encrypted, in order to avoid having them supplied from the client
   * without prior server proposal.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sFileBasename Explorer's file basename (without any path)
   * @param string $sFileName File (save as) name
   * @return string
   */
  final public function makeDownloadURL( $sFileBasename, $sFileName = null )
  {
    // Sanitize input
    $sFileBasename = trim( basename( PHP_APE_Type_Path::parseValue( $sFileBasename ) ) );
    $sFileName = trim( basename( PHP_APE_Type_Path::parseValue( $sFileName ) ) );

    // Parameters
    $amParameters = array();
    $amParameters['salt'] = sha1( rand() ).md5( rand() ); // let's add some random (cryptographic) salt to the parameters
    $amParameters['path'] = $this->getFullPath().'/'.$sFileBasename;
    if( strlen( $sFileName ) > 0 ) $amParameters['as'] = $sFileName;

    // URL
    $sURL = $this->getURL().'/download.php';
    $sURL = PHP_APE_Util_URL::addVariable( $sURL, array( 'PHP_APE_Explorer' => self::$roEnvironment->encryptData( serialize( $amParameters ) ) ) );
    return $sURL;
  }

  /** Returns the (decrypted) download's parameters from the (GET only) request
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Explorer_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|mixed
   */
  final static public function getDownloadParameters()
  {
    // Check request
    if( !array_key_exists( 'PHP_APE_Explorer', $_GET ) )
      throw new PHP_APE_Explorer_Exception( __METHOD__, 'Missing parameters' );
      
    // File key
    return unserialize( self::$roEnvironment->decryptData( $_GET['PHP_APE_Explorer'] ) );
  }


  /*
   * METHODS: directory parameters
   ********************************************************************************/

  /** Returns the path of the file (persistent storage location) containing the directory parameters
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sPath Explorer's (relative) path (default to the REQUEST path if <SAMP>null</SAMP>)
   * @return string
   */
  final private function __getDirectoryParametersFilepath( $sPath = null )
  {
    return PHP_APE_Util_File_Any::encodePath( $this->getFullPath( $sPath ).'/'.PHP_APE_EXPLORER_CONF );
  }

  /** Loads the directory parameters
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link __construct()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Explorer_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sPath Explorer's (relative) path (default to the REQUEST path if <SAMP>null</SAMP>)
   */
  final private function __loadDirectoryParameters( $sPath = null )
  {

    // Parameters data file
    $sFilePath = $this->__getDirectoryParametersFilepath( $sPath );

    // Check cache file
    $bSaveCache = true;
    $sCachePath = PHP_APE_CACHE.'/PHP_APE_Explorer#'.sha1( $sFilePath ).md5( $sFilePath ).'.data';
    if( file_exists( $sCachePath ) and filemtime( $sCachePath ) > filemtime( $sFilePath ) )
    {
      $this->asDirectoryParameters = unserialize( file_get_contents( $sCachePath, false ) );
      if( is_array( $this->asDirectoryParameters ) ) $bSaveCache = false;
    }

    // Load, parse and check parameters data
    if( !is_array( $this->asDirectoryParameters ) )
    {
      require( $sFilePath );
      if( !isset( $_CONFIG ) or !is_array( $_CONFIG ) )
        throw new PHP_APE_Explorer_Exception( __METHOD__, 'Missing configuration; Path: '.$sFilePath );
      $this->asDirectoryParameters = $_CONFIG;
      $this->__verifyDirectoryParameters();
    }

    // Save file cache
    if( $bSaveCache )
      file_put_contents( $sCachePath, serialize( $this->asDirectoryParameters ), LOCK_EX );

  }

  /** Verifies the directory parameters
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link saveDirectoryParameters()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __verifyDirectoryParameters()
  {
    self::$roEnvironment->verifyParameters( $this->asDirectoryParameters );
  }

  /** Returns whether directory parameters exists
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sPath Explorer's (relative) path (default to the REQUEST path if <SAMP>null</SAMP>)
   * @return boolean
   */
  final public function hasDirectoryParameters( $sPath = null )
  {
    return file_exists( $this->__getDirectoryParametersFilepath( $sPath ) );
  }
  
  /** Returns whether the given directory parameter value exists
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @return boolean
   */
  final public function hasDirectoryParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    return array_key_exists( $sName, $this->asDirectoryParameters );
  }
  
  /** Returns the given directory parameter value
   *
   * <P><B>RETURNS:</B> <SAMP>null</SAMP> if the parameter is not existing.</P>
   * <P><B>LOGS:</B> <SAMP>WARNING</SAMP> message if the parameter is not existing.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param boolean $bHierarchicalLookup Search parameter value in parent environment stores (static, etc.)
   * @return mixed
   */
  final public function getDirectoryParameter( $sName, $bHierarchicalLookup = true )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    if( array_key_exists( $sName, $this->asDirectoryParameters ) ) return $this->asDirectoryParameters[ $sName ];
    if( $bHierarchicalLookup and self::$roEnvironment->hasStaticParameter( $sName ) ) return self::$roEnvironment->getStaticParameter( $sName );

    // undefined parameter
    self::$roEnvironment->log( __METHOD__, 'Undefined parameter; Name: '.$sName, E_USER_WARNING );
    return null;
  }


  /*
   * METHODS: authorization
   ********************************************************************************/

  public function isReadAuthorized()
  {
    // Authorization cache
    if( is_null( $this->bReadAuthorized ) )
    {
      // ... check directory parameters
      if( !PHP_APE_Explorer_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.explorer.auth.noconf' ) and
          !$this->hasDirectoryParameters() )
        throw new PHP_APE_Explorer_Exception( __METHOD__, 'Missing directory-specific configuration' );

      // ... check permissions
      $this->bReadAuthorized = false;
      $sDirectoryPath = PHP_APE_Util_File_Any::encodePath( $this->getFullPath() );
      if( is_readable( $sDirectoryPath ) )
      {
        $oAuthenticationToken = PHP_APE_Auth_WorkSpace::useEnvironment()->getAuthenticationToken();
        $sDirectoryName = basename( $this->getExplorerPath() );
        $this->bReadAuthorized =
          $this->getDirectoryParameter( 'php_ape.explorer.auth.read.any' ) ||
          ( $this->getDirectoryParameter( 'php_ape.explorer.auth.read.dirmatch' ) &&
            ( $sDirectoryName == $oAuthenticationToken->getUserID() ||
              in_array( $sDirectoryName, $oAuthenticationToken->getGroupIDs() )
              )
            ) ||
          in_array( $oAuthenticationToken->getUserID(), $this->getDirectoryParameter( 'php_ape.explorer.auth.read.users' ) ) ||
          count( array_intersect( $oAuthenticationToken->getGroupIDs(), $this->getDirectoryParameter( 'php_ape.explorer.auth.read.groups' ) ) ) > 0;
      }
    }
    
    // Output
    return $this->bReadAuthorized;
  }

  public function isInsertAuthorized()
  {
    // Authorization cache
    if( is_null( $this->bInsertAuthorized ) )
    {
      $this->bInsertAuthorized = false;
      if( $this->isReadAuthorized() and is_writable( PHP_APE_Util_File_Any::encodePath( $this->getFullPath() ) ) )
      {
        $oAuthenticationToken = PHP_APE_Auth_WorkSpace::useEnvironment()->getAuthenticationToken();
        $sDirectoryName = basename( $this->getExplorerPath() );
        $this->bInsertAuthorized =
          $this->getDirectoryParameter( 'php_ape.explorer.auth.insert.any' ) ||
          ( $this->getDirectoryParameter( 'php_ape.explorer.auth.insert.dirmatch' ) &&
            ( $sDirectoryName == $oAuthenticationToken->getUserID() ||
              in_array( $sDirectoryName, $oAuthenticationToken->getGroupIDs() )
              )
            ) ||
          in_array( $oAuthenticationToken->getUserID(), $this->getDirectoryParameter( 'php_ape.explorer.auth.insert.users' ) ) ||
          count( array_intersect( $oAuthenticationToken->getGroupIDs(), $this->getDirectoryParameter( 'php_ape.explorer.auth.insert.groups' ) ) ) > 0;
      }
    }
    
    // Output
    return $this->bInsertAuthorized;
  }

  public function isUpdateAuthorized()
  {
    // Authorization cache
    if( is_null( $this->bUpdateAuthorized ) )
    {
      $this->bUpdateAuthorized = false;
      if( $this->isReadAuthorized() )
      {
        $oAuthenticationToken = PHP_APE_Auth_WorkSpace::useEnvironment()->getAuthenticationToken();
        $sDirectoryName = basename( $this->getExplorerPath() );
        $this->bUpdateAuthorized =
          $this->getDirectoryParameter( 'php_ape.explorer.auth.update.any' ) ||
          ( $this->getDirectoryParameter( 'php_ape.explorer.auth.update.dirmatch' ) &&
            ( $sDirectoryName == $oAuthenticationToken->getUserID() ||
              in_array( $sDirectoryName, $oAuthenticationToken->getGroupIDs() )
              )
            ) ||
          in_array( $oAuthenticationToken->getUserID(), $this->getDirectoryParameter( 'php_ape.explorer.auth.update.users' ) ) ||
          count( array_intersect( $oAuthenticationToken->getGroupIDs(), $this->getDirectoryParameter( 'php_ape.explorer.auth.update.groups' ) ) ) > 0;
      }
    }

    // Output
    return $this->bUpdateAuthorized;
  }

  public function isDeleteAuthorized()
  {
    // Authorization cache
    if( is_null( $this->bDeleteAuthorized ) )
    {
      $this->bDeleteAuthorized = false;
      if( $this->isReadAuthorized() )
      {
        $oAuthenticationToken = PHP_APE_Auth_WorkSpace::useEnvironment()->getAuthenticationToken();
        $sDirectoryName = basename( $this->getExplorerPath() );
        $this->bDeleteAuthorized =
          $this->getDirectoryParameter( 'php_ape.explorer.auth.delete.any' ) ||
          ( $this->getDirectoryParameter( 'php_ape.explorer.auth.delete.dirmatch' ) &&
            ( $sDirectoryName == $oAuthenticationToken->getUserID() ||
              in_array( $sDirectoryName, $oAuthenticationToken->getGroupIDs() )
              )
            ) ||
          in_array( $oAuthenticationToken->getUserID(), $this->getDirectoryParameter( 'php_ape.explorer.auth.delete.users' ) ) ||
          count( array_intersect( $oAuthenticationToken->getGroupIDs(), $this->getDirectoryParameter( 'php_ape.explorer.auth.delete.groups' ) ) ) > 0;
      }
    }
    
    // Output
    return $this->bDeleteAuthorized;
  }

  public function isImageDownloadAuthorized()
  {
    // Authorization cache
    if( is_null( $this->bImageDownloadAuthorized ) )
    {
      $this->bImageDownloadAuthorized = false;
      if( $this->isReadAuthorized() )
      {
        $oAuthenticationToken = PHP_APE_Auth_WorkSpace::useEnvironment()->getAuthenticationToken();
        $sDirectoryName = basename( $this->getExplorerPath() );
        $this->bImageDownloadAuthorized =
          $this->getDirectoryParameter( 'php_ape.explorer.auth.image.download.any' ) ||
          ( $this->getDirectoryParameter( 'php_ape.explorer.auth.image.download.dirmatch' ) &&
            ( $sDirectoryName == $oAuthenticationToken->getUserID() ||
              in_array( $sDirectoryName, $oAuthenticationToken->getGroupIDs() )
              )
            ) ||
          in_array( $oAuthenticationToken->getUserID(), $this->getDirectoryParameter( 'php_ape.explorer.auth.image.download.users' ) ) ||
          count( array_intersect( $oAuthenticationToken->getGroupIDs(), $this->getDirectoryParameter( 'php_ape.explorer.auth.image.download.groups' ) ) ) > 0;
      }
    }
    
    // Output
    return $this->bImageDownloadAuthorized;
  }


  /*
   * METHODS: actions/view
   ********************************************************************************/

  /** Returns this controller's (HTML) page
   *
   * @return string
   */
  public function htmlPage()
  {
    // Output
    $sOutput = null;

    // ... HTML
    $sOutput .= PHP_APE_HTML_Tags::htmlDocumentOpen();

    // ... HEAD
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadOpen();
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadTitle( $this->getTitle() );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlCSS();
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadClose();

    // ... PAGE
    $bUseLeftBar = self::$roEnvironment->getUserParameter( 'php_ape.explorer.frameset.leftbar.use' );
    $bShowFrameSet = $bUseLeftBar;
    $bInFrameSet = false;
    if( isset( $_GET['FRAME'] ) ) 
    {
      $bShowFrameSet = false;
      switch( $_GET['FRAME'] )
      {
      case 'T': $bInFrameSet = true; $sOutput .= $this->htmlTopBar(); break;
      case 'L': $bInFrameSet = true; $sOutput .= $this->htmlLeftBar(); break;
      case 'C': $bInFrameSet = true; $sOutput .= $this->htmlContent(); break;
      default: $bInFrameSet = false;
      }
    }

    if( $bShowFrameSet )
    {
      $iTopBar_Height = self::$roEnvironment->getStaticParameter( 'php_ape.explorer.frameset.topbar.height' );
      $iLeftBar_Width = self::$roEnvironment->getStaticParameter( 'php_ape.explorer.frameset.leftbar.width' );
      $sOutput .= '<FRAMESET ROWS="'.$iTopBar_Height.',*" BORDER="0">'."\r\n";
      $sOutput .= '<FRAME CLASS="APE-top" NAME="PHP_APE_Explorer_TopBar" FRAMEBORDER="0" SCROLLING="no" NORESIZE SRC="index.php?FRAME=T'.($_SERVER['QUERY_STRING']?'&'.$_SERVER['QUERY_STRING']:null).'">'."\r\n";
      $sOutput .= '<FRAMESET COLS="'.$iLeftBar_Width.',*" BORDER="1">'."\r\n";
      $sOutput .= '<FRAME CLASS="APE-left" NAME="PHP_APE_Explorer_LeftBar" FRAMEBORDER="1" SCROLLING="auto" SRC="index.php?FRAME=L'.($_SERVER['QUERY_STRING']?'&'.$_SERVER['QUERY_STRING']:null).'">'."\r\n";
      $sOutput .= '<FRAME CLASS="APE-content" NAME="PHP_APE_Explorer_Content" FRAMEBORDER="0" SCROLLING="auto" SRC="index.php?FRAME=C'.($_SERVER['QUERY_STRING']?'&'.$_SERVER['QUERY_STRING']:null).'">'."\r\n";
      $sOutput .= '</FRAMESET>'."\r\n";
      $sOutput .= '</FRAMESET>'."\r\n";
    }
    elseif( !$bInFrameSet )
      $sOutput .= $this->htmlContent();

    // ... END
    $sOutput .= PHP_APE_HTML_Tags::htmlDocumentClose();

    // End
    return $sOutput;
  }

  /** Returns this controller's (HTML) top-bar
   *
   * @return string
   */
  public function htmlTopBar()
  {
    // Output
    $sOutput = null;

    // ... HTML
    $sOutput .= PHP_APE_HTML_Tags::htmlDocumentOpen();

    // ... HEAD
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadOpen();
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadCharSet();
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlCSS();
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadClose();

    // ... BODY
    $sOutput .= PHP_APE_HTML_Tags::htmlBodyOpen( 'APE-top' );
    $sOutput .= '<DIV CLASS="APE">'."\r\n";

    // ... Header
    $sOutput .= $this->htmlHeader();

    // ... END
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= PHP_APE_HTML_Tags::htmlBodyClose();

    $sOutput .= PHP_APE_HTML_Tags::htmlDocumentClose();

    // End
    return $sOutput;
  }

  /** Returns this controller's (HTML) left-bar
   *
   * @return string
   */
  public function htmlLeftBar()
  {
    // Output
    $sOutput = null;

    // ... HTML
    $sOutput .= PHP_APE_HTML_Tags::htmlDocumentOpen();

    // ... HEAD
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadOpen();
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadCharSet();
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlCSS();
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadClose();

    // ... BODY
    $sOutput .= PHP_APE_HTML_Tags::htmlBodyOpen( 'APE-left' );
    $sOutput .= '<DIV CLASS="APE">'."\r\n";

    try
    {
      // ... Directory Explorer
      $bDirectoryBrowser_Use = self::$roEnvironment->getUserParameter( 'php_ape.explorer.directory.browser.use' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( 'WIDTH:100%;', 'WIDTH:100%;' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( self::$asResources['name.leftbar.directories'], 'M-folder', null, self::$asResources['description.leftbar.directories'], null, true, false, 'H1' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
      $sOutput .= PHP_APE_HTML_Components::htmlControlFrameset( array( 'php_ape.explorer.directory.browser.use' ),
                                                                array( self::$asResources['description.leftbar.directories.use'] ),
                                                                array( $bDirectoryBrowser_Use ? 'S-minimize' : 'S-maximize' ),
                                                                'self' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();

      // ... content
      if( $bDirectoryBrowser_Use ) $sOutput .= $this->htmlDirectoryBrowser( true );

      // ... Additional left-bar
      $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      $sOutput .= $this->htmlLeftBar_Add();
    }
    catch( PHP_APE_Auth_AuthorizationException $e )
    {
      $sOutput .= PHP_APE_HTML_Components::htmlAuthorizationException( $e );
    }
    catch( PHP_APE_Exception $e )
    {
      $sOutput .= PHP_APE_HTML_Components::htmlUnexpectedException( $e );
    }

    // ... END
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= PHP_APE_HTML_Tags::htmlBodyClose();

    $sOutput .= PHP_APE_HTML_Tags::htmlDocumentClose();

    // End
    return $sOutput;
  }

  /** Returns this controller's (HTML) additional left-bar content
   *
   * @return string
   */
  public function htmlLeftBar_Add()
  {
    return null;
  }

  /** Returns this controller's (HTML) document
   *
   * @return string
   */
  public function htmlContent()
  {
    // Output
    $sOutput = null;

    // Controller
    $bUseLeftBar = self::$roEnvironment->getUserParameter( 'php_ape.explorer.frameset.leftbar.use' );
    $bIsPopup = $this->isPopup();
    $sDestination = $this->getDestination();

    // ... HTML
    $sOutput .= PHP_APE_HTML_Tags::htmlDocumentOpen();

    // ... HEAD
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadOpen();
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadCharSet();
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlCSS();
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadTitle( $this->getTitle() );
    $sOutput .= PHP_APE_HTML_Tags::htmlHeadClose();

    // ... BODY
    $sOutput .= PHP_APE_HTML_Tags::htmlBodyOpen( 'APE' );
    $sOutput .= '<SCRIPT TYPE="text/javascript">top.document.title=self.document.title;</SCRIPT>'."\r\n";
    $sOutput .= '<DIV CLASS="APE">'."\r\n";

    // ... Header
    if( !$bUseLeftBar and !$bIsPopup ) $sOutput .= $this->htmlHeader( $bFormatForPrint );

    // ... Title
    $sOutput .= '<DIV STYLE="FLOAT:left;PADDING:2px;">'."\r\n";
    $sOutput .= $this->htmlTitle();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '<DIV STYLE="CLEAR:both;"></DIV>'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();

    // ... View
    $sOutput .= '<BLOCKQUOTE>'."\r\n";
    try 
    {
      $sOutput .= $this->htmlViewOrAction();
    }
    catch( PHP_APE_Auth_AuthorizationException $e )
    {
      $sOutput .= PHP_APE_HTML_Components::htmlAuthorizationException( $e );
    }
    catch( PHP_APE_Exception $e )
    {
      $sOutput .= PHP_APE_HTML_Components::htmlUnexpectedException( $e );
    }
    $sOutput .= '</BLOCKQUOTE>'."\r\n";


    // ... Footer
    if( !$bIsPopup ) $sOutput .= $this->htmlFooter();

    // ... END
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= PHP_APE_HTML_Tags::htmlBodyClose();

    $sOutput .= PHP_APE_HTML_Tags::htmlDocumentClose();

    // End
    return $sOutput;
  }

}

// Initialize static fields
PHP_APE_Explorer_Controller::__static();

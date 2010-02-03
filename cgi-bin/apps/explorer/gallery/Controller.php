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

/** Gallery controller
 *
 * @package PHP_APE_Explorer
 * @subpackage Control
 */
class PHP_APE_Explorer_Gallery_Controller
extends PHP_APE_Explorer_Image_Controller
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
    parent::__construct( $sPath );
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
   * METHODS: actions/view - OVERRIDE
   ********************************************************************************/

  public function htmlFrameSet()
  {
    return $this->htmlContent();
  }

  public function htmlContent()
  {
    // Output
    $sOutput = null;

    // Controller
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
    $sOutput .= '<DIV CLASS="APE">'."\r\n";

    // ... Header
    if( !$bIsPopup ) $sOutput .= $this->htmlHeader();

    // ... Title
    if( !$bIsPopup and ( empty( $sDestination ) or $sDestination == 'list' or $sDestination == 'folders' ) )
    {
      $sOutput .= '<DIV CLASS="do-not-print" STYLE="FLOAT:right;PADDING:2px;">'."\r\n";
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
      if( $sDestination == 'folders' )
        $sOutput .= $this->htmlImageBrowser();
      else
        $sOutput .= $this->htmlFolderBrowser();
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      $sOutput .= PHP_APE_HTML_Components::htmlAuthentication();
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
      $sOutput .= '</DIV>'."\r\n";
    }
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
    $iPrimaryKey = (integer)$this->getPrimaryKey();
    $bIsPopup = $this->isPopup();

    // Actions / Views
    switch( $sDestination )
    {

    case 'detail':
      // Database object
      $oView = new PHP_APE_Explorer_Gallery_detail();

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
      $sOutput .= $this->htmlDetailControls( $iPrimaryKey );
      $oHTML->prefUseHeader( false );
      $oHTML->prefUseFooter( false );
      $oHTML->prefUseDisplayPreferences( false );
      $sOutput .= $oHTML->html();
      $sOutput .= $this->htmlDetailControls( $iPrimaryKey );
    break;

    case 'folders':
      $sOutput .= '<UL>'."\r\n";
      // ... parent
      $sDirectoryName = ltrim( basename( $this->getExplorerPath() ), './' );
      if( strlen( $sDirectoryName ) > 0 )
      {
        $sExplorerPath = ltrim( dirname( $this->getExplorerPath() ), './' );
        $sOutput .= '<LI>'.PHP_APE_HTML_Tags::htmlAnchor( $this->makeRequestURL( 'index.php', $sExplorerPath, 'folders' ), '..' ).'</LI>'."\r\n";
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
          $sOutput .= '<LI>'.PHP_APE_HTML_Tags::htmlAnchor( $this->makeRequestURL( 'index.php', $sExplorerPath, 'folders' ), basename( $sExplorerPath ) ).'</LI>'."\r\n";
        }
      }
      $sOutput .= '</UL>'."\r\n";
      $sOutput .= PHP_APE_HTML_SmartTags::htmlSeparator();

    default:
    case 'list':
      // Database object
      $oView = new PHP_APE_Explorer_Gallery_list();

      // Prepare
      $this->prepareListView( $oView );

      // Output
      $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full | PHP_APE_Data_isQueryAbleResultSet::Disable_DeleteAble | PHP_APE_Data_isQueryAbleResultSet::Disable_InsertAble | PHP_APE_Data_isQueryAbleResultSet::Disable_UpdateAble | PHP_APE_Data_isQueryAbleResultSet::Disable_DetailAble;
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
      $oHTML->prefUseHeader( false );
      $oHTML->prefUseFooter( false );
      $oHTML->prefUseDisplayPreferences( false );
      $oHTML->prefUseOrderPreferences( false );
      $sOutput .= $oHTML->html();
      break;

    }

    // End
    return $sOutput;
  }

  public function htmlImageBrowser()
  {
    // Output
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Explorer_Gallery_Resources' );
    $sOutput = null;

    // Browser button
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.images'], 'S-image', $this->makeRequestURL( 'index.php' ), $asResources['tooltip.images'], null, true, false );

    // End
    return $sOutput;
  }

  public function htmlFolderBrowser()
  {
    // Output
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Explorer_Gallery_Resources' );
    $sOutput = null;

    // Browser button
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.folders'], 'S-folder', $this->makeRequestURL( 'index.php', null, 'folders' ), $asResources['tooltip.folders'], null, true, false );

    // End
    return $sOutput;
  }

  public function htmlDetailControls( $iPrimaryKey )
  {
    // Controller
    $bIsPopup = $this->isPopup();
    $rasRequestData =& $this->useRequestData();

    // Output
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Explorer_Gallery_Resources' );
    $sOutput = null;

    // Controls
    $iLastPrimaryKey = array_key_exists( '__LAST', $rasRequestData ) ? (integer)$rasRequestData['__LAST'] : 0;
    $amLastPrimaryKey = $iLastPrimaryKey > 0 ? array( '__LAST' => $iLastPrimaryKey ) : null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
    if( !$bIsPopup ) $sOutput .= PHP_APE_HTML_Components::htmlBack( $this->makeRequestURL( 'index.php', null, 'list' ) );
    if( $iPrimaryKey > 0 or $iPrimaryKey < $iLastPrimaryKey )
    {
      if( !$bIsPopup ) $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.browse'].':', null, null, $asResources['tooltip.browse'], null, false, false );
      if( $iPrimaryKey > 0 )
      {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'M-first', $this->makeRequestURL( 'index.php', null, 'detail', '0', $amLastPrimaryKey, null, $bIsPopup ), $asResources['tooltip.first'], null, true );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'M-previous', $this->makeRequestURL( 'index.php', null, 'detail', (string)($iPrimaryKey-1), $amLastPrimaryKey, null, $bIsPopup ), $asResources['tooltip.previous'], null, true );
      }
      if( $iPrimaryKey < $iLastPrimaryKey )
      {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'M-next', $this->makeRequestURL( 'index.php', null, 'detail', (string)($iPrimaryKey+1), $amLastPrimaryKey, null, $bIsPopup ), $asResources['tooltip.next'], null, true );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'M-last', $this->makeRequestURL( 'index.php', null, 'detail', (string)$iLastPrimaryKey, $amLastPrimaryKey, null, $bIsPopup ), $asResources['tooltip.last'], null, true );
      }
    }
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();

    // End
    return $sOutput;
  }

}

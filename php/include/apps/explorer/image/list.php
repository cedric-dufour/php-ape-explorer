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

/** Image (list) view
 *
 * @package PHP_APE_Explorer
 * @subpackage Views
 */
class PHP_APE_Explorer_Image_list
extends PHP_APE_Explorer_Image_view
implements PHP_APE_Data_isListAbleResultSet, PHP_APE_HTML_hasSmarty, PHP_APE_Data_isDetailAble
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = 'ImageList' )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_Image_Resources' );

    // Parent constructor
    parent::__construct( $mID, $asResources['name.view.list'], $asResources['description.view.list'] );

    // Customize fields
    $this->useElement( 'extension' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
    $this->useElement( 'mode' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
    $this->useElement( 'user' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
    $this->useElement( 'uid' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
    $this->useElement( 'group' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
    $this->useElement( 'gid' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
    $this->useElement( 'accessed' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
    $this->useElement( 'modified' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
    $this->useElement( 'changed' )->addMeta( PHP_APE_Data_Field::Feature_HideAlways );
  }


  /*
   * METHODS: PHP_APE_HTML_hasSmarty - IMPLEMENT
   ********************************************************************************/

  public function hasSmarty()
  {
    return true;
  }

  public function &useSmarty()
  {
    static $oSmarty;
    if( is_null( $oSmarty ) ) 
    {
      if( PHP_APE_Explorer_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.explorer.image.thumbnail.list.use' ) )
        $oSmarty = new PHP_APE_HTML_Smarty( 'thumbnails', 'smarty.tpl', dirname( __FILE__ ) );
      else
        $oSmarty = new PHP_APE_HTML_Smarty( 'list', 'smarty.tpl', dirname( __FILE__ ) );
    }
    return $oSmarty;
  }


  /*
   * METHODS: PHP_APE_HTML_hasOutputHandler - OVERRIDE
   ********************************************************************************/

  public function getHTMLOutput( $mKey = null )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $roController =& PHP_APE_Explorer_WorkSpace::useImageController();

    // Output override
    if( $mKey == 'image' )
    {
      // ... file
      $sFileBasename = $this->getBasename();
      $sFilePath = PHP_APE_Util_File_Any::encodePath( $this->getDirectoryPath().'/'.$sFileBasename );

      // ... thumbnail
      $aiDimension = $this->getDimension();
      $bUseThumbnail = $roEnvironment->getUserParameter( 'php_ape.explorer.image.thumbnail.list.use' );
      $iGauge =
        $bUseThumbnail ?
        $roController->getDirectoryParameter( 'php_ape.explorer.image.size.thumbnail' ) :
        $roController->getDirectoryParameter( 'php_ape.explorer.image.size.list' );
      $aiGauge = array( $iGauge, $iGauge );
      $aiDimension_thumbnail = PHP_APE_Util_Image_Any::getDimensionGauge( $aiDimension, $aiGauge );
      $iMargin_horz = $aiGauge[0] - $aiDimension_thumbnail[0];
      $iMargin_left = floor( (float)$iMargin_horz / 2.0 );
      $iMargin_right = ceil( (float)$iMargin_horz / 2.0 );
      $iMargin_vert = $aiGauge[1] - $aiDimension_thumbnail[1];
      $iMargin_top = floor( (float)$iMargin_vert / 2.0 );
      $iMargin_bottom = ceil( (float)$iMargin_vert / 2.0 );
      $sIMG = '<IMG SRC="'.$roController->makeImageURL( $sFileBasename, $aiGauge ).'"';
      $sIMG .= ' STYLE="WIDTH:'.$aiDimension_thumbnail[0].'px;HEIGHT:'.$aiDimension_thumbnail[1].'px;MARGIN:'.$iMargin_top.'px '.$iMargin_right.'px '.$iMargin_bottom.'px '.$iMargin_left.'px;"';
      $sIMG .= ' />';

      // ... hyperlink
      $sURL = $roController->makeRequestURL( 'index.php', null, 'detail', $this->getPrimaryKey() );
      return PHP_APE_HTML_Tags::htmlAnchor( $sURL, $sIMG, null, null, true );
    }

    // Default
    return parent::getHTMLOutput( $mKey );
  }


  /*
   * METHODS: PHP_APE_Data_isDetailAble - IMPLEMENT
   ********************************************************************************/

  public function getDetailView()
  {
    return new PHP_APE_Explorer_Image_detail();
  }

  public function isDetailAuthorized()
  {
    return true;
  }

}

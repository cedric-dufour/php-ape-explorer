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

/** Image (detail) view
 *
 * @package PHP_APE_Explorer
 * @subpackage Views
 */
class PHP_APE_Explorer_Image_detail
extends PHP_APE_Explorer_Image_view
implements PHP_APE_Data_isDetailAbleResultSet, PHP_APE_HTML_hasSmarty, PHP_APE_Data_isListAble
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = 'ImageDetail' )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_Image_Resources' );

    // Parent constructor
    parent::__construct( $mID, $asResources['name.view.detail'], $asResources['description.view.detail'] );

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
      $oSmarty = new PHP_APE_HTML_Smarty( 'detail', 'smarty.tpl', dirname( __FILE__ ) );
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
      $bUseThumbnail = $roEnvironment->getUserParameter( 'php_ape.explorer.image.thumbnail.detail.use' );
      if( $bUseThumbnail )
        $iGauge = $roController->getDirectoryParameter( 'php_ape.explorer.image.size.thumbnail' );
      else 
      {
        $iGauge = $roEnvironment->getUserParameter( 'php_ape.explorer.image.size.detail' );
        $aiDetailSize_Choices = $roEnvironment->getStaticParameter( 'php_ape.explorer.image.size.detail.choices' );
        if( !in_array( $iGauge, $aiDetailSize_Choices ) ) $iGauge = $aiDetailSize_Choices[0];
      }
      $aiGauge = array( $iGauge, $iGauge );
      $aiDimension_thumbnail = PHP_APE_Util_Image_Any::getDimensionGauge( $aiDimension, $aiGauge );
      $iMargin_horz = $aiGauge[0] - $aiDimension_thumbnail[0];
      $iMargin_left = $bUseThumbnail ? floor( (float)$iMargin_horz / 2.0 ) : 0;
      $iMargin_right = $bUseThumbnail ? ceil( (float)$iMargin_horz / 2.0 ) : 0;
      $iMargin_vert = $aiGauge[1] - $aiDimension_thumbnail[1];
      $iMargin_top = $bUseThumbnail ? floor( (float)$iMargin_vert / 2.0 ) : 0;
      $iMargin_bottom = $bUseThumbnail ? ceil( (float)$iMargin_vert / 2.0 ) : 0;
      $sIMG = '<IMG SRC="'.$roController->makeImageURL( $sFileBasename, $aiGauge ).'"';
      $sIMG .= ' STYLE="WIDTH:'.$aiDimension_thumbnail[0].'px;HEIGHT:'.$aiDimension_thumbnail[1].'px;MARGIN:'.$iMargin_top.'px '.$iMargin_right.'px '.$iMargin_bottom.'px '.$iMargin_left.'px;"';
      $sIMG .= ' />';

      // ... check authorization
      if( $roController->isImageDownloadAuthorized() )
      {
        // ... hyperlink
        $sURL = $roController->makeDownloadURL( $sFileBasename );
        return PHP_APE_HTML_Tags::htmlAnchor( $sURL, $sIMG, null, null, true );
      }
      else
        return $sIMG;
    }

    // Default
    return parent::getHTMLOutput( $mKey );
  }


  /*
   * METHODS: PHP_APE_Data_isListAble - IMPLEMENT
   ********************************************************************************/

  public function getListView()
  {
    return new PHP_APE_Explorer_Image_list();
  }

  public function isListAuthorized()
  {
    return true;
  }

}

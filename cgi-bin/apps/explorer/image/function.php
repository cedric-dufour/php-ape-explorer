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

/** Image (generic) function
 *
 * @package PHP_APE_Explorer
 * @subpackage Functions
 */
abstract class PHP_APE_Explorer_Image_function
extends PHP_APE_Data_Function
implements PHP_APE_Data_hasAuthorization
{
  
  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID, PHP_APE_Type_Scalar $oResult, $sName = null, $sDescription = null )
  {
    // Controller
    $roController =& PHP_APE_Explorer_WorkSpace::useImageController();

    // Check authorization
    if( !$roController->isReadAuthorized() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Access denied' );

    // Parent constructor
    parent::__construct( $mID, $oResult, $sName, $sDescription );
  }
  

  /*
   * METHODS: initialization
   ********************************************************************************/

  /** Retrieves the arguments (template) objects
   *
   * @param string $sKeyPrefix Arguments keys prefix
   * @return array|PHP_APE_Database_Argument
   */
  public static function getArgumentsTemplates( $sKeyPrefix = null )
  {
    // Sanitize input
    $sKeyPrefix = PHP_APE_Type_Index::parseValue( $sKeyPrefix );

    // Resources
    $asResources_File = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Util_File_Resources' );
    $asResources_Image = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Util_Image_Resources' );

    // Arguments
    $aoArguments =
      array(

            $sKeyPrefix.'name' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'name',
                                       new PHP_APE_Type_String(),
                                       PHP_APE_Data_Argument::Type_PrimaryKey,
                                       $asResources_File['name.name'],
                                       $asResources_File['description.name']
                                       ),

            $sKeyPrefix.'iptc_name' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'iptc_name',
                                       new PHP_APE_Type_String( null, null, 100 ),
                                       PHP_APE_Data_Argument::Type_Data,
                                       $asResources_Image['name.name'],
                                       $asResources_Image['description.name']
                                       ),

            $sKeyPrefix.'iptc_headline' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'iptc_headline',
                                       new PHP_APE_Type_Text( null, null, 1000 ),
                                       PHP_APE_Data_Argument::Type_Data,
                                       $asResources_Image['name.headline'],
                                       $asResources_Image['description.headline']
                                       ),

            $sKeyPrefix.'iptc_caption' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'iptc_caption',
                                       new PHP_APE_Type_Text( null, null, 1000 ),
                                       PHP_APE_Data_Argument::Type_Data,
                                       $asResources_Image['name.caption'],
                                       $asResources_Image['description.caption']
                                       ),

            $sKeyPrefix.'iptc_author' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'iptc_author',
                                       new PHP_APE_Type_String( null, null, 100 ),
                                       PHP_APE_Data_Argument::Type_Data,
                                       $asResources_Image['name.author'],
                                       $asResources_Image['description.author']
                                       ),

            $sKeyPrefix.'iptc_copyright' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'iptc_copyright',
                                       new PHP_APE_Type_String( null, null, 100 ),
                                       PHP_APE_Data_Argument::Type_Data,
                                       $asResources_Image['name.copyright'],
                                       $asResources_Image['description.copyright']
                                       ),

            $sKeyPrefix.'iptc_category' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'iptc_category',
                                       new PHP_APE_Type_String( null, null, 100 ),
                                       PHP_APE_Data_Argument::Type_Data,
                                       $asResources_Image['name.category'],
                                       $asResources_Image['description.category']
                                       ),

            $sKeyPrefix.'iptc_subcategories' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'iptc_subcategories',
                                       new PHP_APE_Type_String( null, null, 250 ),
                                       PHP_APE_Data_Argument::Type_Data,
                                       $asResources_Image['name.subcategories'],
                                       $asResources_Image['description.subcategories']
                                       ),

            $sKeyPrefix.'iptc_keywords' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'iptc_keywords',
                                       new PHP_APE_Type_String( null, null, 250 ),
                                       PHP_APE_Data_Argument::Type_Data,
                                       $asResources_Image['name.keywords'],
                                       $asResources_Image['description.keywords']
                                       )

            );

    // End
    return $aoArguments;
  }

  /** Sets (defines) the given arguments (arguments)
   *
   * @param array|string $asArgumentsKeys Key(s) of arguments to be defined (all if <SAMP>null</SAMP>)
   */
  protected function _setArguments( $asArgumentsKeys = null )
  {
    // Retrieve arguments templates
    $aoArguments = $this->getArgumentsTemplates();

    // Sanitize input
    if( !is_null( $asArgumentsKeys ) )
      $asArgumentsKeys = PHP_APE_Type_Array::parseValue( $asArgumentsKeys );
    else
      $asArgumentsKeys = array_keys( $aoArguments );

    // Set
    $roArgumentSet =& $this->useArgumentSet();
    foreach( $asArgumentsKeys as $sKey )
    {
      if( !array_key_exists( $sKey, $aoArguments ) )
        throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid argument key; Key: '.$sKey );
      $roArgumentSet->setElement( $aoArguments[ $sKey ] );
    }

  }

}

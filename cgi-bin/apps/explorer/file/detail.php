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

/** File (detail) view
 *
 * @package PHP_APE_Explorer
 * @subpackage Views
 */
class PHP_APE_Explorer_File_detail
extends PHP_APE_Explorer_File_view
implements PHP_APE_Data_isDetailAbleResultSet, PHP_APE_Data_isListAble
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = 'FileDetail' )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_File_Resources' );

    // Parent constructor
    parent::__construct( $mID, $asResources['name.view.detail'], $asResources['description.view.detail'] );
  }


  /*
   * METHODS: PHP_APE_Data_isListAble - IMPLEMENT
   ********************************************************************************/

  public function getListView()
  {
    return new PHP_APE_Explorer_File_list();
  }

  public function isListAuthorized()
  {
    return true;
  }

}

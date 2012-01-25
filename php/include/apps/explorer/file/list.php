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

/** File (list) view
 *
 * @package PHP_APE_Explorer
 * @subpackage Views
 */
class PHP_APE_Explorer_File_list
extends PHP_APE_Explorer_File_view
implements PHP_APE_Data_isListAbleResultSet, PHP_APE_Data_isDetailAble
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = 'FileList' )
  {
    // Environment
    $roEnvironment =& PHP_APE_Explorer_WorkSpace::useEnvironment();
    $asResources =& $roEnvironment->loadProperties( 'PHP_APE_Explorer_File_Resources' );

    // Parent constructor
    parent::__construct( $mID, $asResources['name.view.list'], $asResources['description.view.list'] );
  }


  /*
   * METHODS: PHP_APE_Data_isDetailAble - IMPLEMENT
   ********************************************************************************/

  public function getDetailView()
  {
    return new PHP_APE_Explorer_File_detail();
  }

  public function isDetailAuthorized()
  {
    return true;
  }

}

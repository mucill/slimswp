<?php
/*
Plugin Name: SLiMS WP
Plugin URI: http://slims.web.id
Description: Display all collection from SLiMS applications.
Version: 1.0
Author: Eddy Subratha
Author URI: http://www.facebook.com/mucill

Copyright (C) 2012  Eddy Subratha  (email : eddy.subratha@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include 'lib/language.php';
function slims_page() {
      global $wp_query;
      $base_url = get_option('slimswp_base_url');
      if(substr($base_url,0,-1) != '' ) {
		$base_url .= '/';
	  }
      if(isset($_GET['id'])) {
            $source_url       = $base_url . "index.php?p=show_detail&inXML=true&id=".$_GET['id'];      
            $xml_read         = file_get_contents($source_url);
            $xml              = new SimpleXMLElement($xml_read);
            $author_name      = '';      
			$subject_name	  = '';
            $tpl = '<div class="entry-content collection-list">';
            foreach($xml->mods as $coll) {
                  $namespaces       = $coll->getNamespaces(true);
                  $slims            = $coll->children($namespaces['slims']);
				foreach($coll->name as $author) {
				  $author_name.= $author->namePart . '<br/>';
				}
				foreach($coll->subject as $subject) {
				  $subject_name.= $subject->topic . '<br/>';
				}
				if($slims->image != '') {
					  $img = '
					  <div class="cover-list"><img src="'.$base_url.'lib/phpthumb/phpThumb.php?src=../../images/docs/'.$slims->image.'&w=190" /></div>';
				} else {
					$img = '';
				}

						$tpl .= '
						<table>
							<tr>
								<td>'.TITLE.'</td>
								<td>'.$coll->titleInfo->title.' '. $coll->titleInfo->subTitle.'<br/>
								<a href="'.$base_url.'index.php?p=show_detail&id='.$coll->attributes()->ID.'" class="xmlDetailLink" title="View Detail in XML Format" target="_blank">View on OPAC</a>&nbsp;|&nbsp; 
								<a href="'.$base_url.'index.php?p=show_detail&inXML=true&id='.$coll->attributes()->ID.'" class="xmlDetailLink" title="View Detail in XML Format" target="_blank">XML Detail</a>
								</td>
							</tr>
							<tr>
								<td>'.COLLECTION_LOCATION.'</td>
								<td>'.$coll->location->physicalLocation.'</td>
							</tr>
							<tr>
								<td>'.EDITION.'</td>
								<td>'.$coll->originInfo->edition.'</td>
							</tr>
							<tr>
								<td>'.CALL_NUMBER.'</td>
								<td>'.$coll->call_number.'</td>
							</tr>
							<tr>
								<td>'.ISBN_ISSN.'</td>
								<td>'.$coll->identifier .'</td>
							</tr>
							<tr>
								<td>'.AUTHORS.'</td>
								<td>'.$author_name.'</td>
							</tr>
							<tr>
								<td>'.SUBJECTS.'</td>
								<td>'.$subject_name.'</td>
							</tr>
							<tr>
								<td>'.CLASSIFICATION.'</td>
								<td>'.$coll->classification.'</td>
							</tr>
							<tr>
								<td>'.SERIES_TITLE.'</td>
								<td>'.$coll->series.'</td>
							</tr>
							<tr>
								<td>'.GMD.'</td>
								<td>'.$coll->physicalDescription->form.'</td>
							</tr>
							<tr>
								<td>'.LANGUAGE.'</td>
								<td>'.$coll->language->languageTerm[1].'</td>
							</tr>
							<tr>
								<td>'.PUBLISHER.'</td>
								<td>'.$coll->originInfo->publisher.'</td>
							</tr>
							<tr>
								<td>'.PUBLISHING_YEAR.'</td>
								<td>'.$coll->originInfo->dateIssued.'</td>
							</tr>
							<tr>
								<td>'.PUBLISHING_PLACE.'</td>
								<td>'.$coll->originInfo->place->placeTerm.'</td>
							</tr>
							<tr>
								<td>'.COLLATION.'</td>
								<td>'.$coll->physicalDescription->extent.'</td>
							</tr>
							<tr>
								<td>'.ABSTRACT_NOTES.'</td>
								<td>'.$coll->note.'</td>
							</tr>
							<tr>
								<td>'.SPESIFIC_DETAIL_INFO.'</td>
								<td>'.$coll->call_number.'</td>
							</tr>
							<tr>
								<td>'.IMAGE.'</td>
								<td>'.$img.'</td>
							</tr>
						</table>';
            }     
            $tpl .= '</div>';
            echo $tpl;                  
      } else {
            if (isset($wp_query->query_vars['page']))
            {
                  $page = $wp_query->query_vars['page'];
                  $source_url       = $base_url . "index.php?resultXML=true&page=".$page;                        
            } else {
                  $source_url       = $base_url . "index.php?resultXML=true&search=Search";                                          
            }

            $xml_read         = file_get_contents($source_url);
            $xml              = new SimpleXMLElement($xml_read);
            $modsGet          = $xml->getNamespaces(true);
            $modsRead         = $xml->children($modsGet['slims']);
            $total            = $modsRead->resultInfo->modsResultNum;
            $per_page         = $modsRead->resultInfo->modsResultShowed;
            $author_name      = '';      
            $tpl = '<div class="entry-content collection-list">';
            foreach($xml->mods as $coll) {
                  $namespaces       = $coll->getNamespaces(true);
                  $slims            = $coll->children($namespaces['slims']);

                  $tpl .= '
                        <div class="item">';
                              if($slims->image != '') {
                                    $tpl .= '<div class="cover-list"><img src="'.$base_url.'lib/phpthumb/phpThumb.php?src=../../images/docs/'.$slims->image.'&w=90" /></div>';
                              }
                              $tpl .= '                              
                              <div class="detail-list">
                                    <h3><a href="?id='.$coll->attributes()->ID.'" class="titleField" title="Record Detail">'.$coll->titleInfo->title.' '. $coll->titleInfo->subTitle.'</a></h3>
                                    <div class="author"><b>Author(s)</b> : ';
                                    foreach($coll->name as $author) {
                                          $author_name.= $author->namePart . ',';
                                    }
                                    $tpl .= substr($author_name,0,-1);
                                    
                  $tpl .='          </div>
                                    <div class="subItem">
                                    <a href="?id='.$coll->attributes()->ID.'" class="detailLink" title="Record Detail">Record Detail</a>
                                    <a href="'.$base_url.'index.php?p=show_detail&inXML=true&id='.$coll->attributes()->ID.'" class="xmlDetailLink" title="View Detail in XML Format" target="_blank">XML Detail</a>
                                    </div>
                              </div>
                        </div>';
            }     
            $tpl .= '</div>';
            echo $tpl;            
      }
      
      $args = array(
                              'base'         => '%_%',
                              'format'       => $_SERVER["REQUEST_URI"].'?page=%#%',
                              'total'        => ($total!=0)? ceil($total/$per_page) : 0,
                              'current'      => 0,
                              'show_all'     => False,
                              'end_size'     => 5,
                              'mid_size'     => 2,
                              'prev_next'    => True,
                              'prev_text'    => __('&laquo; Previous'),
                              'next_text'    => __('Next &raquo;'),
                              'type'         => 'plain'
      );
      echo paginate_links( $args );

}


function register_slims_options() {
   add_menu_page('SLiMS Option Page', 'Senayan', 'manage_options', 'slimswp/options.php', '',   plugins_url('slimswp/images/icon.png'), 99);
}

add_shortcode( 'slims', 'slims_page' );
add_action('admin_menu', 'register_slims_options');
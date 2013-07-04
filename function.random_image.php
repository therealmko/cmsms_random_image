 <?php
#-------------------------------------------------------------------------
# Plugin: random_image
# Author: King
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2012 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The plugin's homepage is: http://dev.cmsmadesimple.org/projects/
#-------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#-------------------------------------------------------------------------

/**
 * Default parameter settings and return of result
 */
function smarty_cms_function_random_image($params, &$smarty) {

 	  #Set default root path. Might be needed to change if hosting provider requires so
          $root                   = '';
          
          #Get parameters from function call
          $image_path             = isset($params['image_path']) ? $params['image_path'] : '';
          $image_height		  = isset($params['image_height']) ? $params['image_height'] : '';
          $image_width		  = isset($params['image_width']) ? $params['image_width'] : '';
          $exclude		  = isset($params['exclude']) ? $params['exclude'] : '';

	  #Call function to fill array with all pictures
          $imgList                = getImagesFromDir($root . $image_path, $exclude);
          
          #Call function to randomly pick 1 image from array
          $img                    = getRandomFromArray($imgList);
         
          #Put together complete image location 
          $display_image	  = $image_path . $img;
         
          #Return html to display image 
          return <<<HTML
          <div class="display_image">
              <img src="{$display_image}" alt="DKHC Mug Shot" width="{$image_width}" height="{$image_height}" />
          </div>
HTML;
}

/**
 * Function to fill array with all pictures and remove unwanted names
 */
function getImagesFromDir($path, $exclude) {
    $images = array();
    if ( $img_dir = @opendir($path) ) {
        while ( false !== ($img_file = readdir($img_dir)) ) {
            // checks for gif, jpg, png
            if ( preg_match("/(\.gif|\.jpg|\.png)$/", $img_file) ) {
                $images[] = $img_file;
            }
        }
        closedir($img_dir);
    }
    $images = array_filter($images, function ($item) use ($exclude) { return strpos($item, "$exclude") !== 0; });
    return $images;
}

/**
 * Function to randomly pick 1 image from array
 */
function getRandomFromArray($ar) {
    mt_srand( (double)microtime() * 1000000 ); // php 4.2+ not needed
    $num = array_rand($ar);
    return $ar[$num];
}


/**
 * Help text
 */
function smarty_cms_help_function_random_image() {
         ?>
                  <h3>What does this do?</h3>
                  <p>This plugin displays a random image from a directory on a page where you use the {random_image} tag.</p>
                  <h3>Dependencies</h3>
                  <p>CMS Made Simple release 1.11<br />
                  At least that is what I am using, although it should work with older versions</p>
                  <h3>How do I use it?</h3>
                  <p>You could insert it into your template or page like this:<br />
                  <br />
                  <code>{random_image image_path="uploads/images/Gallery/somegallery/" exclude="thumb" image_height="400" image_width="300"}</code><br />
 
                  <h3>What parameters does it take?</h3>
                  <p><b>image_path=""</b> (Required)</p>
                  <p>Path to the directory containing the pictures you want to possibly show. This is relative to your cmsms install.</p>
                  <p><b>image_height=""</b> (Optional)</p>
                  <p><b>image_width=""</b> (Optional)</p>
                  <p><b>exclude=""</b> (Optional)</p>
                  <p>Give (a part of) a file name you do not want to include in the possible image being displayed.</p>
                  <br />
                  <p><b>Other things</b></p>
                  <p>The image returned is displayed in a div class="display_image". You can style this if needed in your stylesheet.</p>
         <?php
} // End Function
        
/**
 * About text
 */
function smarty_cms_about_function_random_image() {
        ?>
                  <p><b>Plugin author: King</b></p>
                  <p>>Big thanks to Rolf Tjassens (http://www.rolftjassens.com) for his many examples and howtos</p>
                  <p>Also thanks to the author of the example found here : http://www.dyn-web.com/code/basics/random_image/random_img_php.php</p>
                  
                  <p><b>Version:</b> 1.1</p>
                  <p><b>Change History:</b></p>
                  <p><b>04-07-2013 - King - Added functions and styling (v1.1)</b></p>
                  	  <ul>
                  	  	<li>Added functions for path, width, height and exclude</li>
                  	  	<li>Added additional scripting to exclude pre-defined image names. For me because the gallery module I use creates thumbnales, where the name starts with "thumb", which I want to avoid.</li>
                  	  	<li>Added div to contain image so styling is possible in stylesheet</li>
                  	  	<li>Updated help and about functions</li>
                  	  </ul>
                  <br>
                  <p><b>02-07-2013 - King - Initial release (v1.0)</b></p>
                          <ul>
                                <li>Initial release</li>
                          </ul>
        <?php
} // End Function
?>

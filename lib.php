<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    theme_ned_clean
 * @subpackage NED Clean
 * @copyright  2018 NED {@link http://ned.ca}
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @developer  G J Barnard - {@link http://about.me/gjbarnard} and
 *                           {@link http://moodle.org/user/profile.php?id=442195}
 */

/**
 * Parses CSS before it is cached.
 *
 * This function can make alterations and replace patterns within the CSS.
 *
 * @param string $css The CSS
 * @param theme_config $theme The theme config object.
 * @return string The parsed CSS The parsed CSS.
 */
function theme_ned_clean_process_css($css, $theme) {
    static $cleanparent = null;
    if ($cleanparent === null) {
        $cleanparent = theme_config::load('clean');
    }
    $css .= theme_clean_process_css($css, $cleanparent);

    $css .= theme_ned_clean_set_page_module_date($theme);

    return $css;
}

function theme_ned_clean_set_page_module_date($theme) {
    $css = '';

    if ((!empty($theme->settings->pagedateshowhide)) && ($theme->settings->pagedateshowhide == 1)) {
        $css .= '.path-mod-page #region-main > div > .modified {';
        $css .= 'display: none;';
        $css .= '}';
    }

    return $css;
}

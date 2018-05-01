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

defined('MOODLE_INTERNAL') || die;
$settings = null; // Unsets the default $settings object initialised by Moodle.

// Create own category and define pages.
$ADMIN->add('themes', new admin_category('theme_ned_clean', 'NED Clean'));

// Activities and resources settings.
$nedcleansettingsar = new admin_settingpage('theme_ned_clean_ar', get_string('activitiesandresources', 'theme_ned_clean'));
// Initialise individual settings only if admin pages require them.
if ($ADMIN->fulltree) {
    $name = 'theme_ned_clean/questionnaireactivitylink';
    $title = get_string('questionnaireactivitylink', 'theme_ned_clean');
    $description = '';
    $choices = array(
        1 => new lang_string('no'),
        2 => new lang_string('yes')
    );
    $default = 2;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $nedcleansettingsar->add($setting);

    $name = 'theme_ned_clean/urlresourcelink';
    $title = get_string('urlresourcelink', 'theme_ned_clean');
    $description = '';
    $choices = array(
        1 => new lang_string('no'),
        2 => new lang_string('yes')
    );
    $default = 2;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $nedcleansettingsar->add($setting);

    if ($CFG->branch >= 34) {
        $name = 'theme_ned_clean/jumptomenu';
        $title = get_string('jumptomenu', 'theme_ned_clean');
        $description = '';
        $choices = array(
            1 => new lang_string('hide'),
            2 => new lang_string('show')
        );
        $default = 1;
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $nedcleansettingsar->add($setting);

        $name = 'theme_ned_clean/forwardbacklinks';
        $title = get_string('forwardbacklinks', 'theme_ned_clean');
        $description = '';
        $choices = array(
            1 => new lang_string('hide'),
            2 => new lang_string('show')
        );
        $default = 1;
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $nedcleansettingsar->add($setting);
    }

    $name = 'theme_ned_clean/pagedateshowhide';
    $title = get_string('pagedateshowhide', 'theme_ned_clean');
    $description = '';
    $choices = array(
        1 => new lang_string('hide'),
        2 => new lang_string('show')
    );
    $default = 2;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $nedcleansettingsar->add($setting);
}
$ADMIN->add('theme_ned_clean', $nedcleansettingsar);

// Custom settings.
$nedcleansettingscustom = new admin_settingpage('theme_ned_clean_custom', get_string('custom', 'theme_ned_clean'));
// Initialise individual settings only if admin pages require them.
if ($ADMIN->fulltree) {
    // Note: Not a mistake to get title and description strings from Clean.

    // Invert Navbar to dark background.
    $name = 'theme_ned_clean/invert';
    $title = get_string('invert', 'theme_clean');
    $description = get_string('invertdesc', 'theme_clean');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $nedcleansettingscustom->add($setting);

    // Logo file setting.
    $name = 'theme_ned_clean/logo';
    $title = get_string('logo', 'theme_clean');
    $description = get_string('logodesc', 'theme_clean');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $nedcleansettingscustom->add($setting);

    // Small logo file setting.
    $name = 'theme_ned_clean/smalllogo';
    $title = get_string('smalllogo', 'theme_clean');
    $description = get_string('smalllogodesc', 'theme_clean');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'smalllogo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $nedcleansettingscustom->add($setting);

    // Show site name along with small logo.
    $name = 'theme_ned_clean/sitename';
    $title = get_string('sitename', 'theme_clean');
    $description = get_string('sitenamedesc', 'theme_clean');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $nedcleansettingscustom->add($setting);

    // Custom CSS.
    $name = 'theme_ned_clean/customcss';
    $title = get_string('customcss', 'theme_clean');
    $description = get_string('customcssdesc', 'theme_clean');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $nedcleansettingscustom->add($setting);

    // Footnote setting.
    $name = 'theme_ned_clean/footnote';
    $title = get_string('footnote', 'theme_clean');
    $description = get_string('footnotedesc', 'theme_clean');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $nedcleansettingscustom->add($setting);
}
$ADMIN->add('theme_ned_clean', $nedcleansettingscustom);

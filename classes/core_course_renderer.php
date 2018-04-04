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

class theme_ned_clean_core_course_renderer extends core_course_renderer {
    private static $themesettings = null;
    private $editingoff;

    public function __construct(moodle_page $page, $target) {
        parent::__construct($page, $target);
        if (empty(self::$themesettings)) {
            self::$themesettings = theme_config::load('ned_clean')->settings;
        }
        $this->editingoff = !$page->user_is_editing();
    }

    /**
     * Renders html to display a name with the link to the course module on a course page
     *
     * If module is unavailable for user but still needs to be displayed
     * in the list, just the name is returned without a link
     *
     * Note, that for course modules that never have separate pages (i.e. labels)
     * this function return an empty string
     *
     * @param cm_info $mod
     * @param array $displayoptions
     * @return string
     */
    public function course_section_cm_name_title(cm_info $mod, $displayoptions = array()) {
        $output = '';
        $url = $mod->url;
        if (!$mod->is_visible_on_course_page() || !$url) {
            // Nothing to be displayed to the user.
            return $output;
        }

        // Accessibility: for files get description via icon, this is very ugly hack!
        $instancename = $mod->get_formatted_name();
        $altname = $mod->modfullname;
        /* Avoid unnecessary duplication: if e.g. a forum name already
           includes the word forum (or Forum, etc) then it is unhelpful
           to include that in the accessible description that is added. */
        if (false !== strpos(core_text::strtolower($instancename),
                core_text::strtolower($altname))) {
            $altname = '';
        }
        // File type after name, for alphabetic lists (screen reader).
        if ($altname) {
            $altname = get_accesshide(' '.$altname);
        }

        list($linkclasses, $textclasses) = $this->course_section_cm_classes($mod);

        /* Get on-click attribute value if specified and decode the onclick - it
           has already been encoded for display. */
        $onclick = htmlspecialchars_decode($mod->onclick, ENT_QUOTES);

        // Start of NED Clean specific changes.
        if ($this->editingoff) {
            if (($mod->modname == 'url') && ((!empty(self::$themesettings->urlresourcelink)) && (self::$themesettings->urlresourcelink == 2))) {
                global $DB;

                $modurl = $DB->get_record('url', array('id' => $mod->instance), '*', MUST_EXIST);
                $url = $modurl->externalurl;
            }
            if (($mod->modname == 'questionnaire') && ((!empty(self::$themesettings->questionnaireactivitylink)) && (self::$themesettings->questionnaireactivitylink == 2))) {
                global $CFG, $DB, $USER;
                require_once($CFG->dirroot.'/mod/questionnaire/locallib.php');
                require_once($CFG->dirroot.'/mod/questionnaire/questionnaire.class.php');

                // TODO: Could this be more efficient?
                list($cm, $course, $questionnaire) = questionnaire_get_standard_page_items($mod->id, null);
                $questionnaire = new questionnaire(0, $questionnaire, $course, $cm);

                if (($questionnaire->user_can_take($USER->id)) && ($questionnaire->questions)) {
                    $newurl = new moodle_url('/mod/questionnaire/complete.php', array('id' => $questionnaire->cm->id));
                    if ($questionnaire->user_has_saved_response($USER->id)) {
                        $newurl->param('resume', 1);
                        $instancename .= ' - '.get_string('resumesurvey', 'questionnaire');
                    }
                    $url = $newurl;
                }
            }
        }
        // End of NED Clean specific changes.

        // Display link itself.
        $activitylink = html_writer::empty_tag('img', array('src' => $mod->get_icon_url(),
                'class' => 'iconlarge activityicon', 'alt' => ' ', 'role' => 'presentation')) .
                html_writer::tag('span', $instancename . $altname, array('class' => 'instancename'));
        if ($mod->uservisible) {
            $output .= html_writer::link($url, $activitylink, array('class' => $linkclasses, 'onclick' => $onclick));
        } else {
            /* We may be displaying this just in order to show information
               about visibility, without the actual link ($mod->is_visible_on_course_page()). */
            $output .= html_writer::tag('div', $activitylink, array('class' => $textclasses));
        }
        return $output;
    }
}
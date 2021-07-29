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
 * Simple HTML Block
 * @package    block_simplehtml
 * @author     Shubhendra R Doiphode <doiphode.sunny@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('simplehtml_form.php');
require_once('lib.php');

global $DB, $OUTPUT, $PAGE;
$site = get_site();

$courseid = required_param('courseid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$context = context_course::instance($courseid);
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_simplehtml'));
$PAGE->set_url($CFG->wwwroot . '/blocks/simplehtml/view.php', array('id' => $courseid));
// Check for all required variables.

require_capability('block/simplehtml:managepages', context_course::instance($courseid));

$blockid = required_param('blockid', PARAM_INT);

// Next look for optional variables.

$viewpage = optional_param('viewpage', false, PARAM_BOOL);


$settingsnode = $PAGE->settingsnav->add(get_string('simplehtmlsettings', 'block_simplehtml'));
$editurl = new moodle_url('/blocks/simplehtml/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('editpage', 'block_simplehtml'), $editurl);
$editnode->make_active();

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_simplehtml', $courseid);
}

$PAGE->set_url('/blocks/simplehtml/view.php', array('id' => $id, 'courseid' => $courseid));
if ($id) {
    $simplehtmlpage = $DB->get_record('block_simplehtml', array('id' => $id));
    $heading = $site->fullname . ' :: ' . $course->shortname . ' :: ' . $simplehtmlpage->pagetitle;
} else {
    $heading = $site->fullname . ' :: ' . $course->shortname;
}

$PAGE->set_heading($heading);

require_login($course);

$simplehtml = new simplehtml_form();

if ($simplehtml->is_cancelled()) {
    // Cancelled forms redirect to the course main page.

    $courseurl = new moodle_url('/course/view.php', array('id' => $id));
    redirect($courseurl);
} else if ($fromform = $simplehtml->get_data()) {

    $format = $fromform->displaytext['format'];
    $fromform->displaytext = $fromform->displaytext['text'];
    $fromform->format = $format;

    if ($fromform->id != 0) {
        if (!$DB->update_record('block_simplehtml', $fromform)) {
            print_error('updateerror', 'block_simplehtml');
        }
    } else {
        if (!$DB->insert_record('block_simplehtml', $fromform)) {
            print_error('inserterror', 'block_simplehtml');
        }
    }

    // We need to add code to appropriately act on and store the submitted data
    // but for now we will just redirect back to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($courseurl);
} else {
    // Form didn't validate or this is the first display.
    $site = get_site();
    $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/simplehtml/style.css'));
    echo $OUTPUT->header();

    $toform['blockid'] = $blockid;
    $toform['courseid'] = $courseid;
    $toform['id'] = $id;

    if ($id) {
        $simplehtmlpage = $DB->get_record('block_simplehtml', array('id' => $id));

        $toform['blockid'] = $blockid;
        $toform['courseid'] = $courseid;
        $toform['displaytext'] = array("text" => $simplehtmlpage->displaytext);
        $simplehtml->set_data($toform);

        if ($viewpage) {
            block_simplehtml_print_page($simplehtmlpage);
        } else {
            $simplehtml->set_data($simplehtmlpage);
            $simplehtml->display();
        }
    } else {
        $simplehtml->set_data($toform);
        $simplehtml->display();
    }

    echo $OUTPUT->footer();
}
?>
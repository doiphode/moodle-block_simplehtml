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
global $DB, $PAGE, $COURSE;
$courseid = required_param('courseid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);

require_capability('block/simplehtml:managepages', context_course::instance($courseid));
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_simplehtml', $courseid);
}

require_login($course);

if (!$simplehtmlpage = $DB->get_record('block_simplehtml', array('id' => $id))) {
    print_error('nopage', 'block_simplehtml', '', $id);
}

$site = get_site();
$PAGE->set_url('/blocks/simplehtml/view.php', array('id' => $id, 'courseid' => $courseid));
$heading = $site->fullname . ' :: ' . $course->shortname . ' :: ' . $simplehtmlpage->pagetitle;
$PAGE->set_heading($heading);
echo $OUTPUT->header();

if (!$confirm) {
    $optionsno = new moodle_url('/course/view.php', array('id' => $courseid));
    $optionsyes = new moodle_url('/blocks/simplehtml/delete.php', array('id' => $id, 'courseid' => $courseid, 'confirm' => 1, 'sesskey' => sesskey()));
    echo $OUTPUT->confirm(get_string('deletepage', 'block_simplehtml', $simplehtmlpage->pagetitle), $optionsyes, $optionsno);
} else {
    if (confirm_sesskey()) {
        if (!$DB->delete_records('block_simplehtml', array('id' => $id))) {
            print_error('deleteerror', 'block_simplehtml');
        }
    } else {
        print_error('sessionerror', 'block_simplehtml');
    }
    $url = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($url);
}
echo $OUTPUT->footer();
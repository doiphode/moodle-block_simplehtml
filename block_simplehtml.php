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

defined('MOODLE_INTERNAL') || die();

class block_simplehtml extends block_base {
    public function init() {
        $this->title = get_string('pluginname', __CLASS__);
        $this->version = 2015012700;
    }

    public function get_content() {
        global $DB, $COURSE, $USER, $PAGE;
        $this->content = new stdClass();
        $context = context_course::instance($COURSE->id);

        // Check to see if we are in editing mode and that we can manage pages.
        $canmanage = has_capability('block/simplehtml:managepages', $context) && $PAGE->user_is_editing($this->instance->id);
        $canview = has_capability('block/simplehtml:viewpages', $context);

        // The other code.
        if ($simplehtmlpages = $DB->get_records('block_simplehtml', array('blockid' => $this->instance->id))) {

            $this->content->text = html_writer::start_tag('ul');
            foreach ($simplehtmlpages as $simplehtmlpage) {

                if ($canmanage) {

                    $pageparam = array('blockid' => $this->instance->id,
                        'courseid' => $COURSE->id,
                        'id' => $simplehtmlpage->id);
                    $editurl = new moodle_url('/blocks/simplehtml/view.php', $pageparam);
                    $editpicurl = new moodle_url('/pix/t/edit.png');
                    $edit = html_writer::link($editurl, html_writer::tag('img', '', array('src' => $editpicurl, 'alt' => get_string('edit'))));

                    // Delete.
                    $deleteparam = array('id' => $simplehtmlpage->id, 'courseid' => $COURSE->id);
                    $deleteurl = new moodle_url('/blocks/simplehtml/delete.php', $deleteparam);
                    $deletepicurl = new moodle_url('/pix/t/delete.png');

                    $delete = html_writer::link($deleteurl, html_writer::tag('img', '', array('src' => $deletepicurl, 'alt' => get_string('delete'))));

                } else {
                    $edit = '';
                    $delete = '';
                }
                $pageurl = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $simplehtmlpage->id, 'viewpage' => true));
                $this->content->text .= html_writer::start_tag('li');

                if ($canview) {
                    $this->content->text .= html_writer::link($pageurl, $simplehtmlpage->pagetitle);
                } else {
                    $this->content->text .= html_writer::tag('div', $simplehtmlpage->pagetitle);
                }

                $this->content->text .= "  " . $edit;
                $this->content->text .= "  " . $delete;
                $this->content->text .= html_writer::end_tag('li');
            }
        }

        if (has_capability('block/simplehtml:managepages', $context)) {
            $url = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
            $this->content->footer = html_writer::link($url, get_string('addpage', 'block_simplehtml'));
        } else {
            $this->content->footer = '';
        }
    }

    public function instance_delete() {
        global $DB;
        $DB->delete_records('block_simplehtml', array('blockid' => $this->instance->id));
    }


}

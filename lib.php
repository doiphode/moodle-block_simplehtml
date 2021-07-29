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

function block_simplehtml_images() {
    return array(html_writer::tag('img', '', array('alt' => get_string('red', 'block_simplehtml'), 'src' => "pix/red.png")),
        html_writer::tag('img', '', array('alt' => get_string('blue', 'block_simplehtml'), 'src' => "pix/blue.png")),
        html_writer::tag('img', '', array('alt' => get_string('green', 'block_simplehtml'), 'src' => "pix/green.png")));
}

function block_simplehtml_print_page($simplehtml, $return = false) {
    global $OUTPUT, $COURSE;
    $display = $OUTPUT->heading($simplehtml->pagetitle);

    $display .= $OUTPUT->box_start();
    $display .= html_writer::start_tag('div', array('class' => 'simplehtml displaydate'));
    if ($simplehtml->displaydate) {
        $display .= userdate($simplehtml->displaydate);
    }
    $display .= html_writer::end_tag('div');

    $display .= clean_text($simplehtml->displaytext);

    // Close the box.
    $display .= $OUTPUT->box_end();

    if ($simplehtml->displaypicture) {
        $display .= $OUTPUT->box_start();
        $images = block_simplehtml_images();
        $display .= $images[$simplehtml->picture];
        $display .= html_writer::start_tag('p');
        $display .= clean_text($simplehtml->description);
        $display .= html_writer::end_tag('p');
        $display .= $OUTPUT->box_end();
    }

    if ($return) {
        return $display;
    } else {
        echo $display;
    }

}
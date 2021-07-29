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

require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot . '/blocks/simplehtml/lib.php');

class simplehtml_form extends moodleform {

    function definition() {

        $mform =& $this->_form;
        $mform->addElement('header', 'displayinfo', get_string('textfields', 'block_simplehtml'));

        // Add page title element.
        $mform->addElement('text', 'pagetitle', get_string('pagetitle', 'block_simplehtml'));
        $mform->setType('pagetitle', PARAM_RAW);
        $mform->addRule('pagetitle', null, 'required', null, 'client');

        // Add display text field.
        $mform->addElement('editor', 'displaytext', get_string('displayedhtml', 'block_simplehtml'));
        $mform->setType('displaytext', PARAM_RAW);
        $mform->addRule('displaytext', null, 'required', null, 'client');

        // Add filename selection.
        $mform->addElement('filepicker', 'filename', get_string('file'), null, array('accepted_types' => '*'));

        // Add picture fields grouping.
        $mform->addElement('header', 'picfield', get_string('picturefields', 'block_simplehtml'), null, false);

        // Add display picture yes / no option.
        $mform->addElement('selectyesno', 'displaypicture', get_string('displaypicture', 'block_simplehtml'));
        $mform->setDefault('displaypicture', 1);

        // Add image selector radio buttons.
        $images = block_simplehtml_images();
        $radioarray = array();
        for ($i = 0; $i < count($images); $i++) {
            $radioarray[] =& $mform->createElement('radio', 'picture', '', $images[$i], $i);
        }
        $mform->addGroup($radioarray, 'radioar', get_string('pictureselect', 'block_simplehtml'), array(' '), FALSE);

        // Add description field.
        $attributes = array('size' => '50', 'maxlength' => '100');
        $mform->addElement('text', 'description', get_string('picturedesc', 'block_simplehtml'), $attributes);
        $mform->setType('description', PARAM_TEXT);

        // Add optional grouping.
        $mform->addElement('header', 'optional', get_string('optional', 'form'), null, false);
        // Add date_time selector in optional area.
        $mform->addElement('date_time_selector', 'displaydate', get_string('displaydate', 'block_simplehtml'), array('optional' => true));
        $mform->setAdvanced('optional');

        $this->add_action_buttons();

        // Hidden elements.
        $mform->addElement('hidden', 'blockid');
        $mform->setType('blockid', PARAM_RAW);
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_RAW);
        $mform->addElement('hidden', 'id', '0');
        $mform->setType('id', PARAM_RAW);

    }
}
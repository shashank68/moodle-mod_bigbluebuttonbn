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
 * Renderer for recording section.
 *
 * @package   mod_bigbluebuttonbn
 * @copyright 2010 onwards, Blindside Networks Inc
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Laurent David  (laurent.david [at] call-learning [dt] fr)
 */

namespace mod_bigbluebuttonbn\output;

use mod_bigbluebuttonbn\local\bbb_constants;
use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

defined('MOODLE_INTERNAL') || die();

/**
 * Class recordings_session
 *
 * @copyright 2010 onwards, Blindside Networks Inc
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Laurent David  (laurent.david [at] call-learning [dt] fr)
 */
class recordings_session implements renderable, templatable {

    /**
     * @var $bbbsession
     */
    protected $bbbsession;
    /**
     * @var $type
     */
    protected $type;
    /**
     * @var mixed|null $enabledfeatures
     */
    protected $enabledfeatures;

    /**
     * recording_section constructor.
     *
     * @param array $bbbsession
     * @param string $type
     * @param array $enabledfeatures
     */
    public function __construct($bbbsession, $type, $enabledfeatures = null) {

        $this->bbbsession = $bbbsession;
        $this->type = $type;
        $this->enabledfeatures = $enabledfeatures;
    }

    /**
     * Export for template
     *
     * @param renderer_base $output
     * @return array|stdClass|void
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function export_for_template(renderer_base $output) {

        $bbbid = $this->bbbsession['bigbluebuttonbn']->id;
        $hasrecordings = $this->bbbsession['record'];
        $hasrecordings = $hasrecordings &&
            (in_array($this->type, [bbb_constants::BIGBLUEBUTTONBN_TYPE_ALL,
                bbb_constants::BIGBLUEBUTTONBN_TYPE_RECORDING_ONLY]));
        $inputbutton = false;
        if ($this->enabledfeatures['importrecordings'] || $this->bbbsession['importrecordings']) {
            $button = new \single_button(
                new moodle_url('/mod/bigbluebuttonbn/import_view.php',
                    ['originbn' => $bbbid]),
                get_string('view_recording_button_import', 'mod_bigbluebuttonbn'));
            $inputbutton = $button->export_for_template($output);
        }

        $searchbutton = [
            'value' => ''
        ];

        $context = (object)
        [
            'has_recordings' => $hasrecordings,
            'import_button' => $inputbutton,
            'search' => $searchbutton,
            'bbbid' => intval($bbbid)
        ];
        return $context;
    }
}

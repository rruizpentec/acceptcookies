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

defined('MOODLE_INTERNAL') || die;

global $PAGE;

$isajaxrequest = defined('AJAX_SCRIPT') && AJAX_SCRIPT;
$iscliscript = defined('CLI_SCRIPT') && CLI_SCRIPT;

if (!$isajaxrequest && !$iscliscript) {
    $localconfig = get_config('local_acceptcookies');
    $color = (isset($localconfig->color) ? $localconfig->color : '#000000');
    $background = (isset($localconfig->background) ? $localconfig->background : '#DEDEDE');
    $legaltext = (isset($localconfig->legaltext) ? $localconfig->legaltext : get_string('defaultlegaltext', 'local_acceptcookies'));
    $legaltexturl = (isset($localconfig->legaltexturl) ? $localconfig->legaltexturl : '');
    $customcss = (isset($localconfig->customcss) ? $localconfig->customcss : '');
    if ($customcss == '') {
        if ($color == '') {
            $color = '#000000';
        }
        if ($background == '') {
            $background = '#dedede';
        }
        $customcss = 'z-index: 9999; text-align: center; position: fixed; bottom: 0px; width: 90%; padding: 10px 5%;'.
                'background-color: '.$background.'; color: '.$color.';';
    } else {
        $customcss = str_replace('"', "'", $customcss);
    }

    if ($legaltext == '') {
        $legaltext = htmlentities(get_string('defaultlegaltext', 'local_acceptcookies'));
    }

    $moreinfo = get_string('moreInfo', 'local_acceptcookies');
    $ok = get_string('ok', 'local_acceptcookies');

    $iscookieset = isset($_COOKIE['acceptcookies_accepted_mark']);
    $isadminpage = $PAGE->pagelayout === "admin";
    if (!$iscookieset && !$isadminpage) {
        $PAGE->requires->js_call_amd('local_acceptcookies/acceptcookies', 'init');
        $content = html_writer::start_tag('div', array('id' => 'accept-cookies-block', 'style' => $customcss));
        $content .= html_writer::tag('div', $legaltext, array('id' => 'accept-cookies-block-text', 'style' => 'padding-top: 4px;'));
        $content .= html_writer::start_tag('div', array('id' => 'cookies-block-buttons'));
        if ($legaltexturl != "") {
            $content .= html_writer::tag('button', $moreinfo, array('onclick' => "window.open('".$legaltexturl."', '_blank');"));
        }
        $buttononclickeventcode = "(function() { require('local_acceptcookies/acceptcookies').hidecookiesblock() })();";
        $content .= html_writer::tag('button', $ok, array('onclick' => $buttononclickeventcode));
        $content .= html_writer::end_tag('div').html_writer::end_tag('div');
        echo $content;
    }
}
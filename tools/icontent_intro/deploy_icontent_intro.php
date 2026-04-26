<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * CLI tool to deploy a standardized iContent intro using template + variables.
 *
 * @package    mod_mootyper
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);
// phpcs:disable moodle.Files.MoodleInternal

/**
 * Write an error message to STDERR and exit.
 *
 * @param string $message Error description.
 * @param int    $code    Exit code (default 1).
 */
function fail(string $message, int $code = 1): void {
    fwrite(STDERR, "ERROR: {$message}\n");
    exit($code);
}

/**
 * Parse CLI --key=value arguments into an associative array.
 *
 * @param array $argv Command-line arguments from $argv.
 * @return array Parsed key/value pairs.
 */
function parse_args(array $argv): array {
    $args = [];
    foreach ($argv as $arg) {
        if (substr($arg, 0, 2) !== '--') {
            continue;
        }
        $parts = explode('=', substr($arg, 2), 2);
        $key = $parts[0];
        $value = $parts[1] ?? '1';
        $args[$key] = $value;
    }
    return $args;
}

/**
 * Load and decode a JSON file, calling fail() on any error.
 *
 * @param string $path Absolute path to the JSON file.
 * @return array Decoded JSON as a PHP array.
 */
function load_json_file(string $path): array {
    if (!is_file($path)) {
        fail("File not found: {$path}");
    }
    $raw = file_get_contents($path);
    if ($raw === false) {
        fail("Could not read file: {$path}");
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        fail("Invalid JSON in file: {$path}");
    }
    return $data;
}

/**
 * Replace {{TOKEN}} placeholders in a template string using a variable map.
 *
 * @param string $template Template string with {{TOKEN}} placeholders.
 * @param array  $vars     Map of token => value.
 * @param array  $missing  Accumulates any tokens not found in $vars.
 * @return string Rendered string with placeholders replaced.
 */
function render_template_string(string $template, array $vars, array &$missing): string {
    return preg_replace_callback('/{{([A-Z0-9_]+)}}/', function (array $m) use ($vars, &$missing) {
        $token = $m[1];
        if (!array_key_exists($token, $vars)) {
            $missing[$token] = true;
            return $m[0];
        }
        return (string)$vars[$token];
    }, $template);
}

$args = parse_args($argv);

$moodleroot = $args['moodleroot'] ?? '';
$icontentid = isset($args['icontentid']) ? (int)$args['icontentid'] : 0;
$cmid = isset($args['cmid']) ? (int)$args['cmid'] : 0;
$varsfile = $args['vars'] ?? '';
$templatefile = $args['template'] ?? (__DIR__ . '/intro_pages_template.json');
$dryrun = !empty($args['dry-run']) && $args['dry-run'] !== '0';

if ($moodleroot === '') {
    fail('Missing --moodleroot=/path/to/moodle');
}
if (!is_file($moodleroot . '/config.php')) {
    fail('Could not find config.php in --moodleroot path');
}
if ($icontentid <= 0) {
    fail('Missing or invalid --icontentid=<int>');
}
if ($cmid <= 0) {
    fail('Missing or invalid --cmid=<int>');
}
if ($varsfile === '') {
    fail('Missing --vars=/path/to/course_variables.json');
}

$template = load_json_file($templatefile);
$vars = load_json_file($varsfile);

if (empty($template['pages']) || !is_array($template['pages'])) {
    fail('Template must include a non-empty pages array');
}

$metadata = $template['metadata'] ?? [];
$pagecontentformat = isset($metadata['pagecontentformat']) ? (int)$metadata['pagecontentformat'] : 1;
$showtitle = isset($metadata['showtitle']) ? (int)$metadata['showtitle'] : 1;
$layout = isset($metadata['layout']) ? (int)$metadata['layout'] : 1;
$showbgimage = isset($metadata['showbgimage']) ? (int)$metadata['showbgimage'] : 1;
$transitioneffect = isset($metadata['transitioneffect']) ? (string)$metadata['transitioneffect'] : '0';

$renderedpages = [];
$missing = [];
foreach ($template['pages'] as $idx => $page) {
    $title = $page['title'] ?? '';
    $content = $page['content'] ?? '';
    if ($title === '' || $content === '') {
        fail('Each template page must include non-empty title and content');
    }
    $renderedtitle = render_template_string($title, $vars, $missing);
    $renderedcontent = render_template_string($content, $vars, $missing);
    $renderedpages[] = [
        'pagenum' => $idx + 1,
        'title' => $renderedtitle,
        'content' => $renderedcontent,
    ];
}

if (!empty($missing)) {
    $tokens = implode(', ', array_keys($missing));
    fail('Missing template variables: ' . $tokens);
}

if ($dryrun) {
    echo 'DRY_RUN_OK pages=' . count($renderedpages) . "\n";
    foreach ($renderedpages as $p) {
        echo $p['pagenum'] . '. ' . $p['title'] . "\n";
    }
    exit(0);
}

require_once($moodleroot . '/config.php');

global $DB;

$now = time();
$tx = $DB->start_delegated_transaction();

$DB->delete_records('icontent_pages', ['icontentid' => $icontentid]);

foreach ($renderedpages as $p) {
    $rec = new stdClass();
    $rec->icontentid = $icontentid;
    $rec->cmid = $cmid;
    $rec->coverpage = 0;
    $rec->title = $p['title'];
    $rec->showtitle = $showtitle;
    $rec->pageicontent = $p['content'];
    $rec->pageicontentformat = $pagecontentformat;
    $rec->showbgimage = $showbgimage;
    $rec->layout = $layout;
    $rec->transitioneffect = $transitioneffect;
    $rec->pagenum = $p['pagenum'];
    $rec->hidden = 0;
    $rec->maxnotesperpages = 10;
    $rec->attemptsallowed = 0;
    $rec->expandnotesarea = 0;
    $rec->expandquestionsarea = 0;
    $rec->timecreated = $now;
    $rec->timemodified = $now;
    $DB->insert_record('icontent_pages', $rec);
}

$icontent = $DB->get_record('icontent', ['id' => $icontentid], 'id,maxpages,timemodified', MUST_EXIST);
$icontent->maxpages = count($renderedpages);
$icontent->timemodified = $now;
$DB->update_record('icontent', $icontent);

$tx->allow_commit();

echo 'DEPLOYED_OK icontentid=' . $icontentid . ' cmid=' . $cmid . ' pages=' . count($renderedpages) . "\n";

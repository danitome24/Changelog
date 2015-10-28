<?php
/**
 * Guido: Open Course Guide and Syllabus for Universities
 * Copyright (C) 2015 SREd Servei de Recursos Educatius <http://www.sre.urv.cat/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
/**
 * @author Daniel Tomé <danieltomefer@gmail.com>
 * @copyright 2015 Servei de Recursos Educatius (http://www.sre.urv.cat)
 */
require_once('Commit.php');

const FILENAME = 'CHANGELOG-prev.md';
const HELP_PARAM = '--help';
const BRANCH = 'exp_10';
const LOG_GREP = 'gitlab';
const EXPORT_PARAM = 'export';

if (isset($argv[1]) && $argv[1] == HELP_PARAM) {
    echo "\e[33m #### CHANGELOG GENERATOR #### \e[39m \n";
    echo "\n Params: \n";
    echo "\n\e[92m<export>\e[39m Export changelog prev file into a definitive changelog.";
    echo "\n\e[92m<version>\e[39m Insert version like the following example: X.X.X ";
    echo "\n\e[92m<hash>\e[39m Insert hash to define the range of your new version. This will take from hash definet to HEAD. \n\n";
    exit(0);
}

if(isset($argv[1]) && $argv[1] == EXPORT_PARAM) {
    exec("cp CHANGELOG-prev.md CHANGELOG.md");
    echo "\e[92m Definitive changelog file generated... \e[39m \n";
    exit(0);
}

if (isset($argv[1]) && preg_match("/^(\d+\.)(\d+\.)(\*|\d+)$/", $argv[1])) {
    $version = $argv[1];
} else {
    echo "\e[91mThe version is empty or not correct. \n";
    exit(1);
}

$hashRange = (isset($argv[2]))
    ? $argv[2]
    : null;

$title = "###Version " . $version;

//Getting content existing in our file
if (file_exists(FILENAME)) {
    $fileContent = file_get_contents(FILENAME);
}

//Cleaning file and adding new version
file_put_contents(FILENAME, "\n" . $title . PHP_EOL, LOCK_EX);
file_put_contents(FILENAME, "==================" . PHP_EOL, LOCK_EX | FILE_APPEND);

$commitsLog = getCommits($hashRange);
//We got all commits on $commitsLog array
foreach ($commitsLog as $idCommit => $commits) {
    file_put_contents(FILENAME, '* #### Issue #' . $idCommit . PHP_EOL, LOCK_EX | FILE_APPEND);
    foreach ($commits as $commit) {
        file_put_contents(FILENAME, "\t" . "* " . $commit->getMessage() . PHP_EOL, LOCK_EX | FILE_APPEND);
    }
}

//Appending old content to file
if (isset($fileContent)) {
    file_put_contents(FILENAME, $fileContent, LOCK_EX | FILE_APPEND);
}


/**
 * Function to get all Commits from git log
 * @param $grepString
 * @return array
 */
function getCommits($hashRange)
{
    $gitLogCommand = (null == $hashRange)
        ? "git log " . BRANCH . " --grep=" . LOG_GREP
        : "git log " . BRANCH . " $hashRange..HEAD --grep=" . LOG_GREP;
    exec("cd ../guido/public; $gitLogCommand", $output);
    $history = array();
    foreach ($output as $line) {
        if (strpos($line, 'commit') === 0) {
            if (!empty($commit)) {
                $commitInstance = new Commit($commit['hash'], $commit['author'], $commit['date'], $commit['message']);
                $commitInstance->setMessage(filterMessages($commitInstance->getMessage()));
                $history[$commitInstance->getId()][] = $commitInstance;
                unset($commit);
            }
            $commit['hash'] = trim(substr($line, strlen('commit')));
        } else if (strpos($line, 'Author') === 0) {
            $commit['author'] = trim(substr($line, strlen('Author:')));
        } else if (strpos($line, 'Date') === 0) {
            $commit['date'] = trim(substr($line, strlen('Date:')));
        } else if (strpos($line, LOG_GREP)) {
            $commit['message'] = trim($line);
        }

    }
    if (!empty($commit)) {
        $commitInstance = new Commit($commit['hash'], $commit['author'], $commit['date'], $commit['message']);
        $commitInstance->setMessage(filterMessages($commitInstance->getMessage()));
        $history[$commitInstance->getId()][] = $commitInstance;
        unset($commit);
    }

    return $history;
}

function filterMessages($message)
{
 return preg_replace('/('.LOG_GREP.' #)([0-9])+( -)?/', ' ', $message);
}

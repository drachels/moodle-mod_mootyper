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
 * German strings for mootyper
 *
 * @package    mod_mootyper
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @copyright  2018 Thomas Ludwig, ISb Bayern (thomas.ludwig@isb.bayern.de)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['allstring'] = 'Alle';
$string['average'] = 'Durchschnitt';
$string['bymootyper'] = 'Übung';
$string['byuser'] = 'Benutzer';
$string['cancel'] = 'Abbrechen';
$string['charttitleallgrades'] = 'Alle Bewertungen';
$string['charttitlemyowngrades'] = 'Meine eigenen Bewertungen';
$string['chere'] = 'Klicken um zu starten';
$string['closebeforeopen'] = 'Mootyper konnte nicht gestartet werden, da der Aktivitätenabschluss vor dem Startzeitpunkt liegt. Bitte ändern Sie die entsprechenden Einstellungen entsprechend.';
$string['configdateformat'] = 'Festlegen des Datumsformates in den Bewertungsberichten. Der Standard ist Monat - Tag - Jahr - Uhrzeit (im 24 Stunden-Format) voreingestellt. Weitere Informationen und vordefinierte Datumskonstanten finden Sie im PHP-Handbuch unter Datumsformat.';
$string['configrequirepassword'] = 'Passwort festlegen.';
$string['configpassword_desc'] = 'Legt fest, ob ein Passwort zum Betreten der Aktvität benötigt wird.';
$string['configtimelimit_desc'] = 'Wenn ein Zeitlimit festgelegt ist, wird zu Beginn der Lektion eine Warnung angezeigt und es gibt einen Countdown-Timer. Möchten Sie kein Zeitlimit festlegen, setzen Sie diesen Wert auf null.';
$string['continue'] = 'Fortsetzen';
$string['continuoustype'] = 'Kontinuierliches Tippen';
$string['continuoustype_help'] = 'Eine Fehleingabe muss, wenn aktiviert, nicht verbessert werden.';
$string['countmistakes'] = 'Fehler kumulieren';
$string['countmistakes_help'] = 'Alle Fehlversuche eines bestimmten Fehlers werden, wenn aktiviert, kumuliert.';
$string['countmistypedspaces'] = 'Zählen falschgesetzter Leerzeichen';
$string['countmistypedspaces_help'] = 'Fehlerhafte Leerzeichen werden, wenn aktiviert, als Fehler gezählt.';
$string['course_exercises_viewed'] = 'Betrachtete Übungen';
$string['csvexport'] = 'Export im csv-Format';
$string['cursorcolor_title'] = 'Farbe des Cursors';
$string['cursorcolor_descr'] = 'Setzen der Farbe um den Cursor des nächsten zu tippenden Buchstabens.';
$string['cursorcolor_colour'] = '#00ff00';
$string['cursorcolor'] = 'Farbe des Cursors';
$string['cursorcolor_help'] = 'Setzen der Farbe um den Eingabe-Cursor. Eine Eingabe ist im Hex-Format oder als Standardfarbe möglich (z. B. #95fc89 der grün)..';
$string['dateformat'] = 'Standarddatumsformat';
$string['defaultlayout'] = 'Standardtastaturlayout';
$string['defaultprecision'] = 'Standand der TippGenauigkeit in %';
$string['defaulttextalign'] = 'Textausrichtung';
$string['defaulttextalign_help'] = 'Setzen der Textausrichtung während des Bearbeitens von Übungen. Mögliche Einstellungen sind: linksbündig, zentriert, rechtsbündig.';
$string['defaulteditalign'] = 'Textausrichtung bearbeiten';
$string['defaulteditalign_help'] = 'Setzen der Textausrichtung während des Editierens von Übungen. Mögliche Einstellungen sind: linksbündig, zentriert, rechtsbündig.';
$string['defaulttextalign_left'] = 'linksbündig';
$string['defaulttextalign_center'] = 'zentriert';
$string['defaulttextalign_right'] = 'rechtsbündig';
$string['defaulttextalign_warning'] = 'Hinweis: Legen Sie die Textausrichtung fest, bevor Sie mit dem Tippen starten können!';
$string['export'] = 'Exportiere ';
$string['exportconfirm'] = 'Export starten von ';
$string['eaccess0'] = 'alle Lehrkraft';
$string['eaccess1'] = 'alle im Kurs eingeschriebenen Lehrkräfte';
$string['eaccess2'] = 'den Ersteller';
$string['eaddnew'] = 'Hinzufügen einer neuen Übung / Kategorie';
$string['editable'] = 'Veränderbar durch';
$string['editexercises'] = 'Exportieren / Editieren von Übungen';
$string['eeditlabel'] = 'Verändern';
$string['eheading'] = 'Verwalten der MooTyper-Übungen';
$string['emanage'] = 'Zum Verwalten der Übungen und Kategorien klicken Sie bitte hier';
$string['emptypassword'] = 'Das Passwort darf nicht leer sein';
$string['ename'] = 'Übung: ';
$string['endlesson'] = 'Kategorien- / Lektionsende';
$string['enterpassword'] = 'Bitte geben Sie ihr Passwort ein:';
$string['eremove'] = 'Entfernen';
$string['etext'] = 'Text';
$string['etitle'] = 'MooTyper Übung';
$string['examdone'] = 'Prüfung wurde bereits abgenommen.';
$string['excategory'] = 'Kategorie der Übung';
$string['excategory_help'] = 'Wählen Sie einen Lektionsnamen für diese MooTyper-Aktivität.';
$string['exercise'] = 'Übung {$a} von ';
$string['exercise_added'] = 'Hinzugefügte Übungen / Kategorien';
$string['exercise_edited'] = 'Veränderte Übungen';
$string['exercise_removed'] = 'Entfernte Übungen';
$string['eventlessonexport'] = 'Exportierte Lektionen';
$string['failbgc_title'] = 'Hintergrundfarbe für fehlerhafte Bewertungen';
$string['failbgc_descr'] = 'Festlegen der Hintergrundfarbe für fehlerhafte Bewertungen.';
$string['failbgc_colour'] = '#FF6C6C';
$string['fapply'] = 'Apply';
$string['fconfirm'] = 'Bestätigen';
$string['fcontinue'] = 'Fortsetzen';
$string['fexercise'] = 'Übung';
$string['flesson'] = 'Lektion';
$string['fmode'] = 'Modus';
$string['fmode_help'] = 'Im Lektions-Modus werden alle Übungen / Kategorien bis zum Abschluss der Lektion angezeigt. Im Prüfungsmodus wird nur die ausgewählte Übung für die Prüfung angezeigt. Der Modus ist nach dem Speichern nicht mehr veränderbar.';
$string['fnewexercise'] = 'Übung wird ein Bestandteil sein von';
$string['fnewlesson'] = 'einer neuer Lektion / Kategorie';
$string['fsecurity'] = 'Sicherheit';
$string['fsettings'] = 'Einstellungen';
$string['fsetup'] = 'Setup';
$string['fullhits'] = 'Gesamtanschläge';
$string['gradesfilename'] = 'grades.csv';
$string['grade_removed'] = 'Entfernen einer Bewertung';
$string['gviewmode'] = 'Ansicht ';
$string['hitsperminute'] = 'Anschläge / Minute';
$string['invalidaccess'] = 'Sie besitzen nicht die Berechtigung diese Seite zu betrachten.';
$string['isexamtext'] = 'Prüfung';
$string['kblimportadd'] = ' Tastatuslayout wurde erfolgreich in die Datenbank aufgenommen.';
$string['kblimportnotadd'] = ' Tastaturlayout befindet sich bereits in der Datenbank.';
$string['keyboardbgc_title'] = 'Tastaturhintergrundfarbe';
$string['keyboardbgc_descr'] = 'Setzen der Tasturhintergrundfarbe.';
$string['keyboardbgc_colour'] = '#DDDDDD';
$string['keybdbgc'] = 'Tastaturhintergrundfarbe';
$string['keybdbgc_help'] = 'Festelgen der Tastenfarbe mit Ausnahme der Tasten der Grundposition. Eine Eingabe ist im Hex-Format oder als Standardfarbe möglich (z. B. #95fc89 der grün).';
$string['keytopbgc'] = 'Farbe der Tasten';
$string['keytopbgc_help'] = 'Festelgen der Tastenfarbe mit Ausnahme der Tasten der Grundposition. Eine Eingabe ist im Hex-Format oder als Standardfarbe möglich (z. B. #95fc89 der grün).';
$string['layout'] = 'Tastaturlayout';
$string['layout_help'] = 'Auswahl des angezeigten Tastaturlayouts. Hinweis: Eine Tastur wird nur dann eingeblendet, wenn die entsprechende Option aktiviert wurde.';
$string['layout_imported'] = 'Tastaturlayout wurde importiert';
$string['lesson_export'] = 'Einstellungen für den Export von Lektionen';
$string['lesson_export_filename'] = 'Dateiname des Exports';
$string['lesson_export_filenameconfig'] = 'Fügen Sie einen GMT-Zeitstempel als Teil des exportierten Lektionsdateinamen für eine einfachere Versionsverwaltung hinzu.';
$string['lesson_exported'] = 'Exportierte Lektionen / Kategorien';
$string['lesson_imported'] = 'Importierte  Lektionen / Kategorien';
$string['lesson_removed'] = 'Eine Lektion und ihre Übungen wurden entfernt';
$string['loginfail'] = 'Der Login ist fehlgeschlagen. Bitte versuchen Sie es erneut.';
$string['lsnname'] = 'Kategoriename';
$string['lsnimport'] = 'Importieren einer Lektion / eines Tastaturlayouts';
$string['lsnimportadd'] = ' wurde erfolgreich der Datenbank hinzugefügt.';
$string['lsnimportnotadd'] = ' befindet sich bereits in der Datenbank. Keine Änderung nötig.';
$string['modulename'] = 'MooTyper';
$string['modulename_help'] = 'Die Mootyper-Aktivität ermöglicht das Erlernen des Tastschreibens';
$string['modulenameplural'] = 'MooTypers';
$string['mootyper:addinstance'] = 'Add instance';
$string['mootyper:aftersetup'] = 'Datenbankimport';
$string['mootyperclosed'] = 'Diese MooTyper-Aktivität ist verfügbar bis {$a}.';
$string['mootyper:editall'] = 'Alle editieren';
$string['mootyperopen'] = 'Diese Mootyper-Aktivität steht ab {$a} zur Verfügung.';
$string['mootyper:setup'] = 'Setup';
$string['mootyper:view'] = 'Ansicht';
$string['mootyper:viewgrades'] = 'Einsehen aller Bewertungen';
$string['mootyper:viewmygrades'] = 'Meine Bewertungen';
$string['mootyper'] = 'Mootyper';
$string['mootyperclosetime'] = 'Abgabeende';
$string['mootyperfieldset'] = 'Benutzerdefiniertes Beispielfeld';
$string['mootypername_help'] = 'Because of the variety of lessons and exercises you can have, the name should make it clear which MooTyper lesson or exam this acitivity is set for. Markdown syntax is supported.';
$string['mootypername'] = 'Titel des Tests';
$string['mootyperopentime'] = 'Abgabebeginn';
$string['nogrades'] = 'Bisher liegen keine Bewertungen vor';
$string['normalkeytops_title'] = 'Farbe der Tasten';
$string['normalkeytops_descr'] = 'Setzen der Tastenfarbe, mit Ausnahme der Tasten der Grundposition.';
$string['normalkeytops_colour'] = '#CCCCCC';
$string['notavailable'] = '<b>Derzeit nicht verfügbar!<br></b>';
$string['noteditablebyme'] = 'Nicht durch Sie veränderbar.';
$string['notreadyyet'] = 'Noch nicht verfügbar... bitte versuchen Sie es später erneut.';
$string['options'] = 'Optionen';
$string['overview'] = 'Überblick';
$string['overview_help'] = 'MooTyper ist eine Moodle-Aktivität zum Erlernen des Tastschreibens. Einige vorgefertigte Lektionen mit mehreren Übungen sind enthalten. Lehrer, Manager und Administratoren können weitere Übungen hinzufügen und Bestehende verändern. Lektionen und Übungen können zur Sicherung oder zum Tausch mit anderen Moodle-Benutzern heruntergeladen werden. ';
$string['passbgc_title'] = 'Farbe der Bewertung';
$string['passbgc_descr'] = 'Festlegen der Hintergrundfarbe einer Bewertung.';
$string['passbgc_colour'] = '#7FEF6C';
$string['password'] = 'Password';
$string['passwordprotectedlesson'] = '{$a} ist eine passwortgeschützte MooTyper-Aktivität.';
$string['pluginadministration'] = 'MooTyper Administration';
$string['pluginname'] = 'MooTyper';
$string['practice'] = 'Übung';
$string['precision'] = 'Genauigkeit';

$string['privacy:metadata:mootyper_attempts'] = 'Speichert die Ergebnisse eines abgeschlossenen Trainingsversuchs.';
$string['privacy:metadata:mootyper_attempts:mootyperid'] = 'ID der MooTyper-Aktivität für diesen Versuch.';
$string['privacy:metadata:mootyper_attempts:userid'] = 'Userid der Person, die diesen Versuch unternimmt.';
$string['privacy:metadata:mootyper_attempts:timetaken'] = 'Wann wurde der Versuch gestartet?';
$string['privacy:metadata:mootyper_attempts:inprogress'] = 'Status: abgeschlossen = 0 oder laufend = 1. ';
$string['privacy:metadata:mootyper_attempts:suspicion'] = 'Kennzeichne wenn zu viel Zeit oder zu viele Fehler.';

$string['privacy:metadata:mootyper_grades'] = 'Speichert die Ergebnisse eines abgeschlossenen Trainingsversuchs.';
$string['privacy:metadata:mootyper_grades:mootyper'] = 'ID der MooTyper-Aktivität für diesen Versuch.';
$string['privacy:metadata:mootyper_grades:userid'] = 'Userid der Person, die diesen Versuch unternimmt.';
$string['privacy:metadata:mootyper_grades:grade'] = 'Bewertung für diesen Versuch.';
$string['privacy:metadata:mootyper_grades:mistakes'] = 'Anzahl der Fehler, die für diesen Versuch gezählt wurden.';
$string['privacy:metadata:mootyper_grades:timeinseconds'] = 'Für diesen Versuch benötigte Zeit in Sekunden.';
$string['privacy:metadata:mootyper_grades:hitsperminute'] = 'Anschläge pro Minute.';
$string['privacy:metadata:mootyper_grades:fullhits'] = 'Anzahl der Tastenanschläge innerhalb dieses Versuchs';
$string['privacy:metadata:mootyper_grades:precisionfield'] = 'Tippgenauigkeit.';
$string['privacy:metadata:mootyper_grades:timetaken'] = 'Wann wurde dieser Versuch abgeschlossen?';
$string['privacy:metadata:mootyper_grades:exercise'] = 'Die Übung, die für diesen Versuch verwendet wurde';
$string['privacy:metadata:mootyper_grades:pass'] = 'War der Versuch erfolgreich?';
$string['privacy:metadata:mootyper_grades:attemptid'] = 'ID dieses Versuchs';
$string['privacy:metadata:mootyper_grades:wpm'] = 'Quote der Anzahl der Worte pro Minute für diesen Versuch.';
$string['privacy:metadata:mootyper_lessons'] = 'Speichert Lektion für MooTyper.';
$string['privacy:metadata:mootyper_lessons:lessonname'] = 'Lektions- oder Katergiename.';
$string['privacy:metadata:mootyper_lessons:authorid'] = 'Userid, die diese Lektion hinzugefügt hat.';
$string['privacy:metadata:mootyper_lessons:visible'] = 'Sichtbarkeit: Ersteller, im Kurs eingeschrieben Lehrkräfte , allen Lehrkräften.';
$string['privacy:metadata:mootyper_lessons:editable'] = ' Veränderbar durch: Ersteller, im Kurs eingeschrieben Lehrkräfte , allen Lehrkräften.';
$string['privacy:metadata:mootyper_lessons:courseid'] = 'Courseid unter der diese Lektion erstellt wurde.';
$string['privacy:metadata:mootyper_exercises'] = 'Speichert Übungen für jede Lektion.';
$string['privacy:metadata:mootyper_exercises:texttotype'] = 'Übungstext.';
$string['privacy:metadata:mootyper_exercises:exercisename'] = 'Name der Übung.';
$string['privacy:metadata:mootyper_exercises:lesson'] = 'Die zugehörige Lektion.';
$string['privacy:metadata:mootyper_exercises:snumber'] = 'Sequenznummer der Lektion';
$string['removeall'] = 'Vollständig entfernen ';
$string['removekb'] = 'Entfernen des Tastaturlayouts';
$string['removelsnconfirm'] = 'Bestätigung zum vollständigen Entfernen von ';
$string['removeexconfirm'] = 'Bestätigung zum Entfernen von ';
$string['reqfield'] = 'Benötigte Einstellung';
$string['requiredgoal'] = 'Erforderliche Genauigkeit';
$string['requiredgoal_help'] = 'Die für den erfolgreichen Abschluss einer Übung erforderliche Genauigkeit.';
$string['requirepassword'] = 'Passwort wird benötigt';
$string['resetmootyperall'] = 'Zurücksetzen aller Versuche und Bewertugnen';
$string['returnto'] = 'Zurück zu {$a}';
$string['rhitspermin'] = 'Anschläge / min';
$string['rmistakes'] = 'Fehler';
$string['rprecision'] = 'Genauigkeit';
$string['rprogress'] = 'Fortschritt';
$string['rtime'] = 'Zeit';
$string['sflesson'] = 'Lektion';
$string['showkeyboard'] = 'Anzeigen der Tastatur';
$string['showkeyboard_help'] = 'Wenn diese Einstellung aktiviert ist, wird während der Übung die eingestellte Tastatur eingeblendet.';
$string['showrecentactivity'] = 'Letzte Aktivität anzeigen';
$string['showrecentactivityconfig'] = 'Jeder kann Benachrichtigungen in den letzten Aktivitätsberichten sehen.';
$string['statsbgc'] = 'Hintergrundfarbe der Statistiken';
$string['statsbgc_help'] = 'Setzen der Hintergrundfarbe der Statistiken. Eine Eingabe ist im Hex-Format oder als Standardfarbe möglich (z. B. #95fc89 der grün).';
$string['statscolor_title'] = 'Hintergrundfarbe der Statistikleiste';
$string['statscolor_descr'] = 'Festlegen der Hintergrundfarbe der Statistikleiste.';
$string['statscolor_colour'] = '#CCCCCC';
$string['student'] = 'SchülerIn';
$string['suspicion_title'] = 'Warnfarbe für verdächtige Bewertungen';
$string['suspicion_descr'] = 'Setzen der Farbe zum Hervorheben verdächtiger Bewertungen.';
$string['suspicion_colour'] = '#FFFF00';
$string['testing'] = 'Testing code';
$string['textbgc_title'] = 'Texthintergrundfarbe';
$string['textbgc_descr'] = 'Festlegen der Hintergrundfarbe des Übungstextes.';
$string['textbgc_colour'] = '#dddddd';
$string['textbgc'] = 'Texthintergrundfarbe';
$string['textbgc_help'] = 'Setzen der Texthintergrundfarbe. Eine Eingabe ist im Hex-Format oder als Standardfarbe möglich (z. B. #95fc89 der grün).';
$string['texterrorcolor_title'] = 'Farbe eines Eingabefehlers';
$string['texterrorcolor_descr'] = 'Setzen der Hintergrundfarbe einer Falscheingabe.';
$string['texterrorcolor_colour'] = '#ff9999';
$string['texterrorcolor'] = 'Farbliche Hervorhebung eines Eingabefehlers';
$string['texterrorcolor_help'] = 'Setzen der Hintergrundfarbe einer Falscheingabe. Eine Eingabe ist im Hex-Format oder als Standardfarbe möglich (z. B. #95fc89 der grün).';
$string['timeisup'] = 'Zeit ist abgelaufen';
$string['timelimit'] = 'Zeitlimit';
$string['timelimit_help'] = 'Eine Anzeige des Zeitlimits wird, wenn aktiviert, zu Beginn einer Lektion eingeblendet. Eingabe nach verstreichen des Zeitlimits werden nicht mehr gewertet.';
$string['timeinseconds'] = 'Verstrichene Zeit';
$string['timetaken'] = 'Abschlossen';
$string['usepassword'] = 'Passwordgeschützte Lektion';
$string['usepassword_help'] = 'Ein passwort wird, wenn aktiviert, benötigt.';
$string['vaccess0'] = 'alle Lehrkräfte';
$string['vaccess1'] = 'alle im Kurs eingeschriebenen Lehrkräfte';
$string['vaccess2'] = 'den Ersteller';
$string['viewgrades'] = 'Anzeigen aller Bewertungen';
$string['viewmygrades'] = 'Meine eigenen Bewertungen';
$string['visibility'] = 'Kategorie sichtbar für';
$string['vmistakes'] = 'Fehler';
$string['wpm'] = 'Worte / min';
$string['xaxislabel'] = 'Range';

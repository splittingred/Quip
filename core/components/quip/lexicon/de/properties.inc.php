<?php
/**
 * Quip
 *
 * Copyright 2010-11 by Shaun McCormick <shaun@modx.com>
 *
 * This file is part of Quip, a simple commenting component for MODx Revolution.
 *
 * Quip is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Quip is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Quip; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package quip
 */
/**
 * Quip properties German language file
 * (Not yet) Translated by Dennis "DenSchub" Schubert <software@dsx.cc>
 * Instead translated by Jan-Christoph Ihrens (ihrens@cc-services.de)  ;-)
 *
 * @package quip
 * @subpackage lexicon
 */
/* options */
$_lang['quip.all'] = 'Alle';
$_lang['quip.ascending'] = 'Aufsteigend';
$_lang['quip.author'] = 'Autor';
$_lang['quip.comment'] = 'Kommentar';
$_lang['quip.descending'] = 'Absteigend';
$_lang['quip.family'] = 'Familie';
$_lang['quip.opt_blackglass'] = 'Black Glass';
$_lang['quip.opt_clean'] = 'Clean';
$_lang['quip.opt_red'] = 'Red';
$_lang['quip.opt_white'] = 'White';
$_lang['quip.thread'] = 'Thread';
$_lang['quip.user'] = 'Benutzer';

/* Quip */
$_lang['quip.prop_allowremove_desc'] = 'Erlaubt eingeloggten Benutzern, ihre eigenen Beiträge zu löschen.';
$_lang['quip.prop_allowreportasspam_desc'] = 'Erlaubt eingeloggten Benutzern, Kommentare als Spam zu melden.';
$_lang['quip.prop_altrowcss_desc'] = 'Die CSS-Klasse, mit der jeder zweite Kommentar ausgezeichnet wird.';
$_lang['quip.prop_anonymousname_desc'] = 'Der Name, der bei anonymen Beiträgen angezeigt wird. Standardmäßig ist dies "Anonymous".';
$_lang['quip.prop_authortpl_desc'] = 'Der Name eines Chunks, der im Kommentar für die Darstellung des Namens des Autors verwendet wird.';
$_lang['quip.prop_closeafter_desc'] = 'Gibt die Anzahl von Tagen an, nach denen der Thread automatisch geschlossen wird (es werden dann keine neuen Kommentare mehr akzeptiert). Setzen Sie diese Einstellung auf 0, um den Thread dauerhaft geöffnet zu lassen.';
$_lang['quip.prop_closed_desc'] = 'Wenn diese Einstellung auf "Ja" steht, werden im Thread keine neuen Kommentare akzeptiert.';
$_lang['quip.prop_currentpagecls_desc'] = 'Eine CSS-Klasse, die für die aktuelle Seitennummer verwendet wird.';
$_lang['quip.prop_dateformat_desc'] = 'Das Format von Datumsangaben, die für einen Kommentar angezeigt werden.';
$_lang['quip.prop_debug_desc'] = 'Setzen Sie diese Einstellung auf "Ja", um den Debug-Modus einzuschalten. Für produktive Websites nicht empfohlen.';
$_lang['quip.prop_debuguser_desc'] = 'Wenn der Debug-Modus eingeschaltet ist, wird der Benutzername von $modx->user auf diesen Wert gesetzt.';
$_lang['quip.prop_debuguserid_desc'] = 'Wenn der Debug-Modus eingeschaltet ist, wird die ID von $modx->user auf diesen Wert gesetzt.';
$_lang['quip.prop_gravataricon_desc'] = 'Das Standard-Gravatar-Icon, das geladen wird, wenn für einen Benutzer kein anderes gefunden wurde.';
$_lang['quip.prop_gravatarsize_desc'] = 'Die Größe des Gravatars in Pixeln.';
$_lang['quip.prop_idprefix_desc'] = 'Wenn Sie mehrere Quip-Instanzen auf einer Seite verwenden möchten, ändern Sie diesen ID-Präfix.';
$_lang['quip.prop_limit_desc'] = 'Die maximale Anzahl von Kommentaren pro Seite. Wird diese Einstellung auf eine Zahl gesetzt, die größer als 0 ist, wird die Paginierung (Verteilung auf mehrere Seiten mit Verlinkung der anderen Seiten) aktiviert.';
$_lang['quip.prop_namefield_desc'] = 'Das Feld, das für den Namen des Autors eines Kommentars verwendet wird. Empfohlene Werte sind "name" oder "username".';
$_lang['quip.prop_maxdepth_desc'] = 'Die maximale Tiefe, bis zu der Antworten in einem Kommentar-Thread geschrieben werden können, der Unter-Threads erlaubt.';
$_lang['quip.prop_olcss_desc'] = 'Die CSS-Klasse, die in den ol-Tags verwendet wird, die die Kommentare einer Seite umfassen.';
$_lang['quip.prop_pagecls_desc'] = 'Eine CSS-Klasse, die in Seitennummern-Links eingefügt wird, die nicht der aktuell aufgerufenen Seitennummer entsprechen.';
$_lang['quip.prop_paginationcls_desc'] = 'Eine CSS-Klasse die in den ol-Tag eingefügt wird, der die Seitennummern-Links umgibt.';
$_lang['quip.prop_parent_desc'] = 'Die ID des Elternelements, bei dem die Anzeige des Threads gestartet wird. Standardmäßig ist dies die 0.';
$_lang['quip.prop_placeholderprefix_desc'] = 'Der Präfix für die globalen Platzhalter, die von Quip zur Verfügung gestellt werden.';
$_lang['quip.prop_requireauth_desc'] = 'Wenn diese Einstellung auf "Ja" steht, können nur eingeloggte Benutzer Kommentare verfassen.';
$_lang['quip.prop_requireusergroups_desc'] = 'Optional. Eine kommaseparierte Liste von Benutzergruppen, auf die die Berechtigung zu kommentieren begrenzt wird.';
$_lang['quip.prop_removeaction_desc'] = 'Der Name des Submit-Buttons, mittels dessen die Löschung eines Kommentars ausgelöst wird.';
$_lang['quip.prop_removethreshold_desc'] = 'Wenn allowRemove auf "Ja" steht, gibt diese Einstellung die Anzahl der Minuten an, während derer ein Benutzer seinen Beitrag löschen kann, nachdem er ihn abgesendet hat. Die Standardeinstellung ist 3 Minuten.';
$_lang['quip.prop_replyresourceid_desc'] = 'Die ID der Ressource, die das QuipReply-Snippet enthält, mittels dessen auf Kommentare in einem Unter-Thread geantwortet werden kann.';
$_lang['quip.prop_reportaction_desc'] = 'Der Name des Submit-Buttons, mittels dessen die Meldung eines Kommentars als Spam ausgelöst wird.';
$_lang['quip.prop_rowcss_desc'] = 'Die CSS-Klasse, die in den äußeren div-Container jedes Kommentars eingefügt wird.';
$_lang['quip.prop_showanonymousname_desc'] = 'Wenn diese Einstellung auf "Ja" steht, wird der Wert der Einstellung anonymousName (standardmäßig "Anonymous") angezeigt, wenn der Benutzer beim Verfassen eines Beitrags nicht eingeloggt ist.';
$_lang['quip.prop_start_desc'] = 'Der Standard-Kommentar-Index, mit dem gestartet wird. Es wird empfohlen, diese Einstellung auf dem Wert 0 zu belassen.';
$_lang['quip.prop_sortby_desc'] = 'Das Feld, nach dem sortiert wird.';
$_lang['quip.prop_sortbyalias_desc'] = 'Der Alias der Klasse, die bei der Sortierung verwendet wird ("quipComment" oder "Author").';
$_lang['quip.prop_sortdir_desc'] = 'Die Sortierrichtung.';
$_lang['quip.prop_thread_desc'] = 'Der individuelle Name des Threads.';
$_lang['quip.prop_threaded_desc'] = 'Gibt an, ob dieser Thread Unter-Threads haben kann oder nicht.';
$_lang['quip.prop_threadedpostmargin_desc'] = 'Der Abstand in Pixeln, um den Kommentare eines Unter-Threads pro Ebene nach rechts eingerückt werden.';
$_lang['quip.prop_toplaceholder_desc'] = 'Wenn diese Einstellung nicht leer gelassen wird, wird der Inhalt in dem Platzhalter zur Verfügung gestellt, dessen Name hier eingegeben wird, und nicht direkt ausgegeben.';
$_lang['quip.prop_tplcomment_desc'] = 'Ein Chunk für den Kommentar selbst.';
$_lang['quip.prop_tplcommentoptions_desc'] = 'Ein Chunk für die Optionen, wie z. B. "Löschen", die dem Autor eines Kommentars angezeigt werden.';
$_lang['quip.prop_tplcomments_desc'] = 'Ein Chunk für den äußeren Container für Kommentare.';
$_lang['quip.prop_tplpagination_desc'] = 'Ein Chunk, der den ol-Tag enthält, der die Seitennummern-Links umgibt.';
$_lang['quip.prop_tplpaginationitem_desc'] = 'Ein Chunk für jeden Seitennummern-Link, der nicht der aktuell angezeigten Seitennummer entspricht.';
$_lang['quip.prop_tplpaginationcurrentitem_desc'] = 'Ein Chunk für den Seitennummern-Link der aktuell angezeigten Seite.';
$_lang['quip.prop_tplreport_desc'] = 'Der Link, mittels dessen ein Kommentar als Spam gemeldet werden kann. Wird hier der Name eines Chunks eingegeben, wird dieser Chunk für die Darstellung des Links (bzw. Buttons) verwendet.';
$_lang['quip.prop_unapprovedcss_desc'] = 'Die CSS-Klasse, die für ungenehmigte Kommentare verwendet wird.';
$_lang['quip.prop_usecss_desc'] = 'Wenn diese Einstellung auf "Ja" steht, stellt Quip ein einfaches CSS-Template für die Darstellung zur Verfügung.';
$_lang['quip.prop_usemargins_desc'] = 'Wenn diese Einstellung auf "Nein" steht, verwendet Quip ol- und li-Tags für jeden Kommentar, auch in Unter-Threads. Wenn diese Einstellung auf "Ja" steht, werden Abstände für die Kommentare in Unter-Threads verwendet.';
$_lang['quip.prop_usegravatar_desc'] = 'Wenn diese Einstellung auf "Ja" steht, wird versucht, Gravatar-Bilder als Avatare (Benutzerbilder) zu verwenden.';

/* QuipReply */
$_lang['quip.prop_reply_autoconvertlinks_desc'] = 'Wenn diese Einstellung auf "Ja" steht, werden URLs automatisch in Links umgewandelt.';
$_lang['quip.prop_reply_closeafter_desc'] = 'Gibt die Anzahl von Tagen an, nach denen der Thread automatisch geschlossen wird (es werden dann keine neuen Kommentare mehr akzeptiert). Setzen Sie diese Einstellung auf 0, um den Thread dauerhaft geöffnet zu lassen.';
$_lang['quip.prop_reply_closed_desc'] = 'Wenn diese Einstellung auf "Ja" steht, werden im Thread keine neuen Kommentare akzeptiert.';
$_lang['quip.prop_reply_dateformat_desc'] = 'Das Format von Datumsangaben, die für einen Kommentar angezeigt werden.';
$_lang['quip.prop_reply_debug_desc'] = 'Setzen Sie diese Einstellung auf "Ja", um den Debug-Modus einzuschalten. Für produktive Websites nicht empfohlen.';
$_lang['quip.prop_reply_debuguser_desc'] = 'Wenn der Debug-Modus eingeschaltet ist, wird der Benutzername von $modx->user auf diesen Wert gesetzt.';
$_lang['quip.prop_reply_debuguserid_desc'] = 'Wenn der Debug-Modus eingeschaltet ist, wird die ID von $modx->user auf diesen Wert gesetzt.';
$_lang['quip.prop_reply_disablerecaptchawhenloggedin_desc'] = 'Wenn der Benutzer eingeloggt ist, reCaptcha nicht verwenden.';
$_lang['quip.prop_reply_dontmoderatemanagerusers_desc'] = 'Kommentare von Benutzern, die in den MODx-Revolution-Manager eingeloggt sind, nicht moderieren.';
$_lang['quip.prop_reply_extraautolinksattributes_desc'] = 'Zusätzliche HTML-Attribute, die zu automatisch umgewandelten Links hinzugefügt werden, wenn autoConvertLinks auf den Wert "Ja" gesetzt wurde.';
$_lang['quip.prop_reply_gravataricon_desc'] = 'Das Standard-Gravatar-Icon, das geladen wird, wenn für einen Benutzer kein anderes gefunden wurde.';
$_lang['quip.prop_reply_gravatarsize_desc'] = 'Die Größe des Gravatars in Pixeln.';
$_lang['quip.prop_reply_idprefix_desc'] = 'Wenn Sie mehrere Quip-Instanzen auf einer Seite verwenden möchten, ändern Sie diesen ID-Präfix.';
$_lang['quip.prop_reply_moderate_desc'] = 'Wenn diese Einstellung auf "Ja" steht, werden alle neuen Beiträge in diesem Thread moderiert.';
$_lang['quip.prop_reply_moderateanonymousonly_desc'] = 'Wenn diese Einstellung auf "Ja" steht, werden nur die Kommentare anonymer (nicht eingeloggter) Benutzer moderiert.';
$_lang['quip.prop_reply_moderatefirstpostonly_desc'] = 'Wenn diese Einstellung auf "Ja" steht, wird nur der erste Beitrag eines Benutzers moderiert. Alle weiteren Beiträge werden automatisch genehmigt. Dies betrifft nur eingeloggte Benutzer.';
$_lang['quip.prop_reply_moderatorgroup_desc'] = 'Alle Benutzer dieser Benutzergruppe haben Zugriff auf die Moderierungs-Funktionen.';
$_lang['quip.prop_reply_moderators_desc'] = 'Eine kommaseparierte Liste von Moderatoren-Benutzernamen für diesen Thread.';
$_lang['quip.prop_reply_notifyemails_desc'] = 'Eine kommaseparierte Liste von E-Mail-Adressen, an die eine Benachrichtigungs-E-Mail gesendet werden soll, wenn ein neuer Beitrag in diesem Thread verfasst wurde.';
$_lang['quip.prop_reply_postaction_desc'] = 'Der Name des Submit-Buttons, mittels dessen das Abschicken eines Kommentars ausgelöst wird.';
$_lang['quip.prop_reply_previewaction_desc'] = 'Der Name des Submit-Buttons, mittels dessen das Anzeigen der Vorschau eines Kommentars ausgelöst wird.';
$_lang['quip.prop_reply_recaptcha_desc'] = 'Wenn diese Einstellung auf "Ja" steht, wird die reCaptcha-Unterstützung aktiviert.';
$_lang['quip.prop_reply_recaptchatheme_desc'] = 'Wenn die Eigenschaft "recaptcha" auf "Ja" steht, können Sie hier ein Theme für das reCaptcha-Steuerelement auswählen.';
$_lang['quip.prop_reply_redirectto_desc'] = 'Optional. Nach dem Posten einer Antwort zur Ressource mit dieser ID weiterleiten.';
$_lang['quip.prop_reply_redirecttourl_desc'] = 'Optional. Nach dem Posten einer Antwort zu dieser absoluten URL weiterleiten.';
$_lang['quip.prop_reply_requireauth_desc'] = 'Wenn diese Einstellung auf "Ja" steht, können nur eingeloggte Benutzer Kommentare verfassen.';
$_lang['quip.prop_reply_requireusergroups_desc'] = 'Optional. Eine kommaseparierte Liste von Benutzergruppen, auf die die Berechtigung zu kommentieren begrenzt wird.';
$_lang['quip.prop_reply_requireusergroups_desc'] = 'Optional. Eine kommaseparierte Liste von Benutzergruppen, auf die das Kommentieren beschränkt wird.';
$_lang['quip.prop_reply_tpladdcomment_desc'] = 'Das Formular zum Hinzufügen von Kommentaren. Wird hier der Name eines Chunks eingegeben, wird dieser Chunk für die Darstellung des Formulars verwendet.';
$_lang['quip.prop_reply_tpllogintocomment_desc'] = 'Der Hinweis, der angezeigt wird, wenn nur eingeloggte Benutzer Kommentare verfassen dürfen und der Benutzer nicht eingeloggt ist. Wird hier der Name eines Chunks eingegeben, wird dieser Chunk für die Darstellung des Hinweises verwendet.';
$_lang['quip.prop_reply_tplpreview_desc'] = 'Das Template für die Vorschau eines Kommentars. Wird hier der Name eines Chunks eingegeben, wird dieser Chunk für die Darstellung der Vorschau verwendet.';
$_lang['quip.prop_reply_tplreport_desc'] = 'Der Link, mittels dessen ein Kommentar als Spam gemeldet werden kann. Wird hier der Name eines Chunks eingegeben, wird dieser Chunk für die Darstellung des Links (bzw. Buttons) verwendet.';
$_lang['quip.prop_reply_usecss_desc'] = 'Wenn diese Einstellung auf "Ja" steht, stellt Quip ein einfaches CSS-Template für die Darstellung zur Verfügung.';
$_lang['quip.prop_reply_usegravatar_desc'] = 'Wenn diese Einstellung auf "Ja" steht, wird versucht, Gravatar-Bilder als Avatare (Benutzerbilder) zu verwenden.';

/* QuipLatestComments */
$_lang['quip.prop_late_altrowcss_desc'] = 'Die CSS-Klasse, mit der jeder zweite Kommentar ausgezeichnet wird.';
$_lang['quip.prop_late_bodylimit_desc'] = 'Die Anzahl an Zeichen, auf die das Inhaltsfeld bei der Anzeige des Kommentars begrenzt wird, bevor Auslassungspunkte angezeigt werden.';
$_lang['quip.prop_late_contexts_desc'] = 'Eine kommaseparierte Liste von Kontexten, aus denen die Liste der neuesten Kommentare zusammengestellt wird. Ist diese Eigenschaft nicht gesetzt, werden Kommentare aus allen Kontexten verwendet.';
$_lang['quip.prop_late_dateformat_desc'] = 'Das Format von Datumsangaben, die für einen Kommentar angezeigt werden.';
$_lang['quip.prop_late_family_desc'] = 'Die "Familie" von Threads, für die die Liste der neuesten Kommentare zusammengestellt wird. Wirkt sich nur aus, wenn der Typ auf "Familie" gestellt wurde.';
$_lang['quip.prop_late_limit_desc'] = 'Die Anzahl der Kommentare, die angezeigt werden sollen.';
$_lang['quip.prop_late_placeholderprefix_desc'] = 'Der Präfix für die globalen Platzhalter, die von QuipLatestComments zur Verfügung gestellt werden.';
$_lang['quip.prop_late_rowcss_desc'] = 'Die CSS-Klasse, mit der jede Zeile ausgezeichnet wird.';
$_lang['quip.prop_late_sortby_desc'] = 'Das Feld, nach dem sortiert wird.';
$_lang['quip.prop_late_sortbyalias_desc'] = 'Der Alias der Klasse, die bei der Sortierung verwendet wird ("quipComment" oder "Author").';
$_lang['quip.prop_late_sortdir_desc'] = 'Die Sortierrichtung.';
$_lang['quip.prop_late_start_desc'] = 'Der Startindex der Kommentare, aus denen die Liste der neuesten Kommentare zusammengestellt wird.';
$_lang['quip.prop_late_striptags_desc'] = 'Wenn diese Einstellung auf "Ja" steht, werden Tags aus dem Inhalt des Kommentars entfernt.';
$_lang['quip.prop_late_thread_desc'] = 'Die ID (der Name) des Threads, aus dem die Liste der neuesten Kommentare zusammengestellt wird. Wirkt sich nur aus, wenn der Typ auf "Thread" gestellt wurde.';
$_lang['quip.prop_late_toplaceholder_desc'] = 'Wenn diese Einstellung nicht leer gelassen wird, wird der Inhalt in dem Platzhalter zur Verfügung gestellt, dessen Name hier eingegeben wird, und nicht direkt ausgegeben.';
$_lang['quip.prop_late_tpl_desc'] = 'Das Chunk-Template, das für jede Zeile verwendet wird.';
$_lang['quip.prop_late_type_desc'] = 'Gibt an, ob die Liste der neuesten Kommentare aus allen Kommentaren, pro Thread, pro "Familie" von Threads oder pro Benutzer zusammengestellt werden soll.';
$_lang['quip.prop_late_user_desc'] = 'Die Benutzer-ID oder der Benutzername, für den die Liste der neuesten Kommentare zusammengestellt wird. Wirkt sich nur aus, wenn der Typ auf "Benutzer" gestellt wurde.';

/* QuipCount */
$_lang['quip.prop_count_thread_desc'] = 'Die ID (der Name) des Threads, dessen Kommentare gezählt werden sollen. Wirkt sich nur aus, wenn der Typ "Thread" enthält.';
$_lang['quip.prop_count_toplaceholder_desc'] = 'Wenn diese Einstellung nicht leer gelassen wird, wird der Inhalt in dem Platzhalter zur Verfügung gestellt, dessen Name hier eingegeben wird, und nicht direkt ausgegeben.';
$_lang['quip.prop_count_type_desc'] = 'Wird hier "Thread" ausgewählt, wird die Anzahl der Kommentare in einem Thread gezählt. Wird hier "user" ausgewählt, wird die Gesamtanzahl der Kommentare eines Benutzers verwendet. Unterstützt eine kommaseparierte Liste von Typen.';
$_lang['quip.prop_count_user_desc'] = 'Die Benutzer-ID oder der Benutzername, dessen Kommentare gezählt werden sollen. Wirkt sich nur aus, wenn der Typ "Benutzer" enthält.';
$_lang['quip.prop_count_family_desc'] = 'Die "Familie" von Threads, deren Kommentare gezählt werden sollen. Wirkt sich nur aus, wenn der Typ "Familie" enthält.';

/* QuipRss */
$_lang['quip.prop_rss_tpl_desc'] = 'Das Chunk-Template, das für jeden RSS-Eintrag verwendet wird.';
$_lang['quip.prop_rss_containertpl_desc'] = 'Das Chunk-Template, das den Container für den RSS-Feed bildet.';
$_lang['quip.prop_rss_placeholderprefix_desc'] = 'Der Präfix für die globalen Platzhalter, die von QuipRss zur Verfügung gestellt werden.';
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
 * Quip properties Italian language file
 *
 * @package quip
 * @subpackage lexicon
 */
/* options */
$_lang['quip.all'] = 'Tutto';
$_lang['quip.ascending'] = 'Ascendente';
$_lang['quip.author'] = 'Autore';
$_lang['quip.comment'] = 'Commento';
$_lang['quip.descending'] = 'Discendente';
$_lang['quip.family'] = 'Famiglia';
$_lang['quip.opt_blackglass'] = 'Vetro Nero';
$_lang['quip.opt_clean'] = 'Pulisci';
$_lang['quip.opt_red'] = 'Rosso';
$_lang['quip.opt_white'] = 'Bianco';
$_lang['quip.thread'] = 'Discussione';
$_lang['quip.user'] = 'Utente';

/* Quip */
$_lang['quip.prop_allowremove_desc'] = 'Permetti agli utenti loggati di rimuovere i propri commenti.';
$_lang['quip.prop_allowreportasspam_desc'] = 'Permetti agli utenti loggati di segnalare commenti come Spam.';
$_lang['quip.prop_altrowcss_desc'] = 'La classe CSS da aggiungere ai Commenti Alternati.';
$_lang['quip.prop_anonymousname_desc'] = 'Il Nome da mostrare per post di Anonimi. Defaults &egrave; "Anonymous".';
$_lang['quip.prop_authortpl_desc'] = 'Nome del Chunk da usare per il nome Autore nei Commenti.';
$_lang['quip.prop_closeafter_desc'] = 'Numero di giorni dopo di cui la discussione viene chiusa ai Commenti. Inserisci 0 per lasciarla sempre aperto.';
$_lang['quip.prop_closed_desc'] = 'Se impostato su vero (1), la discussione NON accetter&agrave; nuovi commenti.';
$_lang['quip.prop_currentpagecls_desc'] = 'La classe CSS per inserire il numero di pagina corrente.';
$_lang['quip.prop_dateformat_desc'] = 'Il formato della data mostrato nei commenti.';
$_lang['quip.prop_debug_desc'] = 'Imposta vero (1) per attivare il debug mode. Non raccomandato per siti di produzione.';
$_lang['quip.prop_debuguser_desc'] = 'Se il debug &egrave; attivo, a questo valore sar&agrave; assegnato lo  username di $modx->user .';
$_lang['quip.prop_debuguserid_desc'] = 'Se il debug &egrave; attivo, a questo valore sar&agrave; assegnato il valore id di $modx->user .';
$_lang['quip.prop_gravataricon_desc'] = 'La icona Gravatar da inserire di default se non presente per un utente.';
$_lang['quip.prop_gravatarsize_desc'] = 'La dimensione, in pixels, del Gravatar.';
$_lang['quip.prop_idprefix_desc'] = 'Se vuoi usare chiamate multiple di Quip in una pagina, cambia questo ID prefix.';
$_lang['quip.prop_limit_desc'] = 'Il numero massimo di commenti per pagina. Impostalo ad un numero divero da zero per abilitare la paginazione.';
$_lang['quip.prop_namefield_desc'] = 'Il Campo da usare per il nome Autore di ogni commento. Si raccomandano i valori "name" o "username".';
$_lang['quip.prop_maxdepth_desc'] = 'La profondit&agrave; massima delle risposte che possono essere annidate in una discussione.';
$_lang['quip.prop_olcss_desc'] = 'La classe CSS da inserire nel parent tags ol per ogni commento.';
$_lang['quip.prop_pagecls_desc'] = 'La classe CSS da aggiungere al numero-link di una pagina non-corrente.';
$_lang['quip.prop_paginationcls_desc'] = 'La classe CSS da aggiungere al contenitore OL della paginazione.';
$_lang['quip.prop_parent_desc'] = 'Il genitore da cui partire quando viene viasualizzata una discussione.';
$_lang['quip.prop_placeholderprefix_desc'] = 'Il prefisso per i placeholders globali impostati da Quip.';
$_lang['quip.prop_requireauth_desc'] = 'Se impostato su vero (1), solo gli utenti loggati potranno commentare.';
$_lang['quip.prop_requireusergroups_desc'] = 'Opzionale. Lista di gruppi utenti, separati da virgola, che possono commentare.';
$_lang['quip.prop_removeaction_desc'] = 'The name of the submit field to initiate a comment remove.';
$_lang['quip.prop_removethreshold_desc'] = 'Se allowRemove è attivo, il numero di minuti entro i quali si pu&ograve; rimuovere il proprio commento. Defaults: 3 minuti.';
$_lang['quip.prop_replyresourceid_desc'] = 'Il numero ID della Risorsa dove si trova lo snippet QuipReply, per rispondere ai commenti annidati.';
$_lang['quip.prop_reportaction_desc'] = 'The name of the submit field to initiate a comment report as spam.';
$_lang['quip.prop_rowcss_desc'] = 'La classe CSS da aggiungere al contenitore esterno, div, di ogni commento.';
$_lang['quip.prop_showanonymousname_desc'] = 'Se impostato su vero, moster&agrave; il valore della propriet&agrave; "Nome anonimo" (defaults &egrave; "Anonymous") se l\'utente non &egrave; loggato quando lascia il commento.';
$_lang['quip.prop_start_desc'] = 'L\'indice di default del commento da cui partire. Si raccomanda di lasciare a 0.';
$_lang['quip.prop_sortby_desc'] = 'Il campo secondo cui ordinare.';
$_lang['quip.prop_sortbyalias_desc'] = 'L\'alia delle classi da usare con "ordina per".';
$_lang['quip.prop_sortdir_desc'] = 'La direzione dell\'ordinamento.';
$_lang['quip.prop_thread_desc'] = 'Il nome univoco della discussione.';
$_lang['quip.prop_threaded_desc'] = 'Stabilisce se questa discussione pu&ograve; avere o meno commenti annidati.';
$_lang['quip.prop_threadedpostmargin_desc'] = 'Il margine, in pixels, di quanto i commenti annidati vengono spostati verso destra, per ogni livello di profondit&agrave; a cui vanno.';
$_lang['quip.prop_toplaceholder_desc'] = 'Se impostato, mostrer&agrave; il contenuto nel segnaposto specificato in questa propriet&agrave;, invece di mostrare direttamente il contenuto.';
$_lang['quip.prop_tplcomment_desc'] = 'Un Chunk per il commento stesso.';
$_lang['quip.prop_tplcommentoptions_desc'] = 'Un chunk per le opzioni, come "elimina", mostrate al proprietario di un commento.';
$_lang['quip.prop_tplcomments_desc'] = 'un Chunk per il contenitore esterno dei commenti.';
$_lang['quip.prop_tplpagination_desc'] = 'Un chunk per il contenitore OL della paginazione.';
$_lang['quip.prop_tplpaginationitem_desc'] = 'Un Chunk per ogni numero-link della pagina non-corrente.';
$_lang['quip.prop_tplpaginationcurrentitem_desc'] = 'Un Chunk per il numero-link della pagina corrente.';
$_lang['quip.prop_tplreport_desc'] = 'Il link per segnalare un commento come spam. Pu&ograve; essere sia il nome di un chunk che un valore. Se viene usato un valore, sovrascriver&agrave; il chunk.';
$_lang['quip.prop_unapprovedcss_desc'] = 'La classe CSS da aggiungere ai commenti non approvati.';
$_lang['quip.prop_usecss_desc'] = 'Se vero, Quip fornir&agrave; un template CSS di base per la presentazione.';
$_lang['quip.prop_usemargins_desc'] = 'Se falso, Quip user&agrave; gli elementi ol/li per ogni commento e discussione annidata. Se vero, user&agrave; i margini padded per ogni commento nella discussione.';
$_lang['quip.prop_usegravatar_desc'] = 'Se vero, prover&agrave; a utilizzare le immagini di Gravatar come avatars.';

/* QuipReply */
$_lang['quip.prop_reply_autoconvertlinks_desc'] = 'Se vero, convertir&agrave; automaticamente gli URLs in links.';
$_lang['quip.prop_reply_closeafter_desc'] = 'Le discussioni saranno automaticamente chiuse ai nuovi commenti dopo questo numero di giorni. Imposta il valore 0 per lasciare le discussioni sempre aperte.';
$_lang['quip.prop_reply_closed_desc'] = 'Se impostato su vero, questa discussione non accetter&agrave; nuovi commenti.';
$_lang['quip.prop_reply_dateformat_desc'] = 'Il formato delle date mostrate per i commenti.';
$_lang['quip.prop_reply_debug_desc'] = 'Imposta su vero per attivare la modalit&agrave; debug. Non &egrave; raccomandato per i siti di produzione.';
$_lang['quip.prop_reply_debuguser_desc'] = 'Con il debug attivo, assegner&agrave; a questo valore il nome utente di $modx->user.';
$_lang['quip.prop_reply_debuguserid_desc'] = 'Con il debug attivo, assegner&agrave; a questo valore il codice id di $modx->user.';
$_lang['quip.prop_reply_disablerecaptchawhenloggedin_desc'] = 'Se l\'utente &egrave; loggato, non usare reCaptcha.';
$_lang['quip.prop_reply_dontmoderatemanagerusers_desc'] = 'Non moderare mai gli utenti che sono loggati nel Pannello di controllo di MODx.';
$_lang['quip.prop_reply_extraautolinksattributes_desc'] = 'Qualasiasi attributo HTML extra da aggiungere ai links auto-convertiti, qualora autoConvertLinks sia impostato su 1.';
$_lang['quip.prop_reply_gravataricon_desc'] = 'L\'icona Gravatar di default da caricare qualora non ne venga trovata alcuna per un utente.';
$_lang['quip.prop_reply_gravatarsize_desc'] = 'La misura, in pixels, del Gravatar.';
$_lang['quip.prop_reply_idprefix_desc'] = 'Se vuoi usare chiamate multiple di Quip in una pagina, cambia questo prefisso ID.';
$_lang['quip.prop_reply_moderate_desc'] = 'Se impostato su vero, tutti i nuovi posts di questa discussione saranno moderati.';
$_lang['quip.prop_reply_moderateanonymousonly_desc'] = 'Se impostato su vero, soltanto gli utenti anonimi (non-loggati) saranno moderati.';
$_lang['quip.prop_reply_moderatefirstpostonly_desc'] = 'Se impostato su vero, soltanto il primo post di un utente sar&agrave; moderato. Tutti i posts successivi saranno automaticamente approvati. Questo si applica soltanto agli utenti loggati.';
$_lang['quip.prop_reply_moderatorgroup_desc'] = 'Qualsiasi utente in questo gruppo di utenti avr&agrave; diritti di accesso come moderatore.';
$_lang['quip.prop_reply_moderators_desc'] = 'Un elenco, separato da virgola, di nomi utenti di moderatori per questa discussione.';
$_lang['quip.prop_reply_notifyemails_desc'] = 'Un elenco, separato da virgola, di indirizzi email a cui spedire notifiche quando viene lasciato un nuovo post in questa discussione.';
$_lang['quip.prop_reply_postaction_desc'] = 'The name of the submit field to initiate a comment post.';
$_lang['quip.prop_reply_previewaction_desc'] = 'The name of the submit field to initiate a comment preview.';
$_lang['quip.prop_reply_recaptcha_desc'] = 'Se vero, il supporto reCaptcha sar&agrave; abilitato.';
$_lang['quip.prop_reply_recaptchatheme_desc'] = 'Se `recaptcha` &egrave; impostato su 1, questo selezionaer&agrave; un tema per lo widget reCaptcha.';
$_lang['quip.prop_reply_redirectto_desc'] = 'Opzionale. Dopo aver postato una risposta, reindirizza alla risorsa con questo ID.';
$_lang['quip.prop_reply_redirecttourl_desc'] = 'Opzionale. Dopo aver postato una risposta, reindirizza a questo indirizzo URL assoluto.';
$_lang['quip.prop_reply_requireauth_desc'] = 'Se impostato su vero, soltanto gli utenti loggati potranno lasciare commenti.';
$_lang['quip.prop_reply_requirepreview_desc'] = 'Se impostato su vero, a un utente sar&agrave; chiesto di controllare l\'anteprima prima di lasciare un commento.';
$_lang['quip.prop_reply_requireusergroups_desc'] = 'Opzionale. Un elenco, separato da virgola, di Gruppi utenti a cui consentire i commenti.';
$_lang['quip.prop_reply_tpladdcomment_desc'] = 'Il form per aggiugnere un commento. Pu&ograve; essere sia un chunk che un valore. Se viene impostato un valore, questo sovrascriver&agrave; il chunk.';
$_lang['quip.prop_reply_tpllogintocomment_desc'] = 'Il codice da mostrare quando un utente non &egrave; loggato. Pu&ograve; essere sia un chunk che un valore. Se viene impostato un valore, questo sovrascriver&agrave; il chunk.';
$_lang['quip.prop_reply_tplpreview_desc'] = 'Il tpl del testo per l\'anteprima. Pu&ograve; essere sia un chunk che un valore. Se viene impostato un valore, questo sovrascriver&agrave; il chunk.';
$_lang['quip.prop_reply_tplreport_desc'] = 'Il link su un commento per riportarlo come spam. Pu&ograve; essere sia un chunk che un valore. Se viene impostato un valore, questo sovrascriver&agrave; il chunk.';
$_lang['quip.prop_reply_usecss_desc'] = 'Se vero, Quip fornir&agrave; un template CSS di base per la presentazione.';
$_lang['quip.prop_reply_usegravatar_desc'] = 'Se vero, prover&agrave; a usare le immagini Gravatar come avatars.';

/* QuipLatestComments */
$_lang['quip.prop_late_altrowcss_desc'] = 'La classe CSS da aggiungere ai commenti alternati.';
$_lang['quip.prop_late_bodylimit_desc'] = 'Il numero di caratteri a cui limitare il commento prima di aggiungere i puntini.';
$_lang['quip.prop_late_contexts_desc'] = 'Un elenco, separato da virgola, di contesti da cui prendere i commenti, Se non viene impostato verranno presi commenti da tutti i Contesti.';
$_lang['quip.prop_late_dateformat_desc'] = 'Il formato delle date mostrate per i commenti.';
$_lang['quip.prop_late_family_desc'] = 'La famiglia di discussioni da cui attingere. Soltanto se il tipo &egrave; impostato a Famiglia.';
$_lang['quip.prop_late_limit_desc'] = 'Il numero di commenti da prendere.';
$_lang['quip.prop_late_placeholderprefix_desc'] = 'Il prefisso per i segnaposti globali impostati da QuipLatestComments.';
$_lang['quip.prop_late_rowcss_desc'] = 'La classe CSS da aggiungere a ogni riga.';
$_lang['quip.prop_late_sortby_desc'] = 'Il campo secondo cui ordinare.';
$_lang['quip.prop_late_sortbyalias_desc'] = 'L\'alias delle classi da usare per l\'ordinamento.';
$_lang['quip.prop_late_sortdir_desc'] = 'La direzione dell\'ordinamento.';
$_lang['quip.prop_late_start_desc'] = 'The start index of comments to pull from.';
$_lang['quip.prop_late_striptags_desc'] = 'Se impostato su vero, i tags saranno rimossi dal corpo del testo.';
$_lang['quip.prop_late_thread_desc'] = 'Il codice ID della discurssione da cui attingere. Soltanto se "tipo" &egrave; impostato a "Thread".';
$_lang['quip.prop_late_toplaceholder_desc'] = 'Se impostato, mostrer&agrave; il contenuto nel segnaposto specificato in questa propriet&agrave;, invece che mostrarlo direttamente.';
$_lang['quip.prop_late_tpl_desc'] = 'Il tpl del chunk da usare per ogni riga.';
$_lang['quip.prop_late_type_desc'] = 'Stabilisce se prendere un elenco di tutti i commenti, per thread, per family of threads, or per user.';
$_lang['quip.prop_late_user_desc'] = 'Il codice ID dello username da cui attingere. Soltanto se "tipo" &egrave; impostato su "User".';

/* QuipCount */
$_lang['quip.prop_count_thread_desc'] = 'Il codice ID del thread  da cui attingere. Soltanto se tipo &egrave; contiente `thread`.';
$_lang['quip.prop_count_toplaceholder_desc'] = 'Se impostato, mostrer&agrave; il contenuto in output nel segnaposto specificato in questa propriet&agrave;, invece di mostrarlo direttamente.';
$_lang['quip.prop_count_type_desc'] = 'Se contiene `thread`, Contenr&agrave; il # di commenti in una discussione. Se contiene `user`, prender&agrave; # dei commenti totali di un User. Supporta un elenco, separato da virgola, di tipi.';
$_lang['quip.prop_count_user_desc'] = 'Il codice ID di un Utente o lo usernmane da cui attingere. Soltanto se "tipo" contiene `user`.';
$_lang['quip.prop_count_family_desc'] = 'La famiglia di threads da cui attingere. Soltanto se "tipo" contiene  `family`.';

/* QuipRss */
$_lang['quip.prop_rss_tpl_desc'] = 'Il tpl del chunk da usare per ogni elemento RSS.';
$_lang['quip.prop_rss_containertpl_desc'] = 'Il tpl del chunk da usare come contenitore dei feeds RSS.';
$_lang['quip.prop_rss_placeholderprefix_desc'] = 'Il pregisso per i segnaposti globali impostati da QuipRss.';
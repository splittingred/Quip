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
 * Quip properties Polish language file
 * Translated by Jakub Kalina <kuba.kalina@gmail.com>
 *
 * @package quip
 * @subpackage lexicon
 */
/* options */
$_lang['quip.all'] = 'Wszystkie';
$_lang['quip.ascending'] = 'Rosnąco';
$_lang['quip.author'] = 'Autor';
$_lang['quip.comment'] = 'Komentarz';
$_lang['quip.descending'] = 'Malejąco';
$_lang['quip.family'] = 'Rodzina';
$_lang['quip.opt_blackglass'] = 'Czarny szklisty';
$_lang['quip.opt_clean'] = 'Wyczyść';
$_lang['quip.opt_red'] = 'Czerwony';
$_lang['quip.opt_white'] = 'Biały';
$_lang['quip.thread'] = 'Wątek';
$_lang['quip.user'] = 'Użytkownik';

/* Quip */
$_lang['quip.prop_allowremove_desc'] = 'Pozwól zalogowanym użytkownikom usuwać swoje wpisy.';
$_lang['quip.prop_allowreportasspam_desc'] = 'Pozwól zalogowanym użytkownikom raportować niechciane wiadomości.';
$_lang['quip.prop_altrowcss_desc'] = 'Klasa CSS dla wyświetlanych naprzemiennie komentarzy.';
$_lang['quip.prop_anonymousname_desc'] = 'Nazwa użytkownika wyświetlana dla anonimowych wpisów. Domyślnie ustawiona jako "Anonim".';
$_lang['quip.prop_authortpl_desc'] = 'Nazwa fragmentu używanego do wyświetlania nazwy autora komentarza.';
$_lang['quip.prop_closeafter_desc'] = 'Automatycznie wyłączy możliwość komentowania w wątku po określonej liczbie dni. Ustaw 0, aby możliwość komentowania była zawsze włączona.';
$_lang['quip.prop_closed_desc'] = 'Jeśli włączone, nie będzie możliwe komentowanie w wątku.';
$_lang['quip.prop_currentpagecls_desc'] = 'Klasa CSS aktualnej strony paginacji.';
$_lang['quip.prop_dateformat_desc'] = 'Format dat wyświetlanych w komentarzu.';
$_lang['quip.prop_debug_desc'] = 'Włącz w celu uruchomienia trybu debug. Niezalecane w środowisku produkcyjnym.';
$_lang['quip.prop_debuguser_desc'] = 'Jeśli debug jest włączony, nazwa użytkownika z pola $modx->user będzie ustawiana na tę wartość.';
$_lang['quip.prop_debuguserid_desc'] = 'Jeśli debug jest włączony, id użytkownika z pola $modx->user będzie ustawiona na tę wartość.';
$_lang['quip.prop_gravataricon_desc'] = 'Domyśla ikona Gravatar w przypadku gdy użytkownik nie zostanie znaleziony.';
$_lang['quip.prop_gravatarsize_desc'] = 'Rozmiar Gravatara wyrażony w pikselach.';
$_lang['quip.prop_idprefix_desc'] = 'Jeśli chcesz używać kilka instacji Quip na stronie, zmień ten prefiks ID.';
$_lang['quip.prop_limit_desc'] = 'Limit komentarzy wyświetlanych na stronie. Ustawienie wartości różnej od zera włączy paginację.';
$_lang['quip.prop_namefield_desc'] = 'Pole na nazwę autora komentarza. Zalecane wartości to "name" lub "username".';
$_lang['quip.prop_maxdepth_desc'] = 'Maksymalny poziom zagłębienia odpowiedzi w widoku wątku.';
$_lang['quip.prop_olcss_desc'] = 'Klasa CSS nadrzędnego tagu OL każdego z komentarzy.';
$_lang['quip.prop_pagecls_desc'] = 'Klasa CSS nieaktywnego łącza paginacji.';
$_lang['quip.prop_paginationcls_desc'] = 'Klasa CSS wrappera tagu OL paginacji.';
$_lang['quip.prop_parent_desc'] = 'Id rodzica, od którego zacząć wyświetlanie wątku.';
$_lang['quip.prop_placeholderprefix_desc'] = 'Prefiks globalnych placeholderów ustawianych przez Quip.';
$_lang['quip.prop_requireauth_desc'] = 'Jeśli ustawione true, tylko zalogowani użytkownicy mogą dodawać komentarze.';
$_lang['quip.prop_requireusergroups_desc'] = 'Opcjonalne. Rozdzielona przecinkami lista Grup Użytkowników, do których ograniczyć możliwość komentowania.';
$_lang['quip.prop_removeaction_desc'] = 'Nazwa pola typu submit inicjującego usunięcie komentarza.';
$_lang['quip.prop_removethreshold_desc'] = 'Jeśli allowRemove jest true, czas w minutach, przez który użytkownik może usunąć swój wpis po dodaniu. Domyślnie są to 3 minuty.';
$_lang['quip.prop_replyresourceid_desc'] = 'ID zasobu, gdzie przechowywany jest snippet QuipReply, służący do odpowiadania na wątkowane komentarze.';
$_lang['quip.prop_reportaction_desc'] = 'Nazwa pola typu submit inicjującego zgłoszenie wiadomości jako niechcianej.';
$_lang['quip.prop_rowcss_desc'] = 'Klasa CSS zewnętrznego konterera DIV każdego komentarza.';
$_lang['quip.prop_showanonymousname_desc'] = 'Jeśli true, wyświetli wartość własności anonymousName (domyślnie "Anonim") jeśli użytkownik nie jest zalogowany podczas dodawania wpisu.';
$_lang['quip.prop_start_desc'] = 'Domyślny indeks komentarza, od którego rozpoczynać. Zalecane pozostawienie wartości 0.';
$_lang['quip.prop_sortby_desc'] = 'Pole, według którego sortować.';
$_lang['quip.prop_sortbyalias_desc'] = 'Alias klas używanych do sortowania.';
$_lang['quip.prop_sortdir_desc'] = 'Kierunek sortowania.';
$_lang['quip.prop_thread_desc'] = 'Unikalna nazwa wątku.';
$_lang['quip.prop_threaded_desc'] = 'Czy wątek może mieć wątkowane komentarze.';
$_lang['quip.prop_threadedpostmargin_desc'] = 'Margines, wyrażony w pikselach, o który wątkowany komentarz będzie wcięty na każdym z poziomów.';
$_lang['quip.prop_toplaceholder_desc'] = 'Jeśli ustawione, zwróci zawartość do placeholdera określonego w tej właściwości, zamiast zwracania zawartości bezpośrednio.';
$_lang['quip.prop_tplcomment_desc'] = 'Fragment dla komentarza.';
$_lang['quip.prop_tplcommentoptions_desc'] = 'Fragment dla opcji, takich jak usunięcie, wyświetlanych twórcy komentarza.';
$_lang['quip.prop_tplcomments_desc'] = 'Fragment dla zewnętrznego wrappera komentarzy.';
$_lang['quip.prop_tplpagination_desc'] = 'Fragment dla wrappera listy OL zawierającej paginację.';
$_lang['quip.prop_tplpaginationitem_desc'] = 'Fragment dla każego niewybranego łącza paginacji.';
$_lang['quip.prop_tplpaginationcurrentitem_desc'] = 'Fragment dla aktualnie wybranego łącza paginacji.';
$_lang['quip.prop_tplreport_desc'] = 'Łącze w komentarzu, służące do zgłaszania niechcianej wiadomości. Może to być zarówno nazwa fragmentu, lub wartość. Jeśli wybrana zostanie wartość, nadpisze to zdefiniowany fragment.';
$_lang['quip.prop_unapprovedcss_desc'] = 'Klasa CSS dla niezatwierdzonych komentarzy.';
$_lang['quip.prop_usecss_desc'] = 'Jeśli true, Quip dostarczy postawowy szablon CSS na potrzeby wyświetlania.';
$_lang['quip.prop_usemargins_desc'] = 'Jeśli false, Quip będzie używał elementy OL/LI dla każdego komentarza w wątkach. Jeśli true, będzie używał marginesów dla wątkowanych komentarzy.';
$_lang['quip.prop_usegravatar_desc'] = 'Jeśli true, będzie używał obrazów z serwisu Gravatar do wyświetlania awatarów.';

/* QuipReply */
$_lang['quip.prop_reply_autoconvertlinks_desc'] = 'Jeśli true, automatycznie będzie zamieniał adresy URLs w łącza.';
$_lang['quip.prop_reply_closeafter_desc'] = 'Automatycznie zamknie możliwość komentowania w wątku po określonej liczbie dni. Ustaw 0, aby wątek przyjmował komentarze bez limitu.';
$_lang['quip.prop_reply_closed_desc'] = 'Jeśli true, nie będzie możliwości komentowania w wątku.';
$_lang['quip.prop_reply_dateformat_desc'] = 'Format dat wyświetlanych w komentarzach.';
$_lang['quip.prop_reply_debug_desc'] = 'Ustaw true aby włączyć tryb debug. Niezalecane w środowisku produkcyjnym.';
$_lang['quip.prop_reply_debuguser_desc'] = 'Jeśli tryb debug jest włączony, ustawi wartość nazwy użytkownika $modx->user na wskazaną wartość.';
$_lang['quip.prop_reply_debuguserid_desc'] = 'Jeśli tryb debug jest włączony, ustawi wartość id $modx->user na wskazaną wartość.';
$_lang['quip.prop_reply_disablerecaptchawhenloggedin_desc'] = 'Jeśli użytkownik jest zalogowany, nie używaj reCaptcha.';
$_lang['quip.prop_reply_dontmoderatemanagerusers_desc'] = 'Nigdy nie moderuj użytkowników, którzy są zalogowani w panelu administracyjnym.';
$_lang['quip.prop_reply_extraautolinksattributes_desc'] = 'Dodatkowe atrybuty HTML dodawane do automatycznie tworzonych łączy, jeśli autoConvertLinks jest ustawione na 1.';
$_lang['quip.prop_reply_gravataricon_desc'] = 'Domyślna ikona Gravatar wyświetlana w momencie, gdy żaden użytkownik nie został odnaleziony.';
$_lang['quip.prop_reply_gravatarsize_desc'] = 'Rozmiar awatara Gravatar wyrażony w pikselach.';
$_lang['quip.prop_reply_idprefix_desc'] = 'Jeśli chcesz używać wielu instancji Quip na stronie, zmień ten prefiks ID.';
$_lang['quip.prop_reply_moderate_desc'] = 'Jeśli true, wszystkie nowe wpisy będą moderowane.';
$_lang['quip.prop_reply_moderateanonymousonly_desc'] = 'Jeśli true, tylko wpisy anonimowych użytkowników będą moderowane.';
$_lang['quip.prop_reply_moderatefirstpostonly_desc'] = 'Jeśli true, tylko pierwszy wpis użytkownika będzie moderowany. Wszystkie kolejne wpisy będą automatycznie zatwierdzane. Ta opcja ma zastosowanie tylko dla zalogowanych użytkowników.';
$_lang['quip.prop_reply_moderatorgroup_desc'] = 'Dowolny użytkownik w tej Grupie Użytkowników będzie miał uprawnienia do moderacji.';
$_lang['quip.prop_reply_moderators_desc'] = 'Rozdzielona przecinkami lista nazw użytkowników będących moderatorami wątku.';
$_lang['quip.prop_reply_notifyemails_desc'] = 'Rozdzielona przecinkami lista adresów email, na które wysyłać wiadomość o pojawieniu się nowego wpisu w wątku.';
$_lang['quip.prop_reply_postaction_desc'] = 'Nazwa pola typu submit inicjującego dodanie komentarza.';
$_lang['quip.prop_reply_previewaction_desc'] = 'Nazwa pola typu submit inicjującego podgląd komentarza.';
$_lang['quip.prop_reply_recaptcha_desc'] = 'Jeśli true, włączy reCaptcha.';
$_lang['quip.prop_reply_recaptchatheme_desc'] = 'Jeśli `recaptcha` ustawione na 1, ta właściwość określi szablon widgetu reCaptcha.';
$_lang['quip.prop_reply_redirectto_desc'] = 'Opcjonalne. Po wysłaniu odpowiedzi przekieruj do strony o wskazanym ID.';
$_lang['quip.prop_reply_redirecttourl_desc'] = 'Opcjonalne. Po wysłaniu odpowiedzi, przekieruj do adresu URL.';
$_lang['quip.prop_reply_requireauth_desc'] = 'Jeśli true, tylko zalogowani użytkownicy będą mogli dodawać komentarze..';
$_lang['quip.prop_reply_requirepreview_desc'] = 'Jeśli true, będzie wymagał od użytkownika podglądu komentarza przed wysłaniem.';
$_lang['quip.prop_reply_requireusergroups_desc'] = 'Opcjonalne. Rodzielona przecinkami lista Grup Użytkowników, do którycm ograniczyć możliwość komentowania.';
$_lang['quip.prop_reply_tpladdcomment_desc'] = 'Formularz dodawania komentarza. Może to być zarówno nazwa fragmentu, lub wartość. Jeśli wybrana zostanie wartość, nadpisze to zdefiniowany fragment.';
$_lang['quip.prop_reply_tpllogintocomment_desc'] = 'Część pokazywana niezalogowanemu użytkownikowi. Może to być zarówno nazwa fragmentu, lub wartość. Jeśli wybrana zostanie wartość, nadpisze to zdefiniowany fragment.';
$_lang['quip.prop_reply_tplpreview_desc'] = 'Szablon tekstu podglądu. Może to być zarówno nazwa fragmentu, lub wartość. Jeśli wybrana zostanie wartość, nadpisze to zdefiniowany fragment.';
$_lang['quip.prop_reply_tplreport_desc'] = 'Link w komentarzu służący do raportowania niechcianej wiadomości. Może to być zarówno nazwa fragmentu, lub wartość. Jeśli wybrana zostanie wartość, nadpisze to zdefiniowany fragment.';
$_lang['quip.prop_reply_usecss_desc'] = 'Jeśli true, Quip dostarczy postawowy szablon CSS na potrzeby wyświetlania.';
$_lang['quip.prop_reply_usegravatar_desc'] = 'Jeśli true, będzie używał obrazów z serwisu Gravatar do wyświetlania awatarów.';

/* QuipLatestComments */
$_lang['quip.prop_late_altrowcss_desc'] = 'Klasa CSS dla wyświetlanych naprzemiennie komentarzy.';
$_lang['quip.prop_late_bodylimit_desc'] = 'Limit znaków wyświetlanych w treści przed wielokropkiem.';
$_lang['quip.prop_late_contexts_desc'] = 'Rozdzielona przecinkami lista Kontekstów z których pobierać komentarze. Jeśli nieustawione, komentarze będą pobierane ze wszystkich kontekstów.';
$_lang['quip.prop_late_dateformat_desc'] = 'Format dat wyświetlanych w komentarzach.';
$_lang['quip.prop_late_family_desc'] = 'Rodzina pobieranych wątków. Działa tylko jeśli typ jest ustawiony na Rodzina.';
$_lang['quip.prop_late_limit_desc'] = 'Limit pobieranych komentarzy.';
$_lang['quip.prop_late_placeholderprefix_desc'] = 'Prefiks globalnych placeholderów ustawianych przez QuipLatestComments.';
$_lang['quip.prop_late_rowcss_desc'] = 'Klasa CSS każdego wiersza.';
$_lang['quip.prop_late_sortby_desc'] = 'Pole, według którego sortować.';
$_lang['quip.prop_late_sortbyalias_desc'] = 'Alias klas używanych z sortowaniem.';
$_lang['quip.prop_late_sortdir_desc'] = 'Kierunek sortowania.';
$_lang['quip.prop_late_start_desc'] = 'Indeks, od którego rozpocząć pobieranie komentarzy.';
$_lang['quip.prop_late_striptags_desc'] = 'Jeśli true, tagi HTML będą usuwane z treści.';
$_lang['quip.prop_late_thread_desc'] = 'ID wątku, z którego pobierać. Działa tylko jeśli typ jest ustawiony na Wątek.';
$_lang['quip.prop_late_toplaceholder_desc'] = 'Jeśli ustawione, zwróci zawartość do placeholdera określonego w tej właściwości, zamiast zwracania zawartości bezpośrednio.';
$_lang['quip.prop_late_tpl_desc'] = 'Fragment używany w każdym wierszu.';
$_lang['quip.prop_late_type_desc'] = 'Czy pobierać listę wszystkich komentarzy, dla wątków, dla rodzin wątków czy dla użytkownika.';
$_lang['quip.prop_late_user_desc'] = 'ID lub nazwa użytkownika, dla którego pobierać. Działa tylko jeśli typ jest ustawiony na Użytkownik.';

/* QuipCount */
$_lang['quip.prop_count_thread_desc'] = 'ID wątku, z którego pobierać. Działa tylko jeśli typ zawiera `thread`.';
$_lang['quip.prop_count_toplaceholder_desc'] = 'Jeśli ustawione, zwróci zawartość do placeholdera określonego w tej właściwości, zamiast zwracania zawartości bezpośrednio.';
$_lang['quip.prop_count_type_desc'] = 'Jeśli zawiera `thread`, zliczy komentarze w wątku. Jeśli zawiera `user`, pobierze sumę komentarzy użytkownika. Obsługuje listy typów rozdzielone przecinkami.';
$_lang['quip.prop_count_user_desc'] = 'ID lub nazwa użytkownika, dla którego pobierać. Działa tylko jeśli typ zawiera `user`.';
$_lang['quip.prop_count_family_desc'] = 'Rodzina wątków, z których pobierać. Działa tylko jeśli typ zawiera `family`.';

/* QuipRss */
$_lang['quip.prop_rss_tpl_desc'] = 'Fragment używany dla każdego wpisu RSS.';
$_lang['quip.prop_rss_containertpl_desc'] = 'Fragment używany do opakowania wpisu RSS.';
$_lang['quip.prop_rss_placeholderprefix_desc'] = 'Prefiks dla globalnych placeholderów ustawianych przez QuipRss.';
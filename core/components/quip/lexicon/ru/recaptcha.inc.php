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
 * reCaptcha Lexicon Topic
 *
 * @package quip
 * @subpackge lexicon
 */
$_lang['recaptcha.empty_answer'] = 'Слова введены неправильно. Пожалуйста, проверьте ваш ответ и повторите попытку.';
$_lang['recaptcha.incorrect'] = 'Код капчи был введён неправильно. Вернитесь назад и попробуйте снова. [[+error]]';
$_lang['recaptcha.mailhide_no_mcrypt'] = 'Для использования reCAPTCHA Mailhide у вас должен быть установлен PHP модуль mcrypt.';
$_lang['recaptcha.mailhide_no_api_key'] = 'Для использования reCAPTCHA Mailhide, вам надо получить публичный и личный ключи, это можно сделать на <a href="http://www.google.com/recaptcha/mailhide/apikey">http://www.google.com/recaptcha/mailhide/apikey</a>';
$_lang['recaptcha.no_api_key'] = 'Для использования reCAPTCHA вам надо получить API ключ на<a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a>';
$_lang['recaptcha.no_remote_ip'] = 'For security reasons, you must pass the remote ip to reCAPTCHA';
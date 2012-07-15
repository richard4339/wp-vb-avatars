<?php

/*
  Plugin Name: wp-vb-avatars
  Plugin URI: https://github.com/richard4339/wp-vb-avatars
  Description: Plugin replaces avatars with avatars from a vBulletin table, if it exists.
  Version: 0.1
  Author: Richard
  Author URI: http://www.digitalxero.com
 */

/*  Copyright 2012  RICHARD LYNSKEY

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

function site_get_avatar($avatar, $id_or_email, $size, $default, $alt) {
    $email = '';
    if (is_numeric($id_or_email)) {
        $id = (int) $id_or_email;
        $user = get_userdata($id);
        if ($user)
            $email = $user->user_email;
    } elseif (is_object($id_or_email)) {
        $email = $id_or_email->comment_author_email;
    }
    $forum_db = '';
    $img_folder = ''; // No trailing slash
    $img_path = $img_folder . '/image.php?u=';

    $my_wpdb = new wpdb(DB_USER, DB_PASSWORD, $forum_db, DB_HOST);

    $myrows = $my_wpdb->get_var($my_wpdb->prepare("SELECT userid
    FROM " . $forum_db . ".vb_user
    WHERE email = %s LIMIT 1", array($email)));

    if ($myrows != '') {
        $img = $img_path . $myrows;
    } elseif ($avatar) {
        return $avatar;
    } else {
        $img = $default;
    }

    $my_avatar = '<img src="' . $img . '" alt="' . $alt . '" height="' . $size . '" width="' . $size . '" class="avatar avatar-50 photo grav-hashed grav-hijack" />';
    return $my_avatar;
}

add_filter('get_avatar', 'site_get_avatar', 10, 5);
?>
<?php
/**
 * Created by PhpStorm.
 * User: jega
 * Date: 02/11/2018
 * Time: 23:11
 */

namespace Playlist;


class PlaylistFactory
{
    public static function createPlaylist($request)
    {
        if ($request['id']) {
            return new Playlist($request['id']);
        } else {
            return new Playlist();
        }
    }
}
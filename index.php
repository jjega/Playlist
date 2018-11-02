<?php
/**
 * Created by PhpStorm.
 * User: jonathanega
 * Date: 20/10/2018
 * Time: 22:49
 */

use App\Controller;

new Controller\PlaylistController($_GET['mode'], \Playlist\PlaylistFactory::createPlaylist($_GET), $_POST);
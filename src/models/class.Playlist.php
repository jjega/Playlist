<?php
/**
 * Created by PhpStorm.
 * User: jonathanega
 * Date: 22/10/2018
 * Time: 20:46
 */

namespace Playlist;

require_once 'class.db.php';
require_once 'class.Video.php';

use database\db;

class Playlist
{
    private $_db;

    public $id;
    public $name;
    public $nb_video;
    public $list_video = [];

    public static $TABLE_NAME = 'playlist';


    public function __construct($id = null)
    {
        $this->_db = new db();
        if ($id) {

            $sth = $this->_db->prepare('SELECT id, name, count(id_video) AS nb_video FROM ' . self::$TABLE_NAME . ' AS pl WHERE pl.id = :id');
            $sth->execute(array('id' => $id));
            $res = $sth->fetch(db::FETCH_OBJ);
            $this->id = intval($res->id);
            $this->name = $res->name;
            $this->nb_video = $res->nb_video - 1; // remove id_video 0
        }
    }

    public function create($name)
    {
        if ($sth = $this->_db->prepare('INSERT INTO ' . self::$TABLE_NAME . ' (name) VALUES  (:name)'))
        {
            $sth->execute(array('name' => $name));
            $id = $this->_db->lastInsertId();
            if ($id) {
                $this->id = intval($id);
                $this->name = $name;
                $this->nb_video = 0;
            }
        }

    }

    public function updateName($name)
    {
        $sth = $this->_db->prepare('UPDATE ' . self::$TABLE_NAME . ' SET name = :name');
        $sth->execute(array('name' => $name));
    }

    public function delete()
    {
        $this->_db->exec('DELETE FROM ' . self::$TABLE_NAME . " WHERE id = {$this->id}");
        $this->__destruct();
    }

    public function getVideos($id_video = null)
    {
        if ($id_video) {
            $query = 'SELECT `id_video` FROM ' . self::$TABLE_NAME . ' WHERE id = :id AND `id_prev_video` = :id_video AND `id_video` > 0';
        } else {
            $query = 'SELECT `id_video` FROM ' . self::$TABLE_NAME . ' WHERE id = :id AND `id_prev_video` IS :id_video AND `id_video` > 0';
        }

        $sth = $this->_db->prepare($query);
        $sth->execute(array('id' => $this->id, 'id_video' => $id_video));

        if ($res = $sth->fetch(db::FETCH_OBJ)) {
            $this->list_video[] =  new Video($res->id_video);
            $this->getVideos($res->id_video);
        }

    }

    public function setVideos()
    {

        if ($sth = $this->_db->prepare('INSERT INTO ' . self::$TABLE_NAME . ' (`id`, `id_video`, `id_prev_video`, `id_next_video`, `name`) VALUES (:id, :id_video, :id_prev_video, :id_next_video, :name) ON DUPLICATE KEY UPDATE id_next_video = :id_next_video, id_prev_video = :id_prev_video')) {

            foreach ($this->list_video as $rank => $video) {
                if ($this->list_video[$rank - 1]) {
                    $id_prev_video = $this->list_video[$rank - 1]->id;
                } else {
                    $id_prev_video = null;
                }

                if ($this->list_video[$rank + 1]) {
                    $id_next_video = $this->list_video[$rank + 1]->id;
                } else {
                    $id_next_video = null;
                }

                $sth->execute(array('id' => $this->id, 'id_video' => $video->id, 'id_prev_video' => $id_prev_video, 'id_next_video' => $id_next_video, 'name' => $this->name));

            }
        } else {
            print_r($this->_db->errorInfo());
        }
    }

    public function removeVideo($id_video)
    {
        $sth = $this->_db->prepare('SELECT `id_video`, `id_prev_video`, `id_next_video` FROM ' . self::$TABLE_NAME . ' WHERE id = :id AND `id_video` = :id_video ');
        $sth->execute(array('id' => $this->id,'id_video' => $id_video));
        if ($res = $sth->fetch(db::FETCH_OBJ)) {
            if ($res->id_prev_video) {
                $this->_db->exec("UPDATE " . self::$TABLE_NAME . " SET `id_next_video` = {$res->id_next_video} WHERE `id_video` = {$res->id_prev_video}");
            }
            if ($res->id_next_video) {
                $this->_db->exec("UPDATE " . self::$TABLE_NAME . " SET `id_prev_video` = {$res->id_prev_video} WHERE `id_video` = {$res->id_next_video}");
            }
            $this->_db->exec("DELETE FROM " . self::$TABLE_NAME . " WHERE id = {$this->id} AND `id_video` = {$res->id_video}");
        }

    }

    public function getALL()
    {
        $tab_return = [];
        $sth = $this->_db->prepare('SELECT DISTINCT `id` FROM ' . self::$TABLE_NAME);
        $sth->execute();
        While ($res = $sth->fetch(db::FETCH_OBJ)) {
            $playlist = new Playlist($res->id);
            array_push($tab_return, $playlist);
        }

        return $tab_return;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}
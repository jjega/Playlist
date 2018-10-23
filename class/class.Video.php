<?php
/**
 * Created by PhpStorm.
 * User: jonathanega
 * Date: 22/10/2018
 * Time: 20:03
 */


namespace Playlist;

use database\db;

class Video
{
    private $_db;

    public $id;
    public $title;
    public $thumbnail;

    public static $TABLE_NAME = 'video';

    public function __construct($id = null)
    {
        $this->_db = new db();
        if ($id) {

            $sth = $this->_db->prepare('SELECT `id`, `title`, `thumbnail` FROM ' . self::$TABLE_NAME . ' AS pl WHERE pl.id = :id');
            $sth->execute(array('id' => $id));
            $res = $sth->fetch(db::FETCH_OBJ);
            $this->id = intval($res->id);
            $this->title = $res->title;
            $this->thumbnail = $res->thumbnail;
        }
    }

    public function create($title, $thumbnail)
    {
        $sth = $this->_db->prepare('INSERT INTO ' . self::$TABLE_NAME . ' (title, thumbnail) VALUES  (:title, :thumbnail)');
        $sth->execute(array('title' => $title, 'thumbnail' => $thumbnail));

        $id = $this->_db->lastInsertId();
        if ($id) {
            $this->id = $id;
            $this->title = $title;
            $this->thumbnail = $thumbnail;
        }
    }

    public function updateTitle($title)
    {
        $sth = $this->_db->prepare('UPDATE ' . self::$TABLE_NAME . ' SET title = :title');
        $sth->execute(array('title' => $title));
    }

    public function updateThumbnail($thumbnail)
    {
        $sth = $this->_db->prepare('UPDATE ' . self::$TABLE_NAME . ' SET thumbnail = :thumbnail');
        $sth->execute(array('thumbnail' => $thumbnail));
    }

    public function delete()
    {
        $this->_db->exec('DELETE FROM ' . self::$TABLE_NAME . ' WHERE id = {$this->id}');
    }
}
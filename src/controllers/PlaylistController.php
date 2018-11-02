<?php
/**
 * Created by PhpStorm.
 * User: jega
 * Date: 02/11/2018
 * Time: 22:31
 */

namespace App\Controller;


use Playlist\Playlist;

class PlaylistController
{
    private $playlist;
    private $obejctReturn;
    private $data;
    
    
    public function __construct(string $method, Playlist $playlist, $data)
    {
        $this->obejctReturn = new stdClass();
        $this->obejctReturn->error = 0;
        $this->obejctReturn->message = '';
        
        $this->playlist = $playlist;
        $this->data = $data;
        
        $this->{$method . '()'};
        
        $this->showShowJson();
        
    }

    public function update()
    {
        $this->playlist->updateName($this->data['name']);
        if ($this->data['videos']) {
            foreach ($this->data['videos'] as $video_info) {
                $video = new Video($video_info['id']);
                array_push($this->playlist->list_video, $video);
                unset($video);
            }
            $this->playlist->setVideos();
        }

        $this->obejctReturn->playlist = $this->playlist;
    }

    public function create()
    {
        if ($this->data['name']) {
            $this->playlist->create($this->data['name']);
            if ($this->data['videos']) {

                foreach (json_decode($this->data['videos'], true) as $video_info) {
                    $video = new Video();
                    $video->create($video_info['title'], $video_info['thumbnail']);
                    array_push($this->playlist->list_video, $video);
                    unset($video);
                }
                $this->playlist->setVideos();
            }

            $this->obejctReturn->playlist = $this->playlist;
        } else {
            $this->obejctReturn->message = 'Attention le nom est obligatoir';
            $this->obejctReturn->error = 1;
        }
    }

    public function displayPlaylist()
    {
        $this->playlist->getVideos();

        $this->obejctReturn->playlist = $this->playlist;
    }
    
    public function displayAllPlaylist()
    {
        $this->obejctReturn->playlists = $this->playlist->getALL();
    }
    
    public function deletePlaylist()
    {
        $this->playlist->delete();

        $this->obejctReturn->playlist = $this->playlist->getALL();
    }
    
    public function deleteVideo()
    {
        $this->playlist->removeVideo($this->data['video_id']);
        $this->playlist->getVideos();

        $this->obejctReturn->playlist = $this->playlist;
    }
    
    private function showShowJson()
    {
        $json_return = json_encode($this->obejctReturn, JSON_UNESCAPED_SLASHES);
        
        include '../app/displayJson.php';
    }
}
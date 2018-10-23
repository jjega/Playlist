<?php
/**
 * Created by PhpStorm.
 * User: jonathanega
 * Date: 20/10/2018
 * Time: 22:49
 */
require_once './tools/generate_header.php';
require_once './class/class.Playlist.php';

use Playlist\Playlist;
use Playlist\Video;

// clean value
foreach ($_GET as &$val)
{
    $val = html_entity_decode($val);
    $val = strip_tags($val);
}
foreach ($_POST as &$val)
{
    $val = html_entity_decode($val);
    $val = strip_tags($val);
}
foreach ($_POST as &$val)
{
    $val = html_entity_decode($val);
    $val = strip_tags($val);
}

switch ($_GET['type'])
{
    case 'playlist':
        switch ($_GET['mode'])
        {
            case 'update':
                getHeaderRule('PUT');
                $playlist = new Playlist($_GET['id']);
                $data = json_decode(file_get_contents("php://input"));
                    $playlist->updateName($_POST['name']);
                    if ($_POST['videos']) {
                        foreach ($_POST['videos'] as $video_info) {
                            $video = new Video($video_info['id']);
                            array_push($playlist->list_video, $video);
                            unset($video);
                        }
                        $playlist->setVideos();
                    }

                    $message = $playlist;
                break;
            case 'create':
                getHeaderRule('POST');
                $data = file_get_contents("php://stdin");
                $playlist = new Playlist();
                if ($_POST['name']) {
                    $playlist->create($_POST['name']);
                    if ($_POST['videos']) {

                        foreach (json_decode($_POST['videos'], true) as $video_info) {
                            $video = new Video();
                            $video->create($video_info['title'], $video_info['thumbnail']);
                            array_push($playlist->list_video, $video);
                            unset($video);
                        }
                        $playlist->setVideos();
                    }

                    $message = $playlist;
                } else {
                    $message = new stdClass();
                    $message->message = 'Attention le nom est obligatoir';
                    $message->error = 1;
                }

                break;
            case 'get':
                getHeaderRule('GET');
                $playlist = new Playlist($_GET['id']);
                $playlist->getVideos();

                $message = $playlist;
                break;
            case 'all':
                getHeaderRule('GET');
                $playlist = new Playlist();

                $message = $playlist->getALL();
                break;
            case 'delete':
                getHeaderRule('GET');
                $playlist = new Playlist($_GET['id']);
                $playlist->delete();

                $message = $playlist->getALL();
                break;
            case 'deletevideo':
                getHeaderRule('PUT');
                $playlist = new Playlist($_GET['id']);
                $playlist->removeVideo($_POST['video_id']);
                $playlist->getVideos();

                $message = $playlist;
                break;
            default:
                header("HTTP/1.0 404 Not Found");
        }
}

echo json_encode($message, JSON_UNESCAPED_SLASHES);
# Playlist Manager
## Test Subject
Building a playlist api
Indicative estimated duration : 3h00

Introduction

Dailymotion is building a new feature "The playlist"

The feature is simple : The user can create a list of ordered videos.

As a core api developer, you are responsible for building this feature and expose it through API.

Task
The task is to create an api that manages an ordered playlist.
An example of a minimal video model : (You might add extra fields to do this project)

video {
id : the id of the video,
title: the title of the video
thumbnail : The url of the video
...
}

An example of a minimal playlist model : (You might add extra fields to do this project)

playlist {
id : The id of the playlist,
name : The name of the playlist
......
}

The API must support the following use cases:

- Return the list of all videos:

{
"data" : [
{
"id": 1,
"title": "video 1"
....
},
{
"id": 2,
"title": "video 2"
}
....
]
}

- Return the list of all playlists:

{
"data" : [
{
"id": 1,
"name": "playlist 1"
....
},
{
"id": 2,
"name": "playlist 2"
}
….

]
}

- Create a playlist

- Show informations about the playlist

{
"data" : {
"id": 1,
"name": "playlist 1"
}
}

- Update informations about the playlist
- Delete the playlist
- Add a video in a playlist
- Delete a video from a playlist
- Return the list of all videos from a playlist (ordered by position):

{
"data" : [
{
"id": 1,
"title": "video 1 from playlist 2"
....
},
{
"id": 2,
"title": "video 2 from playlist 2"
}
....
]
}

Your goal: Design and build this API.

Important notes :
- Removing videos should re-arrange the order of your playlist and the storage.
- PHP or Python languages are supported
- Using frameworks is forbidden, your code should use native language libraries, except for Python, you could use bottlepy ( https://bottlepy.org/docs/dev/ ).
- Use Mysql for storing your data
- You should provide us the source code (or a link to GitHub) and the instructions to run your code

## Installation
### Requirement
* PHP 7.X
* composer
* create config/config.ini
[DataBase]
db_driver = mysql
db_name = daily_playlist
db_host = localhost
db_user = root
db_passwd = root

### Call
URL are made using this format /[type]/[mode]/[id]
type : for this test is value is 'playlist'
mode : 

##### create : to add a new playlist.
  * URL : /playlist/create
  * Method : POST
  * Format : {name:test1, videos:[{"title":"titre 1","thumbnail":"thumbnail 1"},{"title":"titre 2","thumbnail":"thumbnail 2"},{"title":"titre 3","thumbnail":"thumbnail 3"}
     * name : string
     * video : array
        * title : string
        * thumbnail : string

##### all : to get all playlist without videos
   * URL : /playlist/all
   * Method : GET
   * Return : array
        * id : int
        * name : string
        * nb_video : int
        * list_video : array
           * id : int (if null create a new video)
           * title : string
           * thumbnail : string
        
##### update : to update name and video list order
   * URL : /playlist/update/[id]
   * Method : PUT
   * Format : {name:test1, videos:[{"title":"titre 1","thumbnail":"thumbnail 1"},{"title":"titre 2","thumbnail":"thumbnail 2"},{"title":"titre 3","thumbnail":"thumbnail 3"}
      * name : string
      * video : array
         * id : int (if null create a new video)
         * title : string
         * thumbnail : string

##### delete : to delete a playlist
   * URL : /playlist/delete/[id]
   * Method : GET

##### get : to get a specific playlist
   * URL : /playlist/get/[id]
   * Method : GET
   * Return : 
        * id : int
        * name : string
        * list_video : array
            * id : int
            * title : string
            * thumbnail : string
           
##### deletevideo : delete a specific video in a playlist
   * URL : /playlist/deletevideo/[id]
   * Method : PUT
   * Format :
        * video_id : int
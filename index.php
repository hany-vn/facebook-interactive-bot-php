<?php
/**
 * Author: ID Thiên Ân
 * Facebook: https://www.facebook.com/idthienan
 * Github: https://github.com/todivn
 */

 /** Set timezone Ho_Chi_Minh */
 date_default_timezone_set('Asia/Ho_Chi_Minh');

 require __DIR__.'/class/Facebook.php';
 use TodiVN\Class\Facebook;

 $Facebook = new Facebook;

 /** Cookie facebok */
 $Facebook->cookie = '';

 /** List reactions random */
 $Facebook->reactions = [
    'care'
 ];
 
 /** List comments random */
 $Facebook->comments = [
    'Xin chào. Chúc 1 ngày tốt lành!! cho mình xin 1 like bài viết đầu tiên ở trang cá nhân nha hihi <3',
    'Cho mình xin lại 1 tym ở bài viết đầu nha :3',
    '#TT chéo đi <3',
    'Mình like bài cho bạn rồi, bạn like lại cho mình nha <3'
 ];

 /** Limit scan post */
 $Facebook->limitPost = 10;

 /** Get new post */
 $posts = $Facebook->getNewPosts();

 if( gettype($posts) == 'array' && count($posts) > 0 ) {
    /**
     * Loop posts
    */
    foreach($posts as $post) {

        /** Like post */
        $reaction = $Facebook->Reaction($post);
        if( gettype($reaction) == 'array' && count($reaction) > 0 ) {
            echo json_encode($reaction, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        echo '<br />';

        /** Comment post */
        $comment = $Facebook->Comment($post);
        if( gettype($comment) == 'array' && count($comment) > 0 ) {
            echo json_encode($comment, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        echo '<hr />';
        
    }
 }

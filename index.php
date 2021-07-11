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
 $Posts = $Facebook->getNewPosts();

 if( gettype($Posts) == 'array' && count($Posts) > 0 ) {
    /**
     * Loop posts
    */
    foreach($Posts as $Post) {

        /** Like post */
        $Reaction = $Facebook->Reaction($Post);
        if( gettype($Reaction) == 'array' && count($Reaction) > 0 ) {
            echo json_encode($Reaction, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        echo '<br />';

        /** Comment post */
        $Comment = $Facebook->Comment($Post);
        if( gettype($Comment) == 'array' && count($Comment) > 0 ) {
            echo json_encode($Comment, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        echo '<hr />';
        
    }
 }
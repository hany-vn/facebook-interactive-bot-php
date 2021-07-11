<?php
/**
 * Author: ID Thiên Ân
 * Facebook: https://www.facebook.com/idthienan
 * Github: https://github.com/todivn
 */

 namespace TodiVN\Class;
 require __DIR__.'/../vendor/autoload.php';

 use GuzzleHttp\Client;

 class Facebook {

    /**
     * Cookie facebook
     *
     * @var string
     */
    public $cookie;

    /**
     * Reaction post
     *
     * @var array
     */
    public $reactions;

    /**
     * Comment post
     *
     * @var array
     */
    public $comments;

    /**
     * Limit scan post
     *
     * @var integer
     */
    public $limitPost = 1;

    /**
     * Uid user
     *
     * @var integer
     */
    public $UID;

    /**
     * Input value fb_dtsg
     *
     * @var string
     */
    public $DTSG;

    public function __construct()
    {
        //
    }

    /**
     * Get new post
     *
     * @return array
     */
    public function getNewPosts()
    {
        /**
         * Request to home page facebook
         */
        $client = new Client (
            [
                'headers' => [
                    'Cookie' => $this->cookie
                ]
            ]
        );
        $res = $client->request('GET', 'https://mbasic.facebook.com/home.php?sk=h_chr');

        /**
         * Get UID User
         */
        $this->getUID($res->getBody());

        /**
         * Get DTSG
         */
        $this->getDTSG($res->getBody());

        /**
         * Get id posts
         */
        if (preg_match_all('#ft_ent_identifier=(.+?)&#is', $res->getBody(), $Posts)) {
            
            /**
             * Limit scan post
             */
            if( count($Posts[1]) > $this->limitPost ) {

                $newPosts = [];

                for( $i = 0; $i < $this->limitPost; $i ++ )  {
                    $newPosts[$i] = $Posts[1][$i];
                }

                /**
                 * Return id posts
                 */
                return $newPosts;

            } else {
                
                /**
                 * Return id posts
                 */
                return $Posts[1];
                
            }

        }
    }

    /**
     * Like post
     *
     * @param integer $id
     * @param string $reactionCustom
     * @return void
     */
    public function Reaction($id, $reactionCustom = '')
    {
        /**
         * Request to reaction post page facebook
         */
        $client = new Client (
            [
                'headers' => [
                    'Cookie' => $this->cookie
                ]
            ]
        );
        $res = $client->request('GET', 'https://mbasic.facebook.com/reactions/picker/?ft_id='.$id.'&av='.$this->UID);
        
        if( empty($reactionCustom) ) {
            
            $reaction = $this->getRandomArray( $this->reactions );

            if($reaction) {

                $reactionSelect = $this->formatReaction($reaction);
                $reactionID = $this->getReactionID($reaction);
    
                /** Sent request reaction post */
                $resReaction = $client->request('POST', 'https://mbasic.facebook.com'.$this->getListReaction($res->getBody())[$reactionSelect], [
                    'form_params' => [
                        'fb_dtsg' => $this->DTSG,
                        'reaction_type' => $reactionID
                    ]
                ]);    
                
                $data = [
                    'status' => 200,
                    'message' => 'Reaction bài viết thành công',
                    'id' => $id,
                    'reaction' => $reaction,
                    'time' => date('Y/m/d H:i:s')
                ];

                $this->saveLog('log/reaction.txt', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");
                $this->saveLog('log/all.txt', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");

                return $data;

            }

        } else {
            $reaction = $reactionCustom;

            if($reaction) {

                $reactionSelect = $this->formatReaction($reaction);
                $reactionID = $this->getReactionID($reaction);
    
                /** Sent request reaction post */
                $resReaction = $client->request('POST', 'https://mbasic.facebook.com'.$this->getListReaction($res->getBody())[$reactionSelect], [
                    'form_params' => [
                        'fb_dtsg' => $this->DTSG,
                        'reaction_type' => $reactionID
                    ]
                ]);    

                $data = [
                    'status' => 200,
                    'message' => 'Reaction bài viết thành công',
                    'id' => $id,
                    'reaction' => $reaction,
                    'time' => date('Y/m/d H:i:s')
                ];
                
                $this->saveLog('log/reaction.txt', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");
                $this->saveLog('log/all.txt', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");

                return $data;

            }
        }
    }

    /**
     * Comment post
     *
     * @param integer $id
     * @param string $commentCustom
     * @return void
     */
    public function Comment($id, $commentCustom = '')
    {
        if( empty($commentCustom) ) {
            
            $comment = $this->getRandomArray( $this->comments );

            if($comment) {
                /** Sent request comment post */
                $client = new Client (
                    [
                        'headers' => [
                            'Cookie' => $this->cookie
                        ]
                    ]
                );
                $res = $client->request('POST', 'https://mbasic.facebook.com/a/comment.php?ft_ent_identifier='.$id.'&av='.$this->UID, [
                    'form_params' => [
                        'fb_dtsg' => $this->DTSG,
                        'comment_text' => $comment
                    ]
                ]);

                $data = [
                    'status' => 200,
                    'message' => 'Comment bài viết thành công',
                    'id' => $id,
                    'comment' => $comment,
                    'time' => date('Y/m/d H:i:s')
                ];
                
                $this->saveLog('log/comment.txt', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");
                $this->saveLog('log/all.txt', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");

                return $data;
            }

        } else {

            $comment = $comment;

            /** Sent request comment post */
            $client = new Client (
                [
                    'headers' => [
                        'Cookie' => $this->cookie
                    ]
                ]
            );
            $res = $client->request('POST', 'https://mbasic.facebook.com/a/comment.php?ft_ent_identifier='.$id.'&av='.$this->UID, [
                'form_params' => [
                    'fb_dtsg' => $this->DTSG,
                    'comment_text' => $comment
                ]
            ]);

            $data = [
                'status' => 200,
                'message' => 'Comment bài viết thành công',
                'id' => $id,
                'comment' => $comment,
                'time' => date('Y/m/d H:i:s')
            ];
            
            $this->saveLog('log/comment.txt', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");
            $this->saveLog('log/all.txt', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");

            return $data;
            
        }
    }

    /**
     * Get random array
     *
     * @param array $array
     * @return mixed
     */
    protected function getRandomArray($array)
    {
        if( count($array) > 0 ) {

            $random = rand(0, count($array) - 1);
            return $array[$random];

        } else {

            return false;

        }
    }

    /**
     * Get UID User
     *
     * @param string $content
     * @return integer
     */
    protected function getUID($content)
    {
        preg_match('#target" value="(.+?)"#is', $content, $uid);
        return $this->UID = $uid[1];
    }

    /**
     * Get fb_dtsg input value
     *
     * @param string $content
     * @return string
     */
    protected function getDTSG($content)
    {
        preg_match('#fb_dtsg" value="(.+?)"#is', $content, $dtsg);
        return $this->DTSG = $dtsg[1];
    }

    /**
     * Get list reaction
     *
     * @param string $content
     * @return array
     */
    protected function getListReaction($content)
    {
        preg_match_all('#a href="(.+?)"#is', $content, $reaction);
        return $reaction[1];
    }

    /**
     * Format reaction name to id
     *
     * @param string $name
     * @return integer
     */
    protected function formatReaction($name)
    {

        if($name == 'like') {
            return 0;
        }

        if($name == 'love') {
            return 1;
        }

        if($name == 'care') {
            return 2;
        }

        if($name == 'haha') {
            return 3;
        }

        if($name == 'wow') {
            return 4;
        }

        if($name == 'sad') {
            return 5;
        }

        if($name == 'angry') {
            return 6;
        }

    }

    /**
     * Get ID reaction by name
     *
     * @param string $name
     * @return integer
     */
    protected function getReactionID($name)
    {

        if($name == 'like') {
            return 1;
        }

        if($name == 'love') {
            return 2;
        }

        if($name == 'care') {
            return 16;
        }

        if($name == 'haha') {
            return 4;
        }

        if($name == 'wow') {
            return 3;
        }

        if($name == 'sad') {
            return 7;
        }

        if($name == 'angry') {
            return 8;
        }

    }

    /**
     * Save log
     *
     * @param string $file
     * @param string $data
     * @return void
     */
    protected function saveLog($file, $data)
    {
        $myFile = fopen($file, "a+") or die("Unable to open file!");
        fwrite($myFile, $data);
        fclose($myFile);
    }

 }
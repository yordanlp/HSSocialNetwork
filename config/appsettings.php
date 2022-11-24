<?php

return [

    'navigation' => [
        'normal_user' => [
            [
                'title' => 'Feed',
                'route' => 'feed.index',
            ],
            [
                'title' => 'Profile',
                'route' => "user.show",
            ],
            [
                'title' => 'Find People',
                'route' => 'user.index',
            ],
            [
                'title' => 'Create Post',
                'route' => 'post.create',
            ],
        ],
        'admin' => [
            [
                'title' => 'Users',
                'route' => 'admin.users'
            ],
            [
                'title' => 'Posts',
                'route' => 'admin.posts'
            ]
        ]
    ],

];

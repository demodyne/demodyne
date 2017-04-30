<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

/**
 * Global variables for DEMODYNE application
 */
return [
    'demodyne' => [
//        'server' => 'www.demodyne.org',
        'account' => [
            'type' => [
                'guest' => 1,
                'member' => 2,
                'partner' => 3,
                'administration' => 4,
                'admin' => 5,
            ],
            'admin-level' => [
                'country' => 3,
                'region' => 2,
                'city' => 1
            ]
        ],
        'level' => [
            'none' => 0,
            'country' => 3,
            'region' => 2,
            'city' => 1
        ],
        'proposal' => [
            'status' => [
                'unvalidated' => -1,
                'draft' => 0,
                'debate'=> 1,
                'vote' => 2,
                'plan' => 3,
                'execute' => 4,
                'followup' => 5
            ],
            'level' => [
                'city' => 1,
                'region' => 2,
                'country' => 3
            ]
        ],
        'news' => [
            'type' => [
                'none' => 0,
                'new_proposal' => 1,
                'implementation_phase' => 2,
                'completed_proposal' => 3,
                'deleted_proposal' => 4,
                'new_measure' => 5, 
                'new_measure_citizen' => 6, 
                'measure_validated' => 7, 
                'task_suggestion' => 8,
                'new_public_event' => 9,
                'new_public_session' => 10
            ],
        ],
        'email' =>[
            'digest' => [
                'daily' => 1,
                'weekly' => 2,
                'monthly' => 3,
                'never' => 0,
            ],
            'alert' => [
                'instant'   => 1,
                'daily'     => 2,
                'weekly'    => 3,
                'never'     => 0,
            ],
            // @todo add instant
            'remainder' => [
                '2 days before' => 0,
                '5 days before' => 1
            ]
        ],
        'blog' => [
            'tag' => [
                'all'       => 0,
                'features'  => 1,
                'news'      => 2,
            ]
        ],
        'inbox' => [
            'type' => [
                'unread' => -1,
                'none' => 0,
                'new_comment' => 1,
                'new_step' => 2,
                'champion_news' => 3,
                'private_message' => 4,
                'newsletter' => 5,
                'invitation' => 6,
            ],
        ],
        'registration' => [
            'user' => [
                'allow' => true,
            ],
            'partner' => [
                'allow' => false,
            ],
            'administration' => [
                'allow' => true,
            ],
        ],
        'partner' => [
            'employees' => [
                1 => "1-10",
                2 => "11-50",
                3 => "51-500",
                4 => ">500",
            ],
            'gain' => [
                1 => "<200k&euro;",
                 2 => "200k&euro;-2M&euro;",
                 3 => "2M&euro;-10M&euro;",
                 4 => ">10M&euro;",
            ]
        ],
        'vote' => [
            'average' => 2
        ],
        'priority' => [
            1 => 50,
            2 => 30,
            3 => 20,
            4 => 15,
            5 => 10,
            6 => 8,
            7 => 6,
            8 => 4,
            9 => 3,
            10 => 2,
            11 => 1
        ]
    ]
];

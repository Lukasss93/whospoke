<?php

return [
    'name' => 'Who spoke?',
    'create' => [
        'guest' => 'To create a session, you must first log in.',
        'label' => 'Create a session',
        'limit' => 'You cannot create new sessions, you have reached the maximum limit.',
        'recreate' => 'Delete an existing session and create a new one.',
    ],
    'join' => [
        'info' => 'Join a session.'
    ],
    'developed' => 'Developed by',
    'welcome' => 'Welcome, :name!',
    'logout' => 'Logout',
    'room' => [
        'title' => [
            'title' => 'Enter the title of the session',
            'placeholder' => 'Session title',
            'info' => 'Optional, the name of the application will be displayed',
        ],
        'code' => [
            'title' => 'Enter the code of the session',
            'placeholder' => 'Session code',
            'info' => 'It will be used to generate a direct link to the session',
        ],
        'members' => [
            'title' => 'Session members',
            'placeholder' => 'Member name',
        ],
        'type' => [
            'title' => 'Select the type of the session',
            'status' => 'Status',
            'counter' => 'Counter',
        ],
        'welcome' => 'Welcome to the session:',
        'info' => 'Members who spoke: :current out of :total',
        'owner' => 'You are the session owner',
        'editor' => 'You are the session moderator',
        'reset' => 'Reset',
        'online' => ':count user is watching this session|:count users are watching this session',
        'link' => [
            'copy' => 'Click to copy the url',
            'copied' => 'Link copied to clipboard!',
        ],
        'delete' => [
            'question' => 'Are you sure you want to delete this session?',
            'success' => 'Session deleted!',
        ],
        'completed' => 'Everyone spoke!',
        'professions' => [
            'show' => 'Show professions',
            'hide' => 'Hide professions',
        ],
    ],
    'your_rooms' => 'Your sessions',
    'error' => 'Error',
    'minutes' => 'minutes',
    'seconds' => 'seconds',
    'member' => [
        'status' => [
            'default' => [
                'title' => 'Default',
                'set' => 'Set as default',
                'all' => 'Set all as online',
            ],
            'offline' => [
                'title' => 'Offline',
                'set' => 'Set as offline',
            ],
            'guest' => [
                'title' => 'Guest',
                'set' => 'Set as guest',
            ],
            'pending' => [
                'title' => 'Pending',
                'set' => 'Set as pending',
                'all' => 'Set all as pending',
            ],
            'except' => 'Except offline or guest members',
        ],
        'canEdit' => 'Can edit this session',
        'edit' => 'Edit member',
    ],
    'show_advanced' => 'Advanced controls',
    'member_user' => [
        'detach' => 'Remove association',
        'empty' => 'No user found',
        'title' => 'Associate a user to :name',
    ],
    'sorting' => [
        'name' => 'Name',
        'time' => 'Time',
        'speech' => 'Speech',
        'profession' => 'Profession',
    ],
    'widget' => [
        'session' => 'Session',
        'counter' => 'How many members spoke',
        'time' => 'Time elapsed',
        'available' => 'Next available member',
        'sort' => 'Members sorting',
    ],
    'theme' => [
        'auto' => 'Auto',
        'light' => 'Light',
        'dark' => 'Dark',
    ],
    'time' => [
        'reset' => 'Reset',
        'play' => 'Play',
        'stop' => 'Stop',
    ],
    'login' => [
        'slack' => 'Login with Slack',
    ],
    'or' => 'Or',
];

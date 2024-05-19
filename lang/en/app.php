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
        'info' => 'To join a session, open a direct link to the session.'
    ],
    'developed' => 'Developed by',
    'welcome' => 'Welcome, :name!',
    'logout' => 'Logout',
    'room' => [
        'code' => [
            'title' => 'Enter the code of the session you want to create',
            'placeholder' => 'Session code',
            'info' => 'It will be used to generate a direct link to the session',
        ],
        'members' => [
            'title' => 'Session members',
            'placeholder' => 'Member name',
        ],
        'welcome' => 'Welcome to the session:',
        'info' => 'Here you can see the status of the members who spoke.',
        'live' => 'Data is updated in :live by the session owner.',
        'owner' => 'As the owner, you can modify the status of the session.',
        'reset' => 'Reset',
        'online' => ':count user online|:count users online',
        'link' => [
            'copy' => 'Click to copy the url',
            'copied' => 'Link copied to clipboard!',
        ],
    ],
    'your_rooms' => 'Your sessions',
    'live' => 'real time',
    'error' => 'Error',
    'minutes' => 'minutes',
    'seconds' => 'seconds',
];
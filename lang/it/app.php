<?php

return [
    'name' => 'Chi ha parlato?',
    'create' => [
        'guest' => 'Per creare una sessione, devi prima eseguire il login.',
        'label' => 'Crea una sessione',
        'limit' => 'Non puoi creare nuove sessioni, hai raggiunto il limite massimo.',
        'recreate' => 'Cancella una sessione esistente e ricreane una nuova.',
    ],
    'join' => [
        'info' => 'Per unirti in una sessione, apri un link diretto alla sessione.'
    ],
    'developed' => 'Sviluppato da',
    'welcome' => 'Benvenuto, :name!',
    'logout' => 'Esci',
    'room' => [
        'code' => [
            'title' => 'Inserisci il codice della sessione che vuoi creare',
            'placeholder' => 'Codice sessione',
            'info' => 'Verrà usato per generare un link diretto alla sessione',
        ],
        'members' => [
            'title' => 'Membri della sessione',
            'placeholder' => 'Nome membro',
        ],
        'welcome' => 'Benvenuto nella sessione:',
        'info' => 'Qui puoi vedere lo stato dei membri che hanno parlato.',
        'live' => 'I dati vengono aggiornati in :live dal proprietario della sessione.',
        'owner' => 'Come proprietario, puoi modificare lo stato della sessione.',
        'reset' => 'Reset',
        'online' => ':count utente online|:count utenti online',
        'link' => [
            'copy' => 'Clicca per copiare l\'url',
            'copied' => 'Link copiato negli appunti!',
        ],
    ],
    'your_rooms' => 'Le tue sessioni',
    'live' => 'tempo reale',
    'error' => 'Errore',
    'minutes' => 'minuti',
    'seconds' => 'secondi',
    'member' => [
        'status' => [
            'offline' => 'Offline',
            'set' => 'Imposta offline',
            'unset' => 'Imposta online',
        ],
    ],
];

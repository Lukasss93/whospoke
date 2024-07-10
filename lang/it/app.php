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
    'logout' => 'Logout',
    'room' => [
        'title' => [
            'title' => 'Inserisci il titolo della sessione',
            'placeholder' => 'Titolo sessione',
            'info' => 'Opzionale. Se vuoto, verrà visualizzato il nome dell\'applicazione',
        ],
        'code' => [
            'title' => 'Inserisci il codice della sessione',
            'placeholder' => 'Codice sessione',
            'info' => 'Verrà usato per generare un link diretto alla sessione',
        ],
        'members' => [
            'title' => 'Membri della sessione',
            'placeholder' => 'Nome membro',
        ],
        'type' => [
            'title' => 'Seleziona il tipo di sessione',
            'status' => 'Stato',
            'counter' => 'Contatore',
        ],
        'welcome' => 'Benvenuto nella sessione:',
        'info' => 'Membri che hanno parlato: :current su :total',
        'owner' => 'Sei il proprietario della sessione',
        'editor' => 'Sei il moderatore della sessione',
        'reset' => 'Reset',
        'online' => ':count utente sta guardando questa sessione|:count utenti stanno guardando questa sessione',
        'link' => [
            'copy' => 'Clicca per copiare l\'url',
            'copied' => 'Link copiato negli appunti!',
        ],
        'delete' => [
            'question' => 'Sei sicuro di voler cancellare questa sessione?',
            'success' => 'Sessione cancellata!',
        ],
        'completed' => 'Hanno parlato tutti!',
    ],
    'your_rooms' => 'Le tue sessioni',
    'error' => 'Errore',
    'minutes' => 'minuti',
    'seconds' => 'secondi',
    'member' => [
        'status' => [
            'default' => [
                'title' => 'Default',
                'set' => 'Imposta come default',
                'all' => 'Imposta tutti online',
            ],
            'offline' => [
                'title' => 'Offline',
                'set' => 'Imposta come offline',
            ],
            'guest' => [
                'title' => 'Guest',
                'set' => 'Imposta come guest',
            ],
            'pending' => [
                'title' => 'In attesa',
                'set' => 'Imposta come in attesa',
                'all' => 'Imposta tutti in attesa',
            ],
            'except' => 'Eccetto i membri offline o guest',
        ],
        'canEdit' => 'Può modificare questa sessione',
    ],
    'show_advanced' => 'Controlli avanzati',
    'member_user' => [
        'detach' => 'Rimuovi associazione',
        'empty' => 'Nessun utente trovato',
        'title' => 'Associa un utente a :name',
    ],
    'sorting' => [
        'name' => 'Nome',
        'time' => 'Tempo',
        'speech' => 'Intervento',
    ],
    'widget' => [
        'session' => 'Sessione',
        'counter' => 'Quanti membri hanno parlato',
        'time' => 'Tempo trascorso',
        'available' => 'Prossimo membro disponibile',
        'sort' => 'Ordinamento membri',
    ],
    'theme' => [
        'auto' => 'Auto',
        'light' => 'Chiaro',
        'dark' => 'Scuro',
    ],
    'time' => [
        'reset' => 'Reset',
        'play' => 'Avvia',
        'stop' => 'Ferma',
    ],
    'login' => [
        'slack' => 'Accedi con Slack',
    ],
];

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
        'live' => 'I dati vengono aggiornati in :live dal proprietario della sessione.',
        'owner' => 'Come proprietario, puoi modificare lo stato della sessione.',
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
    'show_as_owner' => 'Mostra come proprietario',
    'show_as_guest' => 'Mostra come ospite',
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
];

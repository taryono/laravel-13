<?php 
return [

    [
        'title'=>'Dashboard',
        'icon'=>'dashboard',
        'route'=>'dashboard',
    ],

    [

        'title'=>'Certification',

        'icon'=>'verified',

        'children'=>[

            [

                'title'=>'Overview',
                'route'=>'certification.index'
            ],

            [
                'title'=>'Schemes',
                'children'=>[

                    [
                        'title'=>'CPOF',
                        'route'=>'cpof.index'
                    ],

                    [

                        'title'=>'CPSP',
                        'route'=>'cpsp.index'
                    ]

                ]

            ]

        ]

    ]

];
<?php

namespace Tests\App;

class DataUsers
{
    public const DEFAULT_EXPECTED = [
        'name' => '',
        'investor_status' => 'not_verified',
        'show_investor_status' => 0,
        'advertiser_status' => 'not_verified',
        'show_advertiser_status' => 0,
        'real_estate_broker_status' => 'not_verified',
        'show_real_estate_broker_status' => 0,
        'check_status' => 'not_verified',
        'show_check_status' => 0,
    ];

    public const SKIP = ['created_at',
        'updated_at',
        'deletable',
    ];

    public static function usersList()
    {
        $collection = collect(self::users());

        $emails = $collection->flatMap(function ($items) {
            return collect($items)->pluck('kontakt.email')->filter();
        });

        return $emails;
    }

    public static function users()
    {
        return [
            'superadmin' => [
                [
                    'kontakt' => [
                        'email' => 'superadmin@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => false,
                        'advertiser' => false,
                        'realEstateBroker' => false,
                    ]
                ],
                [
                    'id' => '1',
                    'email' => 'superadmin@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 1,
                    'owner' => 1,
                    'advisor' => 0,
                    'investor' => 0,
                    'advertiser' => 0,
                    'real_estate_broker' => 0,
                ]
            ],
            'admin' => [
                [
                    'kontakt' => [
                        'email' => 'admin@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => false,
                        'advertiser' => false,
                        'realEstateBroker' => false,
                    ],
                    'advanced' => [
                        'superadmin' => 1
                    ],
                ],
                [
                    'id' => '2',
                    'email' => 'admin@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 1,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 0,
                    'advertiser' => 0,
                    'real_estate_broker' => 0,
                ]
            ],
            'advisor' => [
                [
                    'kontakt' => [
                        'email' => 'advisor@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => false,
                        'advertiser' => false,
                        'realEstateBroker' => false,
                    ],
                    'advanced' => [
                        'advisor' => 1
                    ],
                ],
                [
                    'id' => '3',
                    'email' => 'advisor@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 1,
                    'investor' => 0,
                    'advertiser' => 0,
                    'real_estate_broker' => 0,
                ]
            ],
            'investor' => [
                [
                    'kontakt' => [
                        'email' => 'investor@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => true,
                        'advertiser' => false,
                        'realEstateBroker' => false,
                    ],
                ],
                [
                    'id' => '4',
                    'email' => 'investor@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 1,
                    'advertiser' => 0,
                    'real_estate_broker' => 0,
                ]
            ],
            'advertiser' => [
                [
                    'kontakt' => [
                        'email' => 'advertiser@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => false,
                        'advertiser' => true,
                        'realEstateBroker' => false,
                    ],
                ],
                [
                    'id' => '5',
                    'email' => 'advertiser@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 0,
                    'advertiser' => 1,
                    'real_estate_broker' => 0,
                ]
            ],
            'real_estate_broker' => [
                [
                    'kontakt' => [
                        'email' => 'real_estate_broker@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => false,
                        'advertiser' => false,
                        'realEstateBroker' => true,
                    ],
                ],
                [
                    'id' => '6',
                    'email' => 'real_estate_broker@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 0,
                    'advertiser' => 0,
                    'real_estate_broker' => 1,
                ]
            ],
            'investor-advertiser' => [
                [
                    'kontakt' => [
                        'email' => 'investor-advertiser@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => true,
                        'advertiser' => true,
                        'realEstateBroker' => false,
                    ],
                ],
                [
                    'id' => '7',
                    'email' => 'investor-advertiser@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 1,
                    'advertiser' => 1,
                    'real_estate_broker' => 0,
                ]
            ],
            'investor-real_estate_broker' => [
                [
                    'kontakt' => [
                        'email' => 'investor-real_estate_broker@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => true,
                        'advertiser' => false,
                        'realEstateBroker' => true,
                    ],
                ],
                [
                    'id' => '8',
                    'email' => 'investor-real_estate_broker@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 1,
                    'advertiser' => 0,
                    'real_estate_broker' => 1,
                ]
            ],
            'advertiser-real_estate_broker' => [
                [
                    'kontakt' => [
                        'email' => 'advertiser-real_estate_broker@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => false,
                        'advertiser' => true,
                        'realEstateBroker' => true,
                    ],
                ],
                [
                    'id' => '9',
                    'email' => 'advertiser-real_estate_broker@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 0,
                    'advertiser' => 1,
                    'real_estate_broker' => 1,
                ]
            ],
            'investor-advertiser-real_estate_broker' => [
                [
                    'kontakt' => [
                        'email' => 'investor-advertiser-real_estate_broker@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => true,
                        'advertiser' => true,
                        'realEstateBroker' => true,
                    ],
                ],
                [
                    'id' => '10',
                    'email' => 'investor-advertiser-real_estate_broker@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 1,
                    'advertiser' => 1,
                    'real_estate_broker' => 1,
                ]
            ],
            'nobody' => [
                [
                    'kontakt' => [
                        'email' => 'nobody@example.com',
                        'phone_number' => '123456789',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ],
                    'userType' => [
                        'investor' => false,
                        'advertiser' => false,
                        'realEstateBroker' => false,
                    ],
                ],
                [
                    'id' => '11',
                    'email' => 'nobody@example.com',
                    'phone_number' => '123456789',
                    'superadmin' => 0,
                    'owner' => 0,
                    'advisor' => 0,
                    'investor' => 0,
                    'advertiser' => 0,
                    'real_estate_broker' => 0,
                ]
            ]
        ];
    }
}

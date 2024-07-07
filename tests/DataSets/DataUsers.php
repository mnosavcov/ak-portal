<?php

namespace Tests\DataSets;

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
                    'id' => '2',
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
                    'id' => '3',
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
                    'id' => '4',
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
                    'id' => '5',
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
                    'id' => '6',
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
                    'id' => '7',
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
                    'id' => '8',
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
        ];
    }
}

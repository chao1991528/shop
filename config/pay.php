<?php
return [
    'alipay' => [
        'app_id'         => '2016091400507242',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAv4LR/G8yrbl6fLHnLGmnxoXqTY6ailfE4UIQQHC5QKgVpyGXvoqp+LwfYu1zBWuXgFklbyaHnKbb1HqOYnZHdn0Zufj0/gkpXOUmVKsc5YUJxntGaYDG9xb0HbOmRr9o3DbHqNyQQ59+l+a4UAfcibwKx2iG3bgpApQWdFnYCt2wSuAFyiCC2v4CtvzU9cLcoeYMrZHGoqdJMpoBoIxpZqg05gNUYYiaWeNLJ7ekON14UZti2BpAFDQ1r2PA6V34m+eRKV82I19c/jOO96oSrPmDa4X35VAXqBKVA7YeAX2PHuES32harpMnRtsxCS9BfWOWuZ0Jx21xCUyPaX4xaQIDAQAB',
        'private_key'    => 'MIIEowIBAAKCAQEA2xguMuanV0kgHgXqcTpyw/6yQfxBugZJazyDqleBNJ2f0VQnsRAg/gsQD85hipHLwpvx6APA9AryKgE2fDkcb4RX80XZjpKxQU6amnAXBRJAUVpdTlxeMpxYvVQo1qc5TKPp8U2DjNh1c8GGQn7lK6Bak8RVDiwmqzcQ+y/h/9DGSICujEEmATewCk44m56iJYMMKgtDSbWdTnOHOxbFlG41ygU+8VxCWJoe+si8MwKKP8jZRNnwkEGbhO1RDY+lBoIA2M2xgynrTdjdq0mrSHySQ7Tp7ayVErMGh6LKDTgwYK0c1rGK62zNf7SIVNpyr7PM5eYIUMOJP+cmLXaSiwIDAQABAoIBAHMRfBIXugPJ0Ch0ivD09ZmihxTI2+xLqPu9SJmKY72ym2FhhYclJW7HeKQUjH4unImVRbPJZOnHZfBRur/7bsfTBi4qnVyYBqh6FBDQlgZ/02/mueKR+Jc0LhchwGEcaqep3xBw2Yp7lRQ6q7z40HvdODUrhFBxN83smeqS92tWzf1zKIGBBQ1fmnXoUdiLbL0PMYnFyvL4dtjq2xvPOnQM8YnuMdiG59lgZavlt9pfxGYeKP0K/A0zCb7QSzi/bCmDXrYn4x72Sz83UofMO0AL40EvRe5JDxTM7iztYq4t8qhLj3F34cQCR5fIcNH3PP2bPhZeHC06akqHGB3u76ECgYEA+JNaEvhXTQ372D73fUk31y2G8u0xl546XmwNd8lx1NdjZ6Pp3pcU4IsvXnZ5gogXEyZDJFGpEnCULR02ZCRCFnGxuAu3NVkg6RHQgoEW25dIDrT9BL8AFCFzOXwmEV/WFDq+AOfceAAwc3FvMtyv7u72n/925eG1lRR74gI6CbcCgYEA4aNpQ2S36F9mgYH0SlML7SXjxf1WtH/9t1y39lhAAtT3P2LjcqD1p9UH/RYAHxmfdnX/GBVYITV9uEps3LgyD6FmwkoUIBD5nImK22T+dUeJ/cNbd9bFCZIq7IlVZQXqPYp5F5yMZmYLRFpAoPyz2V4V87z2ALO6ct5Teet1jc0CgYBwd5sFgj9dqboM+VBY60vMm9i3CQKrKvgVyKW/UPGj72AWgF9MSuczQUZJYylPGev2yhUO1bO9mBoy30jAvnb4WLCkKQjXpl/xlBHXOjjxWd1Mai8gepq6gPf1Rmqi6c2ekYVuO3nRAaTqVqSNSrR/UrdbZOaS1DCoJwB2HbPMuQKBgE03uyJRKY7gdxgC/TMiUIaL3PM4y59h3dYOaPZpJR7S+vo6QVRNOUSCZwV5rfj2r9e+cdowE8TppWiYL8EpWBh5R81dxJ1kscecWmTSnzJLlTG+1WKhoTSxTPc92HeGGq+M3/vwmyu/6EMwD8YINRRCWojbNeTvnPMKms6ygbxRAoGBANRiDYtk8shXa0LkTblx1Hq2kP0kRBuVsZDkplStSXTxZ6VcSAwG3CwhVjiWyUEi6cILuUBjp6EKu2ah1f4NvTapzD0/tHLrRi+P7GM0iwE6BUGSwH7J5tQNEsN6pQ01AuVw4TDM2b6SCl9yWW87j8FSk2h//g1SmTa8zVa5eUxD',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
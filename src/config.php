<?php
    return [
        'image-resizer' => [
            'widths' => [
                'small' => 400,
                'medium' => 800,
                'large' => 1024,
                'xlarge' => 1600 
            ],
            'paths' => [
                'source' => 'source/_images',
                'new' => 'source/assets/images',
            ],
            'directory' => [
                'permissions' => 0755,
                'recursive' => true
            ]
        ]
    ];
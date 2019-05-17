<?php

return [
    'student-nav-bar' => [
        'HOME' => 'student.home.index',
        'AUTHORS' => 'student.author.index',
        'EXPLORE' => 'student.explore.index',
        'CONTACT' => 'student.contact.index',
    ],

    'admin' => [
        'Home' => [
            'Dashboard' => [
                'route' => 'admin.dashboard.index',
                'icon' => 'fa fa-home'
            ]
        ],
        'Accounts Management' => [
            'Admins Account' => [
                'route' => 'admin.dashboard.accounts.admins-account',
                'icon' => 'fa fa-user-secret'
            ],
            'Librarian Account' => [
                'route' => 'admin.dashboard.accounts.librarian-account',
                'icon' => 'fa fa-book'
            ],
            'Students Account' => [
                'route' => 'admin.dashboard.accounts.students-account',
                'icon' => 'fa fa-users'
            ],
        ],
        'Books' => [
            'View Books' => [
                'route' => 'admin.books.index',
                'icon' => 'fa fa-book'
            ],
            'Borrowed' => [
                'route' => 'admin.books.borrowed',
                'icon' => 'fa fa-check'
            ],
        ],
        'Course' => [
            'View Courses' => [
                'route' => 'admin.course.index',
                'icon' => 'fa fa-table'
            ],
        ],
        'Author' => [
            'View Authors' => [
                'route' => 'admin.author.index',
                'icon' => 'fa fa-table'
            ],
        ],
        'Department' => [
            'View Departments' => [
                'route' => 'admin.department.index',
                'icon' => 'fa fa-table'
            ],
        ],
        'Audit Logs' => [
            'View Admin Audit' => [
                'route' => 'admin.dashboard.accounts.admin-audit',
                'icon' => 'fa fa-table'
            ],
            'View Student Audit' => [
                'route' => 'admin.dashboard.accounts.student-audit',
                'icon' => 'fa fa-table'
            ],
        ],
        'Reports' => [
            'View Reports' => [
                'route' => 'admin.reports.index',
                'icon' => 'fa fa-table'
            ],
        ],
    ],
    
    'librarian' => [
        'Home' => [
            'Dashboard' => [
                'route' => 'admin.dashboard.index',
                'icon' => 'fa fa-home'
            ]
        ],
        'Books' => [
            'View Books' => [
                'route' => 'admin.books.index',
                'icon' => 'fa fa-book'
            ],
            'Borrowed' => [
                'route' => 'admin.books.borrowed',
                'icon' => 'fa fa-check'
            ],
        ],
        'Author' => [
            'View Authors' => [
                'route' => 'admin.author.index',
                'icon' => 'fa fa-table'
            ],
        ],
        'Audit Logs' => [
            'View Student Audit' => [
                'route' => 'admin.dashboard.accounts.student-audit',
                'icon' => 'fa fa-table'
            ],
        ],
        'Reports' => [
            'View Reports' => [
                'route' => 'admin.reports.index',
                'icon' => 'fa fa-table'
            ],
        ],
    ],
];
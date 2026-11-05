<?php

class HomeController
{
    public static function index(): void
    {
        $byRole = User::countByRole();
        $byStatus = Order::countByStatus();

        render('home/index', [
            'title'   => 'Фриланс-биржа заказов и исполнителей',
            'resumes' => Resume::best(9),
            'orders'  => Order::latest(5),
            'reviews' => Review::latest(8),
            'stats'   => [
                'executors' => $byRole['executor'],
                'open'      => $byStatus['open'],
                'completed' => $byStatus['completed'],
            ],
        ]);
    }

    public static function category(): void
    {
        $section = Category::find((int)query('id'));
        if (!$section || $section['parent_id'] !== null) {
            not_found();
        }

        render('home/category', [
            'title'   => $section['name'],
            'section' => $section,
            'groups'  => Category::children($section['id']),
            'counts'  => Category::openOrderCounts(),
        ]);
    }

    public static function info(): void
    {
        $pages = [
            'about'   => 'О проекте',
            'rules'   => 'Пользовательское соглашение',
            'privacy' => 'Политика обработки персональных данных',
            'faq'     => 'Вопрос – ответ',
            'support' => 'Служба поддержки',
        ];
        $slug = query('slug');
        if (!isset($pages[$slug])) {
            not_found();
        }

        render('home/info', ['title' => $pages[$slug], 'slug' => $slug]);
    }
}

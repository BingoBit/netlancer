<?php

class ResumeController
{
    public static function index(): void
    {
        $filters = [
            'q'        => query('q'),
            'category' => (int)query('category'),
        ];
        $total = Resume::countSearch($filters);

        render('resumes/index', [
            'title'   => 'Исполнители',
            'filters' => $filters,
            'resumes' => Resume::search($filters, PER_PAGE, page_offset()),
            'total'   => $total,
            'pages'   => pages_count($total),
        ]);
    }

    public static function show(): void
    {
        $resume = Resume::find((int)query('id'));
        if (!$resume) {
            not_found();
        }
        $user = current_user();
        $isOwner = $user && $user['id'] === $resume['user_id'];
        $isAdmin = $user && $user['role'] === 'admin';
        if (($resume['is_hidden'] || $resume['is_blocked']) && !$isOwner && !$isAdmin) {
            not_found();
        }

        render('resumes/show', [
            'title'   => $resume['user_name'] . ' – ' . $resume['title'],
            'resume'  => $resume,
            'reviews' => Review::forExecutor($resume['user_id']),
            'isOwner' => $isOwner,
            'portfolio' => self::links($resume['portfolio']),
            'gallery' => gallery_list($resume['gallery'] ?? null),
        ]);
    }

    public static function edit(): void
    {
        $user = require_role('executor');
        $resume = Resume::findByUser($user['id']);
        $form = $resume ?: [
            'category_id' => 0,
            'title' => '',
            'about' => '',
            'skills' => '',
            'rate' => '',
            'portfolio' => '',
            'photo' => null,
            'gallery' => null,
        ];
        $errors = [];

        if (is_post()) {
            check_csrf();
            $form = [
                'category_id' => (int)input('category_id'),
                'title'       => input('title'),
                'about'       => input('about'),
                'skills'      => input('skills'),
                'rate'        => input('rate'),
                'portfolio'   => input('portfolio'),
                'photo'       => $resume['photo'] ?? null,
                'gallery'     => $resume['gallery'] ?? null,
            ];

            if (!Category::isLeaf($form['category_id'])) {
                $errors['category_id'] = 'Выберите основную категорию.';
            }
            if (text_length($form['title']) < 3 || text_length($form['title']) > 150) {
                $errors['title'] = 'Специализация – от 3 до 150 символов.';
            }
            if (text_length($form['about']) < 30 || text_length($form['about']) > 5000) {
                $errors['about'] = 'Расскажите об опыте подробнее: от 30 до 5000 символов.';
            }
            if (text_length($form['skills']) < 2 || text_length($form['skills']) > 255) {
                $errors['skills'] = 'Перечислите навыки через запятую (до 255 символов).';
            }
            if ($form['rate'] !== '' && (!ctype_digit($form['rate']) || (int)$form['rate'] > 1000000)) {
                $errors['rate'] = 'Ставка – целое число рублей в час.';
            }
            foreach (self::links($form['portfolio']) as $link) {
                if (!filter_var($link, FILTER_VALIDATE_URL) || !preg_match('~^https?://~i', $link)) {
                    $errors['portfolio'] = 'Ссылки на портфолио должны начинаться с http:// или https://, по одной в строке.';
                    break;
                }
            }

            $gallery = gallery_list($form['gallery']);
            $removeGallery = $_POST['remove_gallery'] ?? [];
            if (!is_array($removeGallery)) {
                $removeGallery = [];
            }
            $kept = [];
            foreach ($gallery as $file) {
                if (in_array($file, $removeGallery, true)) {
                    delete_upload($file);
                } else {
                    $kept[] = $file;
                }
            }
            $gallery = $kept;

            $newFiles = $_FILES['gallery'] ?? null;
            if ($newFiles && is_array($newFiles['name'] ?? null)) {
                $count = count($newFiles['name']);
                for ($i = 0; $i < $count; $i++) {
                    if (($newFiles['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE || ($newFiles['name'][$i] ?? '') === '') {
                        continue;
                    }
                    if (count($gallery) >= UPLOAD_GALLERY_MAX) {
                        $errors['gallery'] = 'В галерею можно добавить не больше ' . UPLOAD_GALLERY_MAX . ' фотографий.';
                        break;
                    }
                    $one = [
                        'name'     => $newFiles['name'][$i],
                        'type'     => $newFiles['type'][$i],
                        'tmp_name' => $newFiles['tmp_name'][$i],
                        'error'    => $newFiles['error'][$i],
                        'size'     => $newFiles['size'][$i],
                    ];
                    $saved = save_image_upload($one, 'u' . $user['id'] . '_work');
                    if (!$saved['ok']) {
                        $errors['gallery'] = $saved['error'];
                        break;
                    }
                    if ($saved['file']) {
                        $gallery[] = $saved['file'];
                    }
                }
            }

            $form['gallery'] = gallery_json($gallery);

            if (!$errors) {
                Resume::save($user['id'], $form);
                flash('success', 'Резюме сохранено и показывается в каталоге исполнителей.');
                redirect(url('cabinet'));
            }

            $form['gallery'] = gallery_json($gallery);
        }

        render('resumes/form', [
            'title'  => 'Моё резюме',
            'form'   => $form,
            'errors' => $errors,
            'isNew'  => !$resume,
            'gallery'=> gallery_list($form['gallery'] ?? null),
            'user'   => $user,
        ]);
    }

    private static function links(?string $text): array
    {
        $links = [];
        foreach (preg_split('/\R/u', (string)$text) as $line) {
            if (trim($line) !== '') {
                $links[] = trim($line);
            }
        }
        return $links;
    }
}

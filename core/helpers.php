<?php
// Общие вспомогательные функции

const ORDER_STATUSES = [
    'moderation'  => 'На модерации',
    'open'        => 'Открыт',
    'in_progress' => 'В работе',
    'completed'   => 'Завершён',
    'cancelled'   => 'Отменён',
    'rejected'    => 'Отклонён',
];

const RESPONSE_STATUSES = [
    'sent'     => 'Отправлен',
    'accepted' => 'Принят',
    'rejected' => 'Отклонён',
];

const ROLES = [
    'customer' => 'Заказчик',
    'executor' => 'Исполнитель',
    'admin'    => 'Администратор',
];

const LANGUAGES = [
    'ru' => 'Русский',
    'en' => 'English',
    'uk' => 'Українська',
    'kk' => 'Қазақша',
    'be' => 'Беларуская',
    'de' => 'Deutsch',
    'fr' => 'Français',
    'es' => 'Español',
    'tr' => 'Türkçe',
    'zh' => '中文',
];

function language_codes(?string $raw): array
{
    $codes = [];
    foreach (explode(',', (string)$raw) as $code) {
        $code = trim($code);
        if ($code !== '' && isset(LANGUAGES[$code]) && !in_array($code, $codes, true)) {
            $codes[] = $code;
        }
    }
    return $codes;
}

function language_label(?string $code): string
{
    $code = $code ?: 'ru';
    return LANGUAGES[$code] ?? $code;
}

function languages_label(?string $raw): string
{
    $codes = language_codes($raw) ?: ['ru'];
    return implode(', ', array_map('language_label', $codes));
}

// Экранирование перед выводом в HTML (защита от XSS)
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Адрес страницы: url('order', ['id' => 5]) → ?page=order&id=5
function url(string $page, array $params = []): string
{
    return '?' . http_build_query(['page' => $page] + $params);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

// Вывод шаблона из папки views внутри общего макета (шапка и подвал)
function render(string $view, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../views/layout/header.php';
    require __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/layout/footer.php';
}

// Ответ в формате JSON для запросов из JavaScript (чат)
function render_json(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function not_found(): void
{
    http_response_code(404);
    render('errors/404', ['title' => 'Страница не найдена']);
    exit;
}

function forbidden(): void
{
    http_response_code(403);
    render('errors/403', ['title' => 'Доступ запрещён']);
    exit;
}

// ---------- сообщения после перехода на другую страницу ----------

function flash(string $type, string $text): void
{
    $_SESSION['flash'][] = ['type' => $type, 'text' => $text];
}

function take_flashes(): array
{
    $list = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $list;
}

// ---------- защита форм от CSRF ----------

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

// Вызывается в начале обработки каждой POST-формы
function check_csrf(): void
{
    if (!hash_equals(csrf_token(), input('csrf'))) {
        http_response_code(419);
        exit('Страница устарела. Вернитесь назад, обновите её и повторите действие.');
    }
}

// ---------- данные запроса ----------

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// Значение поля формы (если вместо строки прислали массив – считаем поле пустым)
function input(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

// Значение параметра из адресной строки
function query(string $key): string
{
    $value = $_GET[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

function text_length(string $text): int
{
    return mb_strlen($text);
}

// ---------- форматирование ----------

function money($amount): string
{
    return number_format((int)$amount, 0, ',', ' ') . ' ₽';
}

function date_ru(?string $date, bool $withTime = false): string
{
    if (!$date) {
        return '';
    }
    return date($withTime ? 'd.m.Y H:i' : 'd.m.Y', strtotime($date));
}

// plural(3, 'отклик', 'отклика', 'откликов') → «3 отклика»
function plural(int $n, string $one, string $few, string $many): string
{
    $mod10 = $n % 10;
    $mod100 = $n % 100;
    if ($mod10 === 1 && $mod100 !== 11) {
        $word = $one;
    } elseif ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 10 || $mod100 >= 20)) {
        $word = $few;
    } else {
        $word = $many;
    }
    return $n . ' ' . $word;
}

// Буквы для круглого аватара: «Мария Лебедева» → «МЛ»
function initials(string $name): string
{
    $letters = '';
    foreach (array_slice(preg_split('/\s+/u', trim($name)), 0, 2) as $part) {
        $letters .= mb_strtoupper(mb_substr($part, 0, 1));
    }
    return $letters;
}

// Пять звёзд, закрашенных по оценке
function stars($rating): string
{
    $rating = (float)$rating;
    $html = '<span class="stars" title="Оценка ' . e(number_format($rating, 1, ',', '')) . ' из 5">';
    for ($i = 1; $i <= 5; $i++) {
        $html .= '<span class="star' . ($i <= round($rating) ? ' on' : '') . '">★</span>';
    }
    return $html . '</span>';
}

function status_badge(string $status): string
{
    return '<span class="badge badge-' . e($status) . '">' . e(ORDER_STATUSES[$status] ?? $status) . '</span>';
}

function response_badge(string $status): string
{
    return '<span class="badge badge-r-' . e($status) . '">' . e(RESPONSE_STATUSES[$status] ?? $status) . '</span>';
}

function category_icon(?string $icon): string
{
    return 'assets/img/icons/' . ($icon ?: 'line/layout.svg');
}

const UPLOAD_RESUME_DIR = 'assets/uploads/resumes';
const UPLOAD_MAX_BYTES = 2097152; // 2 МБ
const UPLOAD_GALLERY_MAX = 8;

function upload_dir(): string
{
    $dir = __DIR__ . '/../' . UPLOAD_RESUME_DIR;
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    return $dir;
}

function upload_url(string $filename): string
{
    return UPLOAD_RESUME_DIR . '/' . rawurlencode($filename);
}

function gallery_list(?string $json): array
{
    if ($json === null || $json === '') {
        return [];
    }
    $list = json_decode($json, true);
    return is_array($list) ? array_values(array_filter($list, 'is_string')) : [];
}

function gallery_json(array $files): ?string
{
    $files = array_values(array_filter($files, 'is_string'));
    return $files ? json_encode($files, JSON_UNESCAPED_UNICODE) : null;
}

// Сохраняет загруженный файл изображения. Возвращает имя файла или текст ошибки.
function save_image_upload(array $file, string $prefix): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => true, 'file' => null];
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Не удалось загрузить файл. Попробуйте ещё раз.'];
    }
    if (($file['size'] ?? 0) > UPLOAD_MAX_BYTES) {
        return ['ok' => false, 'error' => 'Размер файла не должен превышать 2 МБ.'];
    }

    $info = @getimagesize($file['tmp_name']);
    $allowed = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_WEBP => 'webp',
        IMAGETYPE_GIF  => 'gif',
    ];
    if (!$info || !isset($allowed[$info[2]])) {
        return ['ok' => false, 'error' => 'Допускаются только изображения JPG, PNG, WEBP или GIF.'];
    }

    $name = $prefix . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$info[2]];
    if (!move_uploaded_file($file['tmp_name'], upload_dir() . '/' . $name)) {
        return ['ok' => false, 'error' => 'Не удалось сохранить файл на сервере.'];
    }
    return ['ok' => true, 'file' => $name];
}

function delete_upload(?string $filename): void
{
    if (!$filename || str_contains($filename, '/') || str_contains($filename, '\\') || str_contains($filename, '..')) {
        return;
    }
    $path = upload_dir() . '/' . $filename;
    if (is_file($path)) {
        unlink($path);
    }
}

// Круглый аватар пользователя: фото профиля или инициалы
function user_avatar(array $user, string $extraClass = ''): string
{
    $class = trim('avatar ' . $extraClass);
    $name = $user['name'] ?? $user['user_name'] ?? '';
    $photo = $user['avatar'] ?? $user['photo'] ?? null;
    if ($photo) {
        return '<img class="' . e($class) . ' avatar-photo" src="' . e(upload_url($photo)) . '" alt="' . e($name) . '">';
    }
    return '<span class="' . e($class) . '">' . e(initials($name)) . '</span>';
}

function resume_avatar(array $resume, string $extraClass = ''): string
{
    return user_avatar([
        'name'   => $resume['user_name'] ?? '',
        'avatar' => $resume['avatar'] ?? $resume['photo'] ?? null,
    ], $extraClass);
}

// ---------- постраничный вывод ----------

function current_page(): int
{
    return max(1, (int)query('p'));
}

function page_offset(): int
{
    return (current_page() - 1) * PER_PAGE;
}

function pages_count(int $total): int
{
    return max(1, (int)ceil($total / PER_PAGE));
}

// Ссылка на другую страницу списка с сохранением фильтров
function page_url(int $number): string
{
    $params = $_GET;
    $params['p'] = $number;
    return '?' . http_build_query($params);
}

</main>

<footer class="footer">
    <div class="footer-logo"><a href="<?= url('home') ?>">NetLancer</a></div>
    <div class="container footer-cols">
        <div>
            <h4>О NetLancer</h4>
            <a href="<?= url('info', ['slug' => 'about']) ?>">О проекте</a>
            <a href="<?= url('info', ['slug' => 'rules']) ?>">Пользовательское соглашение</a>
            <a href="<?= url('info', ['slug' => 'privacy']) ?>">Политика обработки ПД</a>
        </div>
        <div>
            <h4>Сообщество</h4>
            <a href="<?= url('orders') ?>">Заказы</a>
            <a href="<?= url('resumes') ?>">Исполнители</a>
            <a href="<?= url('category', ['id' => 1]) ?>">Дизайн</a>
        </div>
        <div>
            <h4>Помощь</h4>
            <a href="<?= url('info', ['slug' => 'faq']) ?>">Вопрос – ответ</a>
            <a href="<?= url('info', ['slug' => 'support']) ?>">Служба поддержки</a>
        </div>
    </div>
    <p class="container copyright">NetLancer</p>
</footer>
</body>
</html>

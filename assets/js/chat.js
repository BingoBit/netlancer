const chat = document.getElementById('chat');

if (chat) {
    const box = document.getElementById('chat-messages');
    const form = document.getElementById('chat-form');
    const field = form.elements.text;
    const dialogId = chat.dataset.dialog;
    let lastId = Number(chat.dataset.lastId);
    let loading = false;

    const scrollDown = () => {
        box.scrollTop = box.scrollHeight;
    };

    const addMessage = (message) => {
        if (box.querySelector('[data-id="' + message.id + '"]')) {
            return;
        }
        const item = document.createElement('div');
        item.className = message.mine ? 'msg mine' : 'msg';
        item.dataset.id = message.id;

        const text = document.createElement('p');
        text.textContent = message.text;
        const time = document.createElement('time');
        time.textContent = message.time;

        item.append(text, time);
        box.append(item);
        lastId = Math.max(lastId, message.id);
        box.querySelector('.empty')?.remove();
    };

    const loadNew = async () => {
        if (loading || document.hidden) {
            return;
        }
        loading = true;
        try {
            const response = await fetch('?page=chat_poll&id=' + dialogId + '&after=' + lastId);
            const data = await response.json();
            if (data.messages && data.messages.length) {
                const nearBottom = box.scrollHeight - box.scrollTop - box.clientHeight < 80;
                data.messages.forEach(addMessage);
                if (nearBottom) {
                    scrollDown();
                }
            }
        } catch (error) {
        }
        loading = false;
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const text = field.value.trim();
        if (!text) {
            return;
        }
        const body = new FormData(form);
        field.value = '';
        try {
            const response = await fetch('?page=chat_send', { method: 'POST', body });
            const data = await response.json();
            if (!data.ok) {
                field.value = text;
                alert(data.error);
                return;
            }
        } catch (error) {
            field.value = text;
            alert('Не удалось отправить сообщение. Проверьте соединение.');
            return;
        }
        await loadNew();
        scrollDown();
    });

    field.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });

    scrollDown();
    setInterval(loadNew, Number(chat.dataset.interval) * 1000);
    document.addEventListener('visibilitychange', loadNew);
}

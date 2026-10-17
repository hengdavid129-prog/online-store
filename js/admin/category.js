import { addCategoryOption, removeCategoryOptions } from "./item.js";

const input = document.getElementById('category');
const message = document.getElementById('message');
const rows = document.getElementById('catRows');

async function ask(data) {
    try {
        const response = await fetch('api/categories.php', {method: 'POST', body: data});
        const answer = await response.json();
    
        message.textContent = answer.message;
        message.className = answer.ok ? 'text-success' : 'text-danger';
    
        return answer;
    } catch (error) {
        console.error('Error talking to the server:', error);

        message.textContent = 'Something went wrong. Please try again.';
        message.className = 'text-danger';

        return {ok: false};
    }
}

function renumber() {
    [...rows.rows].forEach((row, i) => row.cells[0].textContent = i + 1);
}

document.getElementById('addBtn').addEventListener('click', async () => {
    const data = new FormData();
    data.append('action', 'add');
    data.append('category', input.value);

    const answer = await ask(data);
    if (!answer.ok) return;

    const row = rows.insertRow();
    row.insertCell().textContent = rows.rows.length;
    row.insertCell().textContent = answer.category;
    row.insertCell().innerHTML = `<input type="checkbox" class="form-check" value="${answer.id}">`;

    addCategoryOption(answer.id, answer.category);

    input.value = '';
});

document.getElementById('delBtn').addEventListener('click', async () => {
    const checked = [...rows.querySelectorAll('input:checked')];
    const ids = checked.map(box => box.value);

    const data = new FormData();
    data.append('action', 'delete');
    checked.forEach(box => data.append('ids[]',box.value));

    const answer = await ask(data);
    if (!answer.ok) return;

    checked.forEach(box => box.closest('tr').remove());
    removeCategoryOptions(ids);
    renumber();
})
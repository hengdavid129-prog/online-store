<?php
declare(strict_types=1);
?>

<h3>Admin Forms</h3>
<p id="message" class="text-success"></p>

<div class="row">
    <div class="col-md-6">
        <h5>New Item</h5>
        <div class="row gx-3 gy-2 align-items-center">
            <div class="col-12">
                <select id="cateid" class="form-control">
                    <option value="" selected>Chooes Category...</option>
                </select>
            </div>
            <div class="col-12">
                <input type="text" class="form-control" id="itemname" name="itemname" placeholder="Item name">
            </div>
            <div class="col-12">
                <input type="text" class="form-control" id="pricein" name="pricein" placeholder="Price in">
            </div>
            <div class="col-12">
                <input type="text" class="form-control" id="pricesale" name="pricesale" placeholder="Price Sale">
            </div>
            <div class="col-12">
                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description"></textarea>
            </div>
            <div class="col-12">
                <input type="file" class="form-control" id="imagepath" name="imagepath">
            </div>
            <div class="col-12">
                <button class="btn btn-info" id="addItemBtn">Add Item</button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h5>Categories</h5>
        <table class="table table-striped table-hover">
            <tbody id="catRows">
                <?php foreach (Category::getCategories() as $index => $cat): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($cat->category) ?></td>
                        <td><input type="checkbox" class="form-check" value="<?= (int) $cat->id ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="row gx-3 gy-2 align-items-center">
            <div class="col-8">
                <input type="text" class="form-control" id="category" placeholder="Category">
            </div>
            <div class="col-4">
                <button class="btn btn-info" id="addBtn">Add</button>
                <button class="btn btn-warning" id="delBtn">Delete</button>
            </div>
        </div>
        <hr>
        <h5>Item Images</h5>
    </div>
</div>
<script type="module" src="/js/admin/category.js"></script>
<script type="module" src="/js/admin/item.js"></script>

<script>
    // const input = document.getElementById('category');
    // const message = document.getElementById('message');
    // const rows = document.getElementById('catRows');

    // async function ask(data) {
    //     const response = await fetch('api/categories.php', {method: 'POST', body: data});
    //     const answer = await response.json();

    //     message.textContent = answer.message;
    //     message.className = answer.ok ? 'text-success' : 'text-danger';

    //     return answer;
    // }

    // function renumber() {
    //     [...rows.rows].forEach((row, i) => row.cells[0].textContent = i + 1);
    // }

    // document.getElementById('addBtn').addEventListener('click', async () => {
    //     const data = new FormData();
    //     data.append('action', 'add');
    //     data.append('category', input.value);

    //     const answer = await ask(data);
    //     if (!answer.ok) return;

    //     const row = rows.insertRow();
    //     row.insertCell().textContent = rows.rows.length;
    //     row.insertCell().textContent = answer.category;
    //     row.insertCell().innerHTML = `<input type="checkbox" class="form-check" value="${answer.id}">`;

    //     input.value = '';
    // });

    // document.getElementById('delBtn').addEventListener('click', async () => {
    //     const checked = [...rows.querySelectorAll('input:checked')];

    //     const data = new FormData();
    //     data.append('action', 'delete');
    //     checked.forEach(box => data.append('ids[]',box.value));

    //     const answer = await ask(data);
    //     if (!answer.ok) return;

    //     checked.forEach(box => box.closest('tr').remove());
    //     renumber();
    // })
</script>
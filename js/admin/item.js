const selectElement = document.getElementById('cateid');
const itemName = document.getElementById('itemname');
const priceIn = document.getElementById('pricein');
const priceSale = document.getElementById('pricesale');
const description = document.getElementById('description');
const imagePath = document.getElementById('imagepath');
const addItemBtn = document.getElementById('addItemBtn');
const message = document.getElementById('message');

async function getCategory() {
    const params = new URLSearchParams({
        action: 'all'
    })

    const response = await fetch(`/api/categories.php?${params}`, {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
    })
    if (!response.ok) {
        throw new Error(`Http error! Status: ${response.status}`);
    }
    const answer = await response.json();
    return answer.categories;
}

export function addCategoryOption(id, category) {
    const option = document.createElement('option');
    option.value = id;
    option.textContent = category;

    selectElement.add(option);
}

export function removeCategoryOptions(ids) {
    const wanted = ids.map(String);

    [...selectElement.options]
        .filter(option => wanted.includes(option.value))
        .forEach(option => option.remove());
    
    if (selectElement.selectedIndex === -1) {
        selectElement.selectedIndex = 0;
    }
}

try {
    for (const c of await getCategory()) {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = c.category;
        selectElement.add(option);
    }
} catch (error) {
    console.error('Error fetching categories:', error);
}

addItemBtn.addEventListener('click', async () => {
    try {
        const data = new FormData();
        data.append('catid', selectElement.value);
        data.append('itemname', itemName.value);
        data.append('pricein', priceIn.value);
        data.append('pricesale', priceSale.value);
        data.append('info', description.value);
        data.append('imagepath', imagePath.files[0]);
    
        const response = await fetch('/api/items.php', {method: 'POST', body: data});
        const answer = await response.json();
    
        message.textContent = answer.message;
        message.className = answer.ok ? 'text-success' : 'text-danger';
        if (answer.ok) {
            selectElement.value = '';
            itemName.value = '';
            priceIn.value = '';
            priceSale.value = '';
            description.value = '';
            imagePath.value = '';
        }
        addItemBtn.disable = true;
    } catch (error) {
        console.error('Error talking to the server:', error);

        message.textContent = 'Something went wrong. Please try again';
        message.className = 'text-danger';
    } finally {
        addItemBtn.disable = false;
    }
});
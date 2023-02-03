const addCategory = (event) => {
  async function add_category() {
    try {
      const reponse = await fetch('/api/categories', {
        method: "POST",
        body: new FormData(event.target.closest('form'))
      });
      const retour = await reponse.json();
      if (retour.code === 'success') {
        let category = retour.category;
        const newCategory = document.createElement('tr');
        newCategory.dataset.id = category.id;
        newCategory.classList = 'text-center';
        newCategory.innerHTML = `
        <td>${document.querySelectorAll('tbody tr').length}</td>
        <td>${category.id}</td>  
        <td>${category.name}</td>  
        <td>
        <button class="btn btn-danger remove-category">Supprimer</button>
        </td>
        `;
        document.querySelector('tbody').appendChild(newCategory);
        newCategory.querySelector('.remove-category').addEventListener('click', removeCategory);

        const message = document.createElement('div');
        message.innerHTML = `
        <div class="alert alert-${retour.code}">${retour.msg}</div>
        `;
        document.querySelector('#messages').appendChild(message);
        setTimeout(() => message.remove(), 3000);
      }
    } catch (error) {
      console.log(error);
    }
  }
  add_category();
}

const removeCategory = (event) => {
  async function remove_category() {
    try {
      let reponse = await fetch('/api/categories/' + event.target.closest('tr').dataset.id, {
        method: 'DELETE'
      });
      const retour = await reponse.json();
      if (retour.code === 'success') {
        event.target.closest('tr').remove();
        const message = document.createElement('div');
        message.innerHTML = `
        <div class="alert alert-${retour.code}">${retour.msg}</div>
        `
        document.querySelector('#messages').appendChild(message);
        setTimeout(() => message.remove(), 3000);
      }
    } catch (error) {
      console.log(error);
    }
  }
  remove_category();
}
if (document.querySelector('#page-category')) {
  document.querySelectorAll('.remove-category').forEach(btn => btn.addEventListener('click', removeCategory));
  document.querySelector('.add-category').addEventListener('click', addCategory);
}
// document.querySelector('.add-category').addEventListener('click', () => {
//   console.log('Hello');
// });
const addResp = (event) => {
  async function add_resp() {
    try {
      const response = await fetch('/api/reponses', {
        method: 'POST',
        body: new FormData(event.target.closest('form'))
      });
      const retour = await response.json();
      if (retour.code === 'success') {
        let reponse = retour.reponse;
        const newResp = document.createElement('tr');
        newResp.dataset.id = reponse.id;
        newResp.classList = 'text-center';
        newResp.innerHTML = `
        <td>${document.querySelectorAll('tbody tr').length}</td>
        <td>${reponse.id}</td>
        <td>${reponse.libelle}</td>
        <td>${reponse.success ? 'Bonne' : 'Fausse'}</td>
        <td>
        <button class="btn btn-danger remove-resp">Supprimer</button>
        </td>
        `;
        document.querySelector('tbody').appendChild(newResp);
        newResp.querySelector('.remove-resp').addEventListener('click', removeResp);

        const message = document.createElement('div');
        message.innerHTML = `
        <div class="alert alert${retour.code}">${retour.msg}</div>
        `;
        document.querySelector('#messages').appendChild(message);
        setTimeout(() => message.remove(), 3000);

      }
    } catch (error) {
      console.log(error);
    }
  }
  add_resp();
}

const removeResp = (event) => {
  async function remove_resp() {
    try {
      const response = await fetch('/api/reponses' + event.target.closest('tr').dataset.id, {
        method: 'DELETE'
      });
      const retour = await response.json();
      if (retour.code === 'success') {
        event.target.closest('tr').remove();
        const message = document.createElement('div');
        message.innerHTML = `
        <div class="alert alert-${retour.code}">${retour.msg}</div>
        `
        document.querySelector('#message').appendChild(message);
        setTimeout(() => message.remove(), 3000);
      }
    } catch (error) {
      console.log(error);
    }
  }
  remove_resp();
}
if (document.querySelector('#page-reponse')) {
  document.querySelectorAll('.remove-resp').forEach(btn => btn.addEventListener('click', removeResp));
  document.querySelector('.add-resp').addEventListener('click', addResp);
}
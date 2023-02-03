const addClasse = (event) => {

  async function add_classe() {
    try {
      const reponse = await fetch('/api/classes', {
        method: "POST",
        body: new FormData(event.target.closest('form'))
        // headers: {
        //   'Content-Type': 'multipart/form-data'
        // }
      });
      const retour = await reponse.json();

      if (retour.code === 'success') {
        let classe = retour.classe;
        const newClasse = document.createElement('tr');
        newClasse.dataset.id = classe.id;
        newClasse.classList = 'text-center';
        newClasse.innerHTML = `
        <td>${document.querySelectorAll('tbody tr').length}</td>
        <td>${classe.id}</td>  
        <td>${classe.name}</td>  
        <td>0</td>
        <td>0</td>
        <td>${classe.active ? 'Oui' : 'Non'}</td>  
        <td>
        <button class="btn btn-danger remove-classe">Supprimer</button>
        </td>
        `;
        document.querySelector('tbody').appendChild(newClasse);
        newClasse.querySelector('.remove-classe').addEventListener('click', removeClasse);

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
  add_classe();
}

const removeClasse = (event) => {
  async function remove_classe() {
    try {
      let reponse = await fetch('/api/classes/' + event.target.closest('tr').dataset.id, {
        method: 'DELETE'
      })
        .then(response => response.json())
        .then(json => {
          event.target.closest('tr').remove();

          const message = document.createElement('div');
          message.innerHTML = `
          <div class="alert alert-${json.code}">${json.msg}</div>
          `;
          document.querySelector('#messages').appendChild(message);
          setTimeout(() => message.remove(), 3000);

        });
    } catch (error) {
      console.log(error);
    }
  }
  remove_classe();
}

if (document.querySelector('#page-classes')) {
  document.querySelector('#formulaire').addEventListener('submit', (e) => {
    e.preventDefault();
    document.querySelector('.add-classe').dispatchEvent(new Event('click'));

  });
  document.querySelectorAll('.remove-classe').forEach(btn => btn.addEventListener('click', removeClasse));
  document.querySelector('.add-classe').addEventListener('click', addClasse);
}


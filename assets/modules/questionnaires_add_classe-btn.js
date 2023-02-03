const addQuestionnaireClasse = (event) => {

  async function add_classe() {
    try {
      const reponse = await fetch('/api/questionnaire/add_classe/' + event.target.closest('tr').dataset.id + '/' + event.target.closest('tbody').dataset.id, {
        method: "PATCH",
      });
      const retour = await reponse.json();

      if (retour.code === 'success') {

        let classe = event.target.closest('tr');
        let btn = event.target;
        document.querySelector('#classes_liees').appendChild(classe);
        btn.innerHTML = "Supprimer";
        btn.classList.add('btn-danger', 'questionnaire_add_classe_btn');
        btn.classList.remove('btn-primary', 'remove_classe_btn');
        btn.removeEventListener('click', addQuestionnaireClasse);
        btn.addEventListener('click', removeQuestionnaireClasse);

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

const removeQuestionnaireClasse = (event) => {
  async function remove_classe() {
    try {
      let reponse = await fetch('/api/remove_classe/' + event.target.closest('tr').dataset.id, {
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

if (document.querySelector('#classes_proposees')) {
  document.querySelectorAll('.remove_classe_btn').forEach(btn => btn.addEventListener('click', removeQuestionnaireClasse));
  document.querySelectorAll('.questionnaire_add_classe_btn').forEach(btn => btn.addEventListener('click', addQuestionnaireClasse));
}


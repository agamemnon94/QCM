const addQuestion = function (event) {
    let btn = event.target;
    let question = btn.closest('tr');

    async function add_question() {
        try {
            let reponse = await fetch('/api/question/add', {
                method: "POST",
                body: JSON.stringify({
                    id_questionnaire: btn.dataset.id,
                    id_question: btn.dataset.question
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            let listeDesquestionsPosees = document.querySelector('#questions_posees');
            listeDesquestionsPosees.appendChild(question);
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-danger');
            btn.innerText = "Supprimer";
            btn.removeEventListener('click', addQuestion)
            btn.addEventListener('click', removeQuestion);
        } catch (error) {
            console.log(error);
        }
    }

    add_question();

};

const removeQuestion = function (event) {
    let btn = event.target;
    let question = btn.closest('tr');
    async function remove_question() {
        try {
            let reponse = await fetch('/api/question/remove', {
                method: "POST",
                body: JSON.stringify({
                    id_questionnaire: btn.dataset.id,
                    id_question: btn.dataset.question
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            let listeDesquestionsDisponibles = document.querySelector('#questions_disponibles');
            listeDesquestionsDisponibles.appendChild(question);
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-primary');
            btn.innerText = "Ajouter";
            btn.removeEventListener('click', removeQuestion)
            btn.addEventListener('click', addQuestion)
        } catch (error) {
            console.log(error);
        }
    };

    remove_question();

}

document.querySelectorAll('.add-btn').forEach(
    (btn) => {
        btn.addEventListener('click', addQuestion);
    }
);

document.querySelectorAll('.remove-btn').forEach(
    (btn) => {
        btn.addEventListener('click', removeQuestion);
    }
);



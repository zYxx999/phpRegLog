const terms = document.getElementById('terms');
const submit = document.getElementById('submit');

terms.addEventListener('chage', (e)=>
{
    submit.disabled = !e.currentTarget.checked;
})
// assets/js/register.js

// 1. GESTION DES RÔLES (On le laisse en dehors car appelé par onclick="")
window.selectRole = function(role, clickedCard) {
    console.log("Rôle sélectionné : " + role); 
    
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    clickedCard.classList.add('selected');
    
    const radio = clickedCard.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
};

// 2. INITIALISATION (Une fois que le HTML est prêt)
document.addEventListener('DOMContentLoaded', function() {
    
    // --- PARTIE COMPÉTENCES (TAGS) ---
    let selectedSkills = [];
    const maxSkills = 5;

    const input = document.getElementById('custom-skill-input');
    const container = document.getElementById('selected-tags-container');
    const hiddenInput = document.getElementById('skillsInput');
    const counter = document.getElementById('skill-counter');

    window.addTag = function(label) {
        label = label.trim();
        if (selectedSkills.length >= maxSkills) {
            alert("Limite de 5 compétences atteinte.");
            return;
        }
        if (label === "" || selectedSkills.includes(label)) {
            if(input) input.value = "";
            return;
        }
        selectedSkills.push(label);
        updateDisplay();
        if(input) input.value = "";
    };

    window.removeTag = function(index) {
        selectedSkills.splice(index, 1);
        updateDisplay();
    };

    function updateDisplay() {
        if (!container) return;
        container.innerHTML = "";
        selectedSkills.forEach((skill, index) => {
            const tag = document.createElement('span');
            tag.className = "skill-tag selected";
            tag.innerHTML = `${skill} <span onclick="removeTag(${index})" style="cursor:pointer; font-weight:bold; margin-left:8px;">&times;</span>`;
            container.appendChild(tag);
        });
        if (hiddenInput) hiddenInput.value = selectedSkills.join(',');
        if (counter) counter.innerText = selectedSkills.length;
    }

    if (input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                addTag(this.value);
            }
        });
    }

    // --- PARTIE LANGUES (C'EST ICI QUE JE L'AI RAJOUTÉ) ---
    const langCheckboxes = document.querySelectorAll('input[name="langs[]"]');
    const finalLangsInput = document.getElementById('final-languages');

    if (langCheckboxes.length > 0) {
        langCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                let selected = Array.from(document.querySelectorAll('input[name="langs[]"]:checked'))
                                     .map(cb => cb.value);
                
                if (finalLangsInput) {
                    finalLangsInput.value = selected.join(', ');
                    console.log("Langues sélectionnées : ", finalLangsInput.value); // Pour tes tests
                }
            });
        });
    }
});
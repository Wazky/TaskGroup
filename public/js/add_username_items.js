//file : /public/js/add_items.js

document.addEventListener('DOMContentLoaded', function() {

    const memberInput = document.getElementById('memberInput');
    const addMemberBtn = document.getElementById('buttonAddMember');
    const membersContainer = document.getElementById('memberContainer');
    const membersHiddenInputs = document.getElementById('membersHiddenInputs');

    let members = [];

    Array.from(membersHiddenInputs.children).forEach(child => {
        members.push(child.value);
    });

    updateMembersDisplay();

    function addMember(username) {
        if (!username.trim()) { 
            return;
        }

        // Validate if member is already added
        if (members.includes(username)) {
            showAlert('Member already added.', 'warning');
            return;
        }

        members.push(username);

        updateMembersDisplay();
        updateHiddenInputs();

        memberInput.value = ''
    }

    function removeMember(username) {
        members = members.filter(member => member !== username);
        
        updateMembersDisplay();
        updateHiddenInputs();
        
        showAlert('Member removed.', 'info');
    }

    function updateMembersDisplay() {
        membersContainer.innerHTML = '';

        members.forEach(username => {
            const memberTag = document.createElement('div');
            memberTag.className = 'member-tag badge bg-tg-primary  d-flex align-items-center gap-2 px-3 py-2';
            memberTag.innerHTML = `
                <i class="bi bi-person-fill"></i>
                <span>${username}</span>
                <button type="button" class="btn-close btn-close-white btn-close-sm"
                onclick="removeMember('${username}')" aria-label="Remove"></button>    
            `;

            membersContainer.appendChild(memberTag);            
        })

        if (members.length === 0) {
            const emptyMsg =  document.createElement('p');
            emptyMsg.className = 'small text-secondary';
            emptyMsg.textContent = 'No members added.';
            membersContainer.appendChild(emptyMsg);
        }

    }

    function updateHiddenInputs() {
        membersHiddenInputs.innerHTML = '';

        members.forEach(username => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'members[]';
            hiddenInput.value = username;
            membersHiddenInputs.appendChild(hiddenInput);
        })
    }    

    function showAlert(message, type='info') {
        const existingAlert = document.querySelector('.memberAlert');

        if (existingAlert) {
            existingAlert.remove();
        }

        const alert = document.createElement('div');
        alert.className = `memberAlert alert alert-${type} alert-dismissible fade show mt-2 d-flex align-items-center justify-content-between`;
        alert.innerHTML = `
            <div class="flex-grow-1">${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const inuptGroupElement = document.getElementById('input-group-container');
        inuptGroupElement.appendChild(alert);

        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 3000);
    }

    // Event listeners

    addMemberBtn.addEventListener('click', function() {
        addMember(memberInput.value);
    });

    memberInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addMember(memberInput.value);
        }
    });

    window.removeMember = removeMember;
    
})
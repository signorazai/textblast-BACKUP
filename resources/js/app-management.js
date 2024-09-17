//This is for the tabs highlight (DO NOT MODIFY)
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.tab-button');
    const hiddenInput = document.getElementById('selected_tab');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove the active state from all buttons
            buttons.forEach(btn => btn.classList.remove('text-indigo-500', 'border-b-2', 'border-indigo-500'));

            // Add the active state to the clicked button
            this.classList.add('text-indigo-500', 'border-b-2', 'border-indigo-500');

            // Update the hidden input value
            hiddenInput.value = this.getAttribute('data-value');

            // Handle tab content display (assuming you have tab content divs)
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.add('hidden'));
            document.getElementById(this.getAttribute('data-value')).classList.remove('hidden');
        });
    });

    // Trigger the first tab to be open by default on page load
    buttons[0].click();
});

// JavaScript to handle fetching, displaying, and searching contacts
document.addEventListener('DOMContentLoaded', function () {
    const campusSelect = document.getElementById('campus');
    const filterSelect = document.getElementById('filter');
    const contactsTableBody = document.getElementById('contactsTableBody');
    const contactsSearch = document.getElementById('contactsSearch');

    // Function to fetch and display contacts
    function fetchContacts() {
        const campus = campusSelect.value;
        const filter = filterSelect.value;

        fetch(`/api/contacts?campus=${campus}&filter=${filter}`)
            .then(response => response.json())
            .then(data => {
                contactsTableBody.innerHTML = ''; // Clear existing rows

                if (data.length === 0) {
                    contactsTableBody.innerHTML =
                        '<tr><td colspan="5" class="text-center py-4">No contacts found.</td></tr>';
                } else {
                    data.forEach(contact => {
                        const row = `<tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                        <td class="py-3 px-4 border-b text-gray-600">${contact.stud_fname || contact.emp_fname}</td>
                                        <td class="py-3 px-4 border-b text-gray-600">${contact.stud_lname || contact.emp_lname}</td>
                                        <td class="py-3 px-4 border-b text-gray-600">${contact.stud_mname || contact.emp_mname || ''}</td>
                                        <td class="py-3 px-4 border-b text-gray-600">${contact.stud_contact || contact.emp_contact}</td>
                                        <td class="py-3 px-4 border-b text-gray-600">${contact.stud_email || contact.emp_email}</td>
                                    </tr>`;
                        contactsTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }

                searchTable(); // Apply search filter after fetching contacts
            })
            .catch(error => {
                contactsTableBody.innerHTML =
                    '<tr><td colspan="5" class="text-center py-4 text-red-500">Error fetching contacts.</td></tr>';
            });
    }

    // Function to filter the table based on search input
    function searchTable() {
        const input = contactsSearch.value.toUpperCase();
        const tr = contactsTableBody.getElementsByTagName('tr');

        for (let i = 0; i < tr.length; i++) {
            let showRow = false;
            const td = tr[i].getElementsByTagName('td');
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(input) > -1) {
                        showRow = true;
                        break;
                    }
                }
            }
            tr[i].style.display = showRow ? '' : 'none';
        }
    }

    // Event listeners to trigger fetching contacts
    campusSelect.addEventListener('change', fetchContacts);
    filterSelect.addEventListener('change', fetchContacts);
    contactsSearch.addEventListener('keyup', searchTable);

    // Initial fetch on page load
    fetchContacts();
});

// document.addEventListener('DOMContentLoaded', function () {
//     const importButton = document.getElementById('importButton');
//     const importModal = document.getElementById('importModal');
//     const confirmImportButton = document.getElementById('confirmImportButton');
//     const cancelImportButton = document.getElementById('cancelImportButton');
//     const campusSelect = document.getElementById('campusSelect');

//     // Show the modal when the import button is clicked
//     importButton.addEventListener('click', function () {
//         importModal.classList.remove('hidden');
//     });

//     // Hide the modal when the cancel button is clicked
//     cancelImportButton.addEventListener('click', function () {
//         importModal.classList.add('hidden');
//     });

//     // Handle the import process when the confirm button is clicked
//     confirmImportButton.addEventListener('click', function () {
//         const selectedCampus = campusSelect.value;
//         const importUrl = importButton.getAttribute('data-import-url'); // Get the URL from the data attribute

//         fetch(importUrl, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
//             },
//             body: JSON.stringify({ campus_id: selectedCampus })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 alert(data.success);
//             } else if (data.error) {
//                 alert('Import failed: ' + data.error);
//             }

//             // Hide the modal after the import
//             importModal.classList.add('hidden');
//         })
//         .catch(error => console.error('Error:', error));
//     });
// });
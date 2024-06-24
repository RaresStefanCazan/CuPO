<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lists</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-lists1.css">
   
</head>
<body>
    <header class="header" id="header">
        <div class="nav-container">
            <a class="btn-home active" href="/home/homeL">Home</a>
            <a class="btn-home active" href="/home/logout">Logout</a>
        </div>
    </header>
    <main class="container">
        <div class="filter-section">
            <h2>My Lists</h2>
            <button id="createListBtn">Create New List</button>
            <button id="createGroupBtn">Create New Group</button>
        </div>

        <div class="grid" id="listsGrid">
        </div>
    </main>

    <div id="createListModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('createListModal')">&times;</span>
            <h2 id="modalTitle">Create New List</h2>
            <form id="createListForm">
                <div class="form-group">
                    <label for="listName">Name:</label>
                    <input type="text" id="listName" name="listName" required>
                </div>
                <button type="submit">Create</button>
            </form>
        </div>
    </div>


    <div id="addEmailModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addEmailModal')">&times;</span>
            <h2>Add Email to Group</h2>
            <form id="addEmailForm">
                <input type="hidden" id="listIdInput" name="listId">
                <div class="form-group">
                    <label for="emailInput">Email:</label>
                    <input type="email" id="emailInput" name="email" required>
                </div>
                <button type="submit">Add</button>
            </form>
        </div>
    </div>

    <script>
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) {
                return match[2];
            } else {
                return null;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            let isGroup = false;

            document.getElementById('createListBtn').addEventListener('click', function () {
                isGroup = false;
                document.getElementById('modalTitle').textContent = 'Create New List';
                document.getElementById('createListModal').style.display = 'block';
            });

            document.getElementById('createGroupBtn').addEventListener('click', function () {
                isGroup = true;
                document.getElementById('modalTitle').textContent = 'Create New Group';
                document.getElementById('createListModal').style.display = 'block';
            });

            document.getElementById('createListForm').addEventListener('submit', function (event) {
                event.preventDefault();
                const listName = document.getElementById('listName').value;
                const group = isGroup ? 'yes' : 'no';

                fetch('/home/lists', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name: listName, group: group })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(`${isGroup ? 'Group' : 'List'} created successfully`);
                        loadLists();
                        closeModal('createListModal');
                    } else {
                        alert(`Error creating ${isGroup ? 'group' : 'list'}`);
                    }
                })
                .catch(error => {
                    console.error(`Error creating ${isGroup ? 'group' : 'list'}:`, error);
                    alert(`Error creating ${isGroup ? 'group' : 'list'}: ` + error.message);
                });
            });

            document.getElementById('addEmailForm').addEventListener('submit', function (event) {
                event.preventDefault();
                const email = document.getElementById('emailInput').value;
                const listId = getCookie('currentListId'); 

               
                console.log('Sending data:', { listId: listId, email: email });

                fetch('/home/lists', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ listId: listId, email: email })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Email added successfully');
                    } else {
                        alert('Error adding email');
                    }
                })
                .catch(error => {
                    console.error('Error adding email:', error);
                    alert('Error adding email: ' + error.message);
                });
            });

            function loadLists() {
                fetch('/home/lists')
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text) });
                        }
                        return response.json();
                    })
                    .then(data => {
                        const listsGrid = document.getElementById('listsGrid');
                        listsGrid.innerHTML = ''; 

                        const userLists = data.map(list => list.id); // stochez id-urile listelor userilor
                        localStorage.setItem('userLists', JSON.stringify(userLists)); // salvez id urile in local storage

                        data.forEach(list => {
                            const gridItem = document.createElement('div');
                            gridItem.classList.add('grid-item');

                            gridItem.innerHTML = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="#">${list.name}</a></h5>
                                        <button onclick="seeList(${list.id})">See List</button>
                                        <button onclick="shop(${list.id})">Shop</button>
                                        ${list.group === 'yes' ? `<button onclick="openAddEmailModal(${list.id})">Add Person</button>` : ''}
                                        <button onclick="removeList(${list.id})">Remove</button>
                                    </div>
                                </div>
                            `;

                            listsGrid.appendChild(gridItem);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching lists:', error);
                        alert('Error fetching lists: ' + error.message);
                    });
            }

            window.seeList = function(listId) {
                document.cookie = `currentListId=${listId}; path=/`;
                window.location.href = "/home/mybasket";
            }

            window.shop = function(listId) {
                document.cookie = `currentListId=${listId}; path=/`;
                window.location.href = "/home/shop";
            }

            window.openAddEmailModal = function(listId) {
                document.cookie = `currentListId=${listId}; path=/`;
                document.getElementById('listIdInput').value = listId;
                document.getElementById('addEmailModal').style.display = 'block';
            }

            window.removeList = function(listId) {
                if (confirm('Are you sure you want to delete this list?')) {
                    fetch('/home/lists', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ listId: listId })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text) });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('List deleted successfully');
                            loadLists();
                        } else {
                            alert('Error deleting list');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting list:', error);
                        alert('Error deleting list: ' + error.message);
                    });
                }
            }

           
            loadLists();
        });
    </script>
</body>
</html>

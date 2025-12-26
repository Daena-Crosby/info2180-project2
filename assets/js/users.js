document.addEventListener("DOMContentLoaded", () => {
  const usersTable = document.getElementById("usersTable");

  function loadUsers() {
    fetch("get_users.php")
      .then(response => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.json();
      })
      .then(data => {
        usersTable.innerHTML = "";
        if (data.length === 0) {
          usersTable.innerHTML = `<tr><td colspan="6">No users found.</td></tr>`;
          return;
        }
        data.forEach(user => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.firstname}</td>
            <td>${user.lastname}</td>
            <td>${user.email}</td>
            <td>${user.role}</td>
            <td>${user.created_at}</td>
          `;
          usersTable.appendChild(row);
        });
      })
      .catch(err => {
        console.error("Error loading users:", err);
        usersTable.innerHTML = `<tr><td colspan="6">Error loading users.</td></tr>`;
      });
  }

  loadUsers();
});
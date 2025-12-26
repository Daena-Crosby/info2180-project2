document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const messageBox = document.createElement("div");
  form.parentNode.insertBefore(messageBox, form);

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("add_user.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((html) => {
        messageBox.innerHTML = "";
        if (html.includes("User added successfully")) {
          messageBox.innerHTML = `<div class="success">User added successfully.</div>`;
          form.reset();

          // Optional: refresh user list if on users.php
          if (document.getElementById("usersTable")) {
            fetch("get_users.php")
              .then((res) => res.json())
              .then((data) => {
                const usersTable = document.getElementById("usersTable");
                usersTable.innerHTML = "";
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
              });
          }
        } else {
          const match = html.match(/<div class="error">(.*?)<\/div>/);
          messageBox.innerHTML = match ? match[0] : `<div class="error">Error adding user.</div>`;
        }
      })
      .catch(() => {
        messageBox.innerHTML = `<div class="error">Network error. Please try again.</div>`;
      });
  });
});
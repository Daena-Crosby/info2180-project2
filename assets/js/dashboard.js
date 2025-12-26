document.addEventListener("DOMContentLoaded", () => {
  let contacts = [];
  let currentFilter = "All";
  let searchQuery = "";

  const contactsTable = document.getElementById("contactsTable");
  const searchInput = document.getElementById("searchInput");
  const filterButtons = document.querySelectorAll(".filter-btn");

  function loadContacts() {
    fetch(`get_contacts.php?filter=${encodeURIComponent(currentFilter)}`)
      .then(res => res.json())
      .then(data => {
        contacts = data;
        renderContacts();
      })
      .catch(err => {
        console.error("Error loading contacts:", err);
        contactsTable.innerHTML = `<tr><td colspan="5">Error loading contacts.</td></tr>`;
      });
  }

  function renderContacts() {
    contactsTable.innerHTML = "";
    const filtered = contacts.filter(contact => {
      if (searchQuery) {
        const q = searchQuery.toLowerCase();
        return (
          contact.firstname.toLowerCase().includes(q) ||
          contact.lastname.toLowerCase().includes(q) ||
          contact.email.toLowerCase().includes(q) ||
          contact.company.toLowerCase().includes(q)
        );
      }
      return true;
    });

    if (filtered.length === 0) {
      contactsTable.innerHTML = `<tr><td colspan="5">No contacts found.</td></tr>`;
      return;
    }

    filtered.forEach(contact => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${contact.title} ${contact.firstname} ${contact.lastname}</td>
        <td>${contact.email}</td>
        <td>${contact.company}</td>
        <td><span class="badge ${contact.type === "SALES LEAD" ? "sales" : "support"}">${contact.type}</span></td>
        <td><a href="contact_details.php?id=${contact.id}" class="btn btn-outline">View</a></td>
      `;
      contactsTable.appendChild(row);
    });
  }

  searchInput.addEventListener("input", e => {
    searchQuery = e.target.value;
    renderContacts();
  });

  filterButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      filterButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      currentFilter = btn.dataset.filter;
      loadContacts();
    });
  });

  loadContacts();
});
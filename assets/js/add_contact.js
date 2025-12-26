document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const messageBox = document.createElement("div");
  form.parentNode.insertBefore(messageBox, form);

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("add_contact.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((html) => {
        messageBox.innerHTML = "";
        if (html.includes("Contact added successfully")) {
          messageBox.innerHTML = `<div class="success">Contact added successfully.</div>`;
          form.reset();
        } else {
          const match = html.match(/<div class="error">(.*?)<\/div>/);
          messageBox.innerHTML = match ? match[0] : `<div class="error">Error adding contact.</div>`;
        }
      })
      .catch(() => {
        messageBox.innerHTML = `<div class="error">Network error. Please try again.</div>`;
      });
  });
});
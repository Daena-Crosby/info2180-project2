document.addEventListener("DOMContentLoaded", () => {
  const noteForm = document.querySelector("form textarea[name='comment']")?.form;
  const notesSection = document.querySelector("h3 + div") || document.querySelector(".note")?.parentNode;
  const messageBox = document.createElement("div");
  noteForm?.parentNode.insertBefore(messageBox, noteForm);

  noteForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(noteForm);

    fetch(window.location.href, {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((html) => {
        messageBox.innerHTML = "";
        if (html.includes("Note cannot be empty")) {
          messageBox.innerHTML = `<div class="error">Note cannot be empty.</div>`;
        } else {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, "text/html");
          const newNotes = doc.querySelectorAll(".note");
          notesSection.innerHTML = "";
          newNotes.forEach((note) => notesSection.appendChild(note));
          noteForm.reset();
        }
      })
      .catch(() => {
        messageBox.innerHTML = `<div class="error">Network error. Please try again.</div>`;
      });
  });
});
document.addEventListener('DOMContentLoaded', () => {
  const likeButtons = document.querySelectorAll('.like-btn');
  const likeSound = document.getElementById('like-sound');
  const errorSound = document.getElementById('error-sound');
  const modal = document.getElementById('voto-modal');
  const modalText = document.getElementById('modal-text');
  const closeModal = document.getElementById('cerrar-modal');

  likeButtons.forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-id');
      const contadorSpan = button.querySelector('.contador');

      fetch('votar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(id)}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          likeSound.currentTime = 0;
          likeSound.play();
          contadorSpan.textContent = "1"; // Mostrar siempre "1" al usuario
          button.classList.add('liked');
          button.disabled = true;
          showModal("¡Gracias por tu voto!");
        } else {
          errorSound.currentTime = 0;
          errorSound.play();
          showModal(data.error || "Error al votar");
        }
      })
      .catch(() => {
        errorSound.currentTime = 0;
        errorSound.play();
        showModal("Error de comunicación con el servidor");
      });
    });
  });

  function showModal(message) {
    modalText.textContent = message;
    modal.classList.add('show');
  }

  closeModal.addEventListener('click', () => {
    modal.classList.remove('show');
  });

  window.addEventListener('click', (event) => {
    if (event.target === modal) {
      modal.classList.remove('show');
    }
  });
});

const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const inicio_btn = document.querySelector("#inicio"); 
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

inicio_btn.addEventListener("click", () => {
  window.location.href = 'index.html';
});


function validarCPF(cpf) {
  cpf = cpf.replace(/[^\d]+/g, ''); 

  if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) {
      return false;
  }

  let soma = 0;
  let resto;

  for (let i = 1; i <= 9; i++) {
      soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf.substring(9, 10))) return false;

  soma = 0;
  for (let i = 1; i <= 10; i++) {
      soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf.substring(10, 11))) return false;

  return true;
}


document.querySelector('input[name="edCPF"]').addEventListener('input', function() {
    const cpf = this.value;

    if (!validarCPF(cpf)) {
        this.setCustomValidity('CPF inválido.'); 
    } else {
        this.setCustomValidity(''); 
    }
});


document.querySelector('.sign-up-form').addEventListener('submit', function(event) {
  const cpfInput = document.querySelector('input[name="edCPF"]');
  const cpf = cpfInput.value;

  if (!validarCPF(cpf)) {
      event.preventDefault(); 
      alert('CPF inválido. Por favor, insira um CPF válido.');
  }
});

const cpfInput = document.querySelector('input[name="edCPF"]');

function aplicarMascaraCPF(cpf) {
    cpf = cpf.replace(/\D/g, ''); 

    if (cpf.length <= 3) {
        return cpf;
    } else if (cpf.length <= 6) {
        return `${cpf.slice(0, 3)}.${cpf.slice(3)}`;
    } else if (cpf.length <= 9) {
        return `${cpf.slice(0, 3)}.${cpf.slice(3, 6)}.${cpf.slice(6)}`;
    } else {
        return `${cpf.slice(0, 3)}.${cpf.slice(3, 6)}.${cpf.slice(6, 9)}-${cpf.slice(9, 11)}`;
    }
}

cpfInput.addEventListener('input', function() {
    let cpf = this.value;
    cpf = aplicarMascaraCPF(cpf);
    this.value = cpf;

    if (!validarCPF(cpf.replace(/\D/g, ''))) {
        this.setCustomValidity('CPF inválido.'); 
    } else {
        this.setCustomValidity('');
    }
});

function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, ''); 

    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) {
        return false; 
    }

    let soma = 0;
    let resto;

    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;

    soma = 0;
    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;

    return true;
}

document.querySelector('.sign-up-form').addEventListener('submit', function(event) {
    const cpf = cpfInput.value.replace(/\D/g, ''); 

    if (!validarCPF(cpf)) {
        event.preventDefault();
        alert('CPF inválido. Por favor, insira um CPF válido.');
    }
});

const telefoneInput = document.querySelector('input[name="edTelefone"]');

function aplicarMascaraTelefone(telefone) {
    telefone = telefone.replace(/\D/g, '');

    if (telefone.length <= 2) {
        return `(${telefone}`;
    } else if (telefone.length <= 7) {
        return `(${telefone.slice(0, 2)}) ${telefone.slice(2)}`;
    } else {
        return `(${telefone.slice(0, 2)}) ${telefone.slice(2, 7)}-${telefone.slice(7, 11)}`;
    }
}

telefoneInput.addEventListener('input', function() {
    let telefone = this.value;
    telefone = aplicarMascaraTelefone(telefone);
    this.value = telefone;
});

document.querySelector('.sign-up-form').addEventListener('submit', function(event) {
    const telefone = telefoneInput.value.replace(/\D/g, '');

    if (telefone.length < 10 || telefone.length > 11) {
        event.preventDefault();
        alert('Número de telefone inválido. Por favor, insira um número de telefone válido.');
    }
});

function showErrorMessage(message) {
    const errorMessage = document.getElementById("error-message");
    errorMessage.textContent = message;
    errorMessage.style.display = "block";
    
    setTimeout(() => {
      errorMessage.style.display = "none";
    }, 5000);
  }
  

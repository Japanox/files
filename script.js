// Array para armazenar os comandos a serem executados quando online
var commandQueue = [];

function autoSave() {
    // Coleta os dados do formulário
    var nomeInput = document.getElementById("nome");
    var numeroInput = document.getElementById("numero");

    if (nomeInput && numeroInput) {
        var nome = nomeInput.value;
        var numero = numeroInput.value;

        // Valide os dados, se necessário

        // Verifica se o aplicativo está offline
        if (!navigator.onLine) {
            // Salva os dados no localStorage
            try {
                localStorage.setItem("nome", nome);
                localStorage.setItem("numero", numero);
                console.log("Dados salvos no localStorage.");
            } catch (e) {
                // Trate exceções, por exemplo, o localStorage está cheio
                console.error("Erro ao salvar dados no localStorage: " + e.message);
            }

            // Adicione o comando à fila
            commandQueue.push({ nome: nome, numero: numero });
            console.log("Comando adicionado à fila de sincronização.");
        } else {
            // Se estiver online, envie imediatamente
            sendFormData(nome, numero);
        }
    }
}

// Função para enviar os dados do formulário
function sendFormData(nome, numero) {
    // Faça uma solicitação para enviar os dados ao servidor
    fetch('process.php', {
        method: 'POST',
        body: JSON.stringify({ nome: nome, numero: numero }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(function(response) {
        if (response.status === 200) {
            console.log("Dados enviados com sucesso.");
        }
    })
    .catch(function(error) {
        console.error("Erro ao enviar dados: " + error);
    });
}

// Função para carregar os dados do localStorage quando a página é carregada
window.onload = function () {
    loadSavedData();
};

function loadSavedData() {
    var nomeInput = document.getElementById("nome");
    var numeroInput = document.getElementById("numero");

    if (nomeInput && numeroInput) {
        var nome = localStorage.getItem("nome");
        var numero = localStorage.getItem("numero");

        if (nome) {
            nomeInput.value = nome;
        }

        if (numero) {
            numeroInput.value = numero;
        }
    }
}

// Função para pré-visualizar o formulário
function previewForm() {
    // Coleta os dados do formulário
    var nomeInput = document.getElementById("nome");
    var numeroInput = document.getElementById("numero");

    if (nomeInput && numeroInput) {
        var nome = nomeInput.value;
        var numero = numeroInput.value;

        // Crie um formulário oculto para a pré-visualização
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "preview.php"); // Certifique-se de que "preview.php" seja o destino correto

        // Crie campos de texto ocultos para enviar os dados para a pré-visualização
        var nomeInputHidden = document.createElement("input");
        nomeInputHidden.setAttribute("type", "hidden");
        nomeInputHidden.setAttribute("name", "nome");
        nomeInputHidden.setAttribute("value", nome);
        form.appendChild(nomeInputHidden);

        var numeroInputHidden = document.createElement("input");
        numeroInputHidden.setAttribute("type", "hidden");
        numeroInputHidden.setAttribute("name", "numero");
        numeroInputHidden.setAttribute("value", numero);
        form.appendChild(numeroInputHidden);

        // Abra a pré-visualização em uma nova janela ou guia
        form.style.display = "none";
        document.body.appendChild(form);
        form.submit();
    }
}

// Função para limpar os campos e o localStorage
function clearFields() {
    var nomeInput = document.getElementById("nome");
    var numeroInput = document.getElementById("numero");

    if (nomeInput && numeroInput) {
        // Limpa os campos
        nomeInput.value = "";
        numeroInput.value = "";

        // Limpa o localStorage
        localStorage.removeItem("nome");
        localStorage.removeItem("numero");
        console.log("Campos limpos e dados removidos do localStorage.");
    }
}

// Função para sincronizar os dados pendentes
function syncData() {
    if (commandQueue.length > 0) {
        // A conexão está disponível, envie os comandos para o servidor
        while (commandQueue.length > 0) {
            var command = commandQueue.shift();
            sendFormData(command.nome, command.numero);
        }
        console.log('Dados enviados quando a conexão está disponível.');
    }
}

// Função para verificar a conexão e enviar dados automaticamente quando online
function checkConnectionAndSendData() {
    if (navigator.onLine) {
        syncData();
    } else {
        console.log('Aplicativo offline. Os dados foram armazenados localmente e serão enviados quando online.');
    }
}

// ...

// Evento para verificar a conexão sempre que houver uma mudança
window.addEventListener('online', function () {
    console.log('Conexão online detectada.');
    checkConnectionAndSendData();
});

window.addEventListener('offline', function () {
    console.log('Conexão offline detectada.');
    // Exiba a página de offline aqui, se necessário
});

var cacheName = 'xxx';

var urlsToCache = [
  '/',
  '/styles.css', // Caminho para o CSS
  '/script.js',  // Caminho para o JavaScript
];

self.addEventListener('install', function(event) {
  console.log('Service Worker instalado.');
  event.waitUntil(
    caches.open(cacheName)
      .then(function(cache) {
        console.log('Cache aberto.');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
  console.log('Evento de busca:', event.request.url);
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        if (response) {
          console.log('Recurso recuperado do cache:', event.request.url);
          return response; // Se o recurso estiver em cache, retorne-o
        }

        // Se o recurso não estiver em cache, faça uma solicitação de rede
        // Verifique se a solicitação não é um POST antes de buscar a rede
        if (event.request.method === 'GET') {
          return fetch(event.request)
            .then(function(response) {
              if (!response || response.status !== 200 || response.type !== 'basic') {
                console.log('Resposta inválida:', response);
                return response; // Se a resposta não for válida, retorne-a diretamente
              }

              var responseToCache = response.clone(); // Clone a resposta

              // Abra o cache e coloque a resposta nele para uso futuro
              caches.open(cacheName)
                .then(function(cache) {
                  cache.put(event.request, responseToCache);
                });

              return response; // Retorne a resposta original
            })
            .catch(function(error) {
              console.error('Erro durante a solicitação de rede:', error);
              throw error; // Rejeite o erro para que ele seja tratado no código JavaScript
            });
        } else {
          return fetch(event.request); // Se for uma solicitação POST, busque diretamente da rede
        }
      })
  );
});

// Evento para sincronização de dados quando a conexão estiver disponível
self.addEventListener('sync', function(event) {
  console.log('Evento de sincronização:', event.tag);
  if (event.tag === 'sync-data') {
    event.waitUntil(syncData());
  }
});

// Função para sincronizar dados
function syncData() {
  console.log('Iniciando sincronização de dados.');
  return new Promise(function(resolve, reject) {
    // Verifique a conexão com a internet aqui
    // Se a conexão estiver disponível, recupere os dados do armazenamento local
    // Envie os dados para o servidor e atualize o armazenamento local

    // Exemplo de lógica de sincronização:
    if (navigator.onLine) {
      // A conexão está disponível, execute a sincronização
      // Recupere os dados do armazenamento local (localStorage, IndexedDB, etc.)
      var nome = localStorage.getItem("nome");
      var numero = localStorage.getItem("numero");

      if (nome && numero) {
        console.log('Enviando dados para o servidor:', nome, numero);
        // Envie os dados para o servidor com uma solicitação POST
        fetch('process.php', {
          method: 'POST',
          body: JSON.stringify({ nome: nome, numero: numero }),
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(function(response) {
          if (response.status === 200) {
            console.log('Dados enviados com sucesso.');
            // A sincronização foi bem-sucedida, atualize o armazenamento local, se necessário
            // Limpe os dados do armazenamento local após a sincronização bem-sucedida
            localStorage.removeItem("nome");
            localStorage.removeItem("numero");
            resolve(); // Indique que a sincronização foi bem-sucedida
          } else {
            console.error('Erro ao sincronizar dados. Status:', response.status);
            reject("Erro ao sincronizar dados: " + response.statusText);
          }
        })
        .catch(function(error) {
          console.error('Erro ao sincronizar dados:', error);
          reject("Erro ao sincronizar dados: " + error);
        });
      } else {
        console.log('Nenhum dado para sincronizar.');
        resolve(); // Não há dados para sincronizar, então a sincronização é bem-sucedida
      }
    } else {
      console.log('Conexão indisponível. Tentando novamente mais tarde.');
      resolve(); // A conexão não está disponível, a sincronização será tentada novamente mais tarde
    }
  });
}

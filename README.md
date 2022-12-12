# laminas-mvc-skeleton

## Instalação Docker
O servidor é iniciado através de containers docker, portanto a engine e o orquestrador devem ser instalados

- Docker engine 
https://docs.docker.com/get-docker/

- Docker Compose *para distros Linux*
https://docker-docs.netlify.app/compose/install/#install-compose
- Pós instalação *para distros Linux*
https://docs.docker.com/engine/install/linux-postinstall/

## Boot do sistema
Os comandos podem ser executados por qualquer terminal bash(gitBash, windows power shell ou o terminal do VScode)
após o clone do repositório, executar os seguintes comandos na pasta raíz do projeto:
```
     sudo chmod -R ./data *Caso esteja utilizando uma distro linux*
     docker-compose up -d --force-recreate 
```
A instalação das dependencias será executada pelo container, para verificar o progresso execute:
> docker-compose logs composer

Após Concluída a instalação basta acessar o endereço http://localhost:90